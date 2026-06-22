import uuid
from pathlib import Path

from fastapi import HTTPException
from PIL import Image, ImageDraw, ImageFont

from app.config import BASE_DIR, OUTPUT_DIR


def _resolve_path(raw_path: str) -> Path:
    path = Path(raw_path)
    if path.is_absolute():
        return path
    return (BASE_DIR / path).resolve()


def _measure_text(draw: ImageDraw.ImageDraw, text: str, font: ImageFont.FreeTypeFont) -> tuple[int, int]:
    bbox = draw.textbbox((0, 0), text, font=font, stroke_width=0)
    return bbox[2] - bbox[0], bbox[3] - bbox[1]


def _wrap_text(
    draw: ImageDraw.ImageDraw,
    text: str,
    font: ImageFont.FreeTypeFont,
    max_width: int,
) -> list[str]:
    paragraphs = text.splitlines() or [text]
    lines: list[str] = []

    for paragraph in paragraphs:
        if not paragraph:
            lines.append("")
            continue

        current = ""
        for char in paragraph:
            candidate = f"{current}{char}"
            width, _ = _measure_text(draw, candidate, font)
            if width <= max_width or not current:
                current = candidate
                continue

            lines.append(current)
            current = char

        if current:
            lines.append(current)

    return lines or [text]


def _calculate_layout(
    draw: ImageDraw.ImageDraw,
    text: str,
    font_path: Path,
    box_width: int,
    box_height: int,
    default_font_size: int,
    min_font_size: int,
    line_spacing: int,
) -> tuple[ImageFont.FreeTypeFont, list[str], int]:
    for font_size in range(default_font_size, min_font_size - 1, -1):
        font = ImageFont.truetype(str(font_path), font_size)
        lines = _wrap_text(draw, text, font, box_width)
        line_heights = [_measure_text(draw, line or "A", font)[1] for line in lines]
        total_height = sum(line_heights) + max(0, len(lines) - 1) * line_spacing
        widest_line = max((_measure_text(draw, line or "A", font)[0] for line in lines), default=0)

        if total_height <= box_height and widest_line <= box_width:
            return font, lines, total_height

    raise HTTPException(status_code=400, detail="文字内容过长，无法放入当前模板区域，请缩短内容。")


def render_text_on_template(template_config: dict, text: str, font_override_path: Path | None = None) -> str:
    image_path = _resolve_path(template_config["image_path"])
    font_path = font_override_path or _resolve_path(template_config["font_path"])

    if not image_path.exists():
        raise HTTPException(status_code=400, detail=f"模板图片不存在: {image_path}")
    if not font_path.exists():
        raise HTTPException(status_code=400, detail=f"字体文件不存在: {font_path}")

    image = Image.open(image_path).convert("RGBA")
    draw = ImageDraw.Draw(image)

    box = template_config["text_box"]
    x = int(box["x"])
    y = int(box["y"])
    width = int(box["width"])
    height = int(box["height"])

    default_font_size = int(template_config.get("default_font_size", 36))
    min_font_size = int(template_config.get("min_font_size", 18))
    line_spacing = int(template_config.get("line_spacing", 8))
    align = template_config.get("align", "center")
    font_color = template_config.get("font_color", "#FFFFFF")
    stroke_color = template_config.get("stroke_color", "#000000")
    stroke_width = int(template_config.get("stroke_width", 0))

    font, lines, total_height = _calculate_layout(
        draw=draw,
        text=text.strip(),
        font_path=font_path,
        box_width=width,
        box_height=height,
        default_font_size=default_font_size,
        min_font_size=min_font_size,
        line_spacing=line_spacing,
    )

    current_y = y + max(0, (height - total_height) // 2)

    for line in lines:
        line_text = line or " "
        line_width, line_height = _measure_text(draw, line_text, font)

        if align == "left":
            current_x = x
        elif align == "right":
            current_x = x + width - line_width
        else:
            current_x = x + max(0, (width - line_width) // 2)

        draw.text(
            (current_x, current_y),
            line_text,
            font=font,
            fill=font_color,
            stroke_width=stroke_width,
            stroke_fill=stroke_color,
        )
        current_y += line_height + line_spacing

    filename = f"{template_config['template_id']}_{uuid.uuid4().hex[:12]}.png"
    output_path = OUTPUT_DIR / filename
    image.save(output_path, format="PNG")
    return filename

