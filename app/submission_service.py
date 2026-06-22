import json
import re
import shutil
import uuid
from datetime import UTC, datetime
from pathlib import Path
from typing import Any

from fastapi import HTTPException, UploadFile
from PIL import Image

from app.config import CONFIGS_DIR, SUBMISSION_IMAGES_DIR, SUBMISSIONS_DIR, TEMPLATES_DIR
from app.font_service import get_default_font_path

ALLOWED_IMAGE_EXTENSIONS = {".png", ".jpg", ".jpeg", ".webp"}


def _sanitize_slug(value: str) -> str:
    slug = re.sub(r"[^a-zA-Z0-9\u4e00-\u9fff]+", "-", value).strip("-").lower()
    return slug[:40] or f"template-{uuid.uuid4().hex[:8]}"


def _submission_file(submission_id: str) -> Path:
    return SUBMISSIONS_DIR / f"{submission_id}.json"


def _load_json(file_path: Path) -> dict[str, Any]:
    with file_path.open("r", encoding="utf-8") as file:
        return json.load(file)


def _write_json(file_path: Path, data: dict[str, Any]) -> None:
    with file_path.open("w", encoding="utf-8") as file:
        json.dump(data, file, ensure_ascii=False, indent=2)


def _validate_lengths(name: str, description: str, uploader_nickname: str) -> None:
    if not (1 <= len(name.strip()) <= 10):
        raise HTTPException(status_code=400, detail="模板名字长度需为 1 到 10 个字。")
    if len(description.strip()) > 30:
        raise HTTPException(status_code=400, detail="模板介绍最多 30 个字。")
    if not (1 <= len(uploader_nickname.strip()) <= 16):
        raise HTTPException(status_code=400, detail="上传者昵称长度需为 1 到 16 位。")


def _validate_text_box(text_box: dict[str, Any], image_width: int, image_height: int) -> dict[str, int]:
    try:
        x = int(text_box["x"])
        y = int(text_box["y"])
        width = int(text_box["width"])
        height = int(text_box["height"])
    except (KeyError, TypeError, ValueError) as error:
        raise HTTPException(status_code=400, detail="文字区域参数不正确。") from error

    if width < 20 or height < 20:
        raise HTTPException(status_code=400, detail="文字区域太小，请重新选择。")
    if x < 0 or y < 0 or x + width > image_width or y + height > image_height:
        raise HTTPException(status_code=400, detail="文字区域超出图片范围。")

    return {"x": x, "y": y, "width": width, "height": height}


async def create_submission(
    *,
    name: str,
    description: str,
    uploader_nickname: str,
    text_box: dict[str, Any],
    image_file: UploadFile,
) -> dict[str, Any]:
    _validate_lengths(name, description, uploader_nickname)

    suffix = Path(image_file.filename or "").suffix.lower()
    if suffix not in ALLOWED_IMAGE_EXTENSIONS:
        raise HTTPException(status_code=400, detail="只支持上传 PNG、JPG、JPEG 或 WEBP 图片。")

    image_bytes = await image_file.read()
    if not image_bytes:
        raise HTTPException(status_code=400, detail="上传图片不能为空。")

    submission_id = f"submission-{uuid.uuid4().hex[:12]}"
    pending_image_path = SUBMISSION_IMAGES_DIR / f"{submission_id}{suffix}"

    with pending_image_path.open("wb") as file:
        file.write(image_bytes)

    try:
        with Image.open(pending_image_path) as image:
            if suffix in {".jpg", ".jpeg"}:
                image = image.convert("RGB")
            else:
                image = image.convert("RGBA")
            image_width, image_height = image.size
            validated_box = _validate_text_box(text_box, image_width, image_height)
            image.save(pending_image_path)
    except HTTPException:
        pending_image_path.unlink(missing_ok=True)
        raise
    except Exception as error:
        pending_image_path.unlink(missing_ok=True)
        raise HTTPException(status_code=400, detail="上传的图片无法识别，请更换后重试。") from error

    data = {
        "submission_id": submission_id,
        "template_slug": _sanitize_slug(name),
        "name": name.strip(),
        "description": description.strip(),
        "uploader_nickname": uploader_nickname.strip(),
        "status": "pending",
        "image_path": str(pending_image_path),
        "preview_url": f"/submission-images/{pending_image_path.name}",
        "image_width": image_width,
        "image_height": image_height,
        "text_box": validated_box,
        "submitted_at": datetime.now(UTC).isoformat(),
    }
    _write_json(_submission_file(submission_id), data)
    return data


def list_pending_submissions() -> list[dict[str, Any]]:
    submissions: list[dict[str, Any]] = []
    for file_path in sorted(SUBMISSIONS_DIR.glob("*.json")):
        data = _load_json(file_path)
        submissions.append(data)
    return submissions


def approve_submission(submission_id: str) -> dict[str, Any]:
    file_path = _submission_file(submission_id)
    if not file_path.exists():
        raise HTTPException(status_code=404, detail="未找到待审核模板。")

    data = _load_json(file_path)
    pending_image_path = Path(data["image_path"])
    if not pending_image_path.exists():
        raise HTTPException(status_code=400, detail="待审核模板图片不存在。")

    template_id = f"{data['template_slug']}-{uuid.uuid4().hex[:6]}"
    target_image_path = TEMPLATES_DIR / f"{template_id}{pending_image_path.suffix.lower()}"
    shutil.copy2(pending_image_path, target_image_path)

    default_font_path = get_default_font_path()
    text_box = data["text_box"]
    default_font_size = max(28, min(int(text_box["height"] * 0.7), 120))

    config_data = {
        "template_id": template_id,
        "name": data["name"],
        "description": data.get("description", ""),
        "submitted_by": data.get("uploader_nickname", ""),
        "image_path": f"templates/{target_image_path.name}",
        "font_path": f"fonts/{default_font_path.name}",
        "text_box": text_box,
        "default_font_size": default_font_size,
        "min_font_size": 18,
        "line_spacing": 10,
        "font_color": "#FFFFFF",
        "stroke_color": "#000000",
        "stroke_width": 3,
        "align": "center",
    }
    _write_json(CONFIGS_DIR / f"{template_id}.json", config_data)

    file_path.unlink(missing_ok=True)
    pending_image_path.unlink(missing_ok=True)
    return config_data


def reject_submission(submission_id: str) -> None:
    file_path = _submission_file(submission_id)
    if not file_path.exists():
        raise HTTPException(status_code=404, detail="未找到待审核模板。")

    data = _load_json(file_path)
    Path(data["image_path"]).unlink(missing_ok=True)
    file_path.unlink(missing_ok=True)


def delete_template(template_id: str) -> None:
    config_path = CONFIGS_DIR / f"{template_id}.json"
    if not config_path.exists():
        raise HTTPException(status_code=404, detail="未找到指定模板。")

    data = _load_json(config_path)
    image_path = Path(data["image_path"])
    if not image_path.is_absolute():
        image_path = (TEMPLATES_DIR.parent / image_path).resolve()

    config_path.unlink(missing_ok=True)
    image_path.unlink(missing_ok=True)
