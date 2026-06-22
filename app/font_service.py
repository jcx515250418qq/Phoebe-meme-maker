from pathlib import Path

from fastapi import HTTPException

from app.config import FONTS_DIR


ALLOWED_FONT_EXTENSIONS = {".ttf", ".otf", ".ttc"}


def list_fonts() -> list[dict[str, str]]:
    fonts: list[dict[str, str]] = []
    for file_path in sorted(FONTS_DIR.iterdir()):
        if not file_path.is_file():
            continue
        if file_path.suffix.lower() not in ALLOWED_FONT_EXTENSIONS:
            continue

        fonts.append(
            {
                "font_name": file_path.stem,
                "font_file": file_path.name,
                "font_path": f"fonts/{file_path.name}",
            }
        )

    return fonts


def resolve_font_path(raw_font_path: str | None) -> Path | None:
    if not raw_font_path:
        return None

    normalized = raw_font_path.replace("\\", "/").strip("/")
    candidate = (FONTS_DIR.parent / normalized).resolve()

    try:
        candidate.relative_to(FONTS_DIR.resolve())
    except ValueError as error:
        raise HTTPException(status_code=400, detail="字体路径不合法。") from error

    if not candidate.exists() or candidate.suffix.lower() not in ALLOWED_FONT_EXTENSIONS:
        raise HTTPException(status_code=400, detail="字体文件不存在。")

    return candidate

