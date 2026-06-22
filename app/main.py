import json

from fastapi import Depends, FastAPI, File, Form, HTTPException, UploadFile
from fastapi.middleware.cors import CORSMiddleware
from fastapi.staticfiles import StaticFiles

from app.admin_service import login_admin, require_admin
from app.config import OUTPUT_DIR, SUBMISSION_IMAGES_DIR, TEMPLATES_DIR, ensure_directories
from app.font_service import list_fonts, resolve_font_path
from app.renderer import render_text_on_template
from app.schemas import (
    AdminLoginRequest,
    AdminLoginResponse,
    GenerateRequest,
    GenerateResponse,
)
from app.submission_service import (
    approve_submission,
    create_submission,
    delete_template,
    list_pending_submissions,
    reject_submission,
)
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
app.mount("/submission-images", StaticFiles(directory=str(SUBMISSION_IMAGES_DIR)), name="submission-images")


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


@app.post("/api/submissions")
async def submit_template(
    image: UploadFile = File(...),
    name: str = Form(...),
    description: str = Form(""),
    uploader_nickname: str = Form(...),
    text_box_json: str = Form(...),
) -> dict:
    try:
        text_box = json.loads(text_box_json)
    except json.JSONDecodeError as error:
        raise HTTPException(status_code=400, detail="文字区域参数解析失败。") from error

    return await create_submission(
        name=name,
        description=description,
        uploader_nickname=uploader_nickname,
        text_box=text_box,
        image_file=image,
    )


@app.post("/api/admin/login", response_model=AdminLoginResponse)
def admin_login(payload: AdminLoginRequest) -> AdminLoginResponse:
    token = login_admin(payload.username, payload.password)
    return AdminLoginResponse(token=token, username=payload.username)


@app.get("/api/admin/pending-submissions")
def get_pending_submissions(_: str = Depends(require_admin)) -> list[dict]:
    return list_pending_submissions()


@app.post("/api/admin/submissions/{submission_id}/approve")
def approve_pending_submission(submission_id: str, _: str = Depends(require_admin)) -> dict:
    return approve_submission(submission_id)


@app.delete("/api/admin/submissions/{submission_id}")
def reject_pending_submission(submission_id: str, _: str = Depends(require_admin)) -> dict[str, str]:
    reject_submission(submission_id)
    return {"status": "ok"}


@app.get("/api/admin/templates")
def get_admin_templates(_: str = Depends(require_admin)) -> list[dict]:
    return list_template_configs()


@app.delete("/api/admin/templates/{template_id}")
def delete_admin_template(template_id: str, _: str = Depends(require_admin)) -> dict[str, str]:
    delete_template(template_id)
    return {"status": "ok"}

