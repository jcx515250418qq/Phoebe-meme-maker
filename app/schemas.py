from pydantic import BaseModel, Field


class GenerateRequest(BaseModel):
    template_id: str = Field(..., min_length=1, max_length=100)
    text: str = Field(..., min_length=1, max_length=200)
    font_path: str | None = Field(default=None, max_length=260)


class GenerateResponse(BaseModel):
    template_id: str
    image_url: str
    filename: str


class AdminLoginRequest(BaseModel):
    username: str = Field(..., min_length=1, max_length=64)
    password: str = Field(..., min_length=1, max_length=64)


class AdminLoginResponse(BaseModel):
    token: str
    username: str

