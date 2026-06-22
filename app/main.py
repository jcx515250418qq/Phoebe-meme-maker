from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from fastapi.staticfiles import StaticFiles

from app.config import OUTPUT_DIR, TEMPLATES_DIR, ensure_directories
from app.font_service import list_fonts, resolve_font_path
from app.renderer import render_text_on_template
from app.schemas import GenerateRequest, GenerateResponse
from app.template_service import get_template_config, list_template_configs


ensure_directories()

app = FastAPI(title="Meme Generator API", version="1.0.0")

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

app.mount("/images", StaticFiles(directory=str(OUTPUT_DIR)), name="images")
app.mount("/template-images", StaticFiles(directory=str(TEMPLATES_DIR)), name="template-images")


@app.get("/health")
def health() -> dict[str, str]:
    return {"status": "ok"}


@app.get("/templates")
def get_templates() -> list[dict]:
    return list_template_configs()


@app.get("/api/fonts")
def get_fonts() -> list[dict[str, str]]:
    return list_fonts()


@app.post("/generate", response_model=GenerateResponse)
def generate_image(payload: GenerateRequest) -> GenerateResponse:
    config = get_template_config(payload.template_id)
    font_override_path = resolve_font_path(payload.font_path)
    filename = render_text_on_template(config, payload.text, font_override_path)
    return GenerateResponse(
        template_id=payload.template_id,
        filename=filename,
        image_url=f"/images/{filename}",
    )

