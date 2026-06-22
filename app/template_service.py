import json
from pathlib import Path
from typing import Any

from fastapi import HTTPException
from PIL import Image

from app.config import BASE_DIR, CONFIGS_DIR


def _resolve_path(raw_path: str) -> Path:
    path = Path(raw_path)
    if path.is_absolute():
        return path
    return (BASE_DIR / path).resolve()


def list_template_configs() -> list[dict[str, Any]]:
    templates: list[dict[str, Any]] = []
    for file_path in sorted(CONFIGS_DIR.glob("*.json")):
        with file_path.open("r", encoding="utf-8") as file:
            data = json.load(file)

        image_path = _resolve_path(data["image_path"])
        font_path = _resolve_path(data["font_path"])
        text_box = data["text_box"]
        image_width = None
        image_height = None

        if image_path.exists():
            with Image.open(image_path) as image:
                image_width, image_height = image.size

        templates.append(
            {
                "template_id": data["template_id"],
                "name": data.get("name", data["template_id"]),
                "description": data.get("description", ""),
                "submitted_by": data.get("submitted_by", ""),
                "image_path": str(image_path),
                "font_path": str(font_path),
                "preview_url": f"/template-images/{image_path.name}",
                "image_exists": image_path.exists(),
                "font_exists": font_path.exists(),
                "image_width": image_width,
                "image_height": image_height,
                "text_box": text_box,
                "default_font_size": data.get("default_font_size", 36),
            }
        )

    return templates


def get_template_config(template_id: str) -> dict[str, Any]:
    for file_path in CONFIGS_DIR.glob("*.json"):
        with file_path.open("r", encoding="utf-8") as file:
            data = json.load(file)
        if data["template_id"] == template_id:
            return data

    raise HTTPException(status_code=404, detail=f"未找到模板: {template_id}")

