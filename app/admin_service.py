import secrets
from datetime import UTC, datetime, timedelta

from fastapi import Header, HTTPException

ADMIN_USERNAME = "xiaohai123"
ADMIN_PASSWORD = "888888"
TOKEN_TTL = timedelta(hours=12)

_TOKENS: dict[str, datetime] = {}


def login_admin(username: str, password: str) -> str:
    if username != ADMIN_USERNAME or password != ADMIN_PASSWORD:
        raise HTTPException(status_code=401, detail="账号或密码不正确。")

    token = secrets.token_urlsafe(32)
    _TOKENS[token] = datetime.now(UTC) + TOKEN_TTL
    return token


def require_admin(authorization: str | None = Header(default=None)) -> str:
    if not authorization or not authorization.startswith("Bearer "):
        raise HTTPException(status_code=401, detail="请先登录后台。")

    token = authorization.replace("Bearer ", "", 1).strip()
    expires_at = _TOKENS.get(token)

    if not expires_at:
        raise HTTPException(status_code=401, detail="登录状态已失效，请重新登录。")

    if datetime.now(UTC) > expires_at:
        _TOKENS.pop(token, None)
        raise HTTPException(status_code=401, detail="登录状态已过期，请重新登录。")

    return token
