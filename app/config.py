from pathlib import Path


BASE_DIR = Path(__file__).resolve().parent.parent
CONFIGS_DIR = BASE_DIR / "configs" / "templates"
TEMPLATES_DIR = BASE_DIR / "templates"
FONTS_DIR = BASE_DIR / "fonts"
OUTPUT_DIR = BASE_DIR / "output"
DATA_DIR = BASE_DIR / "data"
SUBMISSIONS_DIR = DATA_DIR / "submissions"
SUBMISSION_IMAGES_DIR = DATA_DIR / "submission_images"


def ensure_directories() -> None:
    CONFIGS_DIR.mkdir(parents=True, exist_ok=True)
    TEMPLATES_DIR.mkdir(parents=True, exist_ok=True)
    FONTS_DIR.mkdir(parents=True, exist_ok=True)
    OUTPUT_DIR.mkdir(parents=True, exist_ok=True)
    SUBMISSIONS_DIR.mkdir(parents=True, exist_ok=True)
    SUBMISSION_IMAGES_DIR.mkdir(parents=True, exist_ok=True)

