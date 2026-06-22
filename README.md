# 表情包生成后端

这是一个基于 `FastAPI + Pillow` 的免费后端方案，用于接收前端传来的文字内容，并将文字渲染到指定模板图片区域，生成用户自定义表情包。

## 功能

- `GET /health`：健康检查
- `GET /templates`：获取可用模板列表
- `GET /api/fonts`：获取字体下拉可选项
- `POST /generate`：生成表情包图片
- `GET /images/{filename}`：访问已生成图片
- `GET /template-images/{filename}`：访问模板预览图

## 目录结构

```text
app/
configs/templates/
fonts/
index.php
templates/
output/
requirements.txt
```

## 安装

```bash
python -m venv .venv
.venv\Scripts\activate
pip install -r requirements.txt
```

## 启动

```bash
uvicorn app.main:app --reload
```

默认地址：

- `http://127.0.0.1:8000`
- Swagger 文档：`http://127.0.0.1:8000/docs`

## PHP 前端

项目根目录新增了一个 `index.php` 页面，用于展示模板列表、弹出编辑框、选择字体并调用后端生成图片。

如果你本机已安装 PHP，可以在项目根目录执行：

```bash
php -S 127.0.0.1:8080
```

然后打开：

- `http://127.0.0.1:8080/index.php`

默认情况下，页面会请求 `http://127.0.0.1:8000` 作为后端地址。

如果你的后端地址不是这个，可以先设置环境变量 `MEME_API_BASE` 再启动 PHP。

## 使用前准备

项目已内置一个示例配置文件：`configs/templates/sample.json`

你需要自行放入以下文件：

- 模板图片：`templates/sample.png`
- 字体文件：`fonts/DingTalkJinBuTi-Regular.ttf`

你也可以新增更多模板配置文件，每个模板一个 `json`。

## 模板配置示例

```json
{
  "template_id": "sample",
  "name": "示例模板",
  "image_path": "templates/sample.png",
  "font_path": "fonts/DingTalkJinBuTi-Regular.ttf",
  "text_box": {
    "x": 60,
    "y": 300,
    "width": 400,
    "height": 140
  },
  "default_font_size": 40,
  "min_font_size": 18,
  "line_spacing": 10,
  "font_color": "#FFFFFF",
  "stroke_color": "#000000",
  "stroke_width": 3,
  "align": "center"
}
```

## 生成接口示例

请求：

```bash
curl -X POST "http://127.0.0.1:8000/generate" ^
  -H "Content-Type: application/json" ^
  -d "{\"template_id\":\"sample\",\"text\":\"今天也要加油\"}"
```

响应：

```json
{
  "template_id": "sample",
  "image_url": "/images/sample_xxxxx.png",
  "filename": "sample_xxxxx.png"
}
```

## 前端对接示例

```js
const response = await fetch("http://127.0.0.1:8000/generate", {
  method: "POST",
  headers: {
    "Content-Type": "application/json"
  },
  body: JSON.stringify({
    template_id: "sample",
    text: "今天也要加油"
  })
});

const data = await response.json();
const imageUrl = `http://127.0.0.1:8000${data.image_url}`;
```

## 当前实现说明

- 支持指定模板图片和字体文件
- 支持实时扫描 `fonts/` 目录生成字体下拉列表
- 支持指定文字区域
- 支持自动换行
- 支持字号自动缩小，直到文字放入区域
- 支持居左、居中、居右
- 支持描边颜色和描边宽度
- 支持模板预览图静态访问
- 支持 PHP 页面点击模板后输入文字、选择字体并新开链接查看生成结果

## 后续可扩展

- 接入数据库管理模板
- 支持多文字区域
- 支持用户自定义颜色和字号
- 支持对象存储上传
- 支持 GIF 动图模板
