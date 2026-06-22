<?php
$apiBaseUrl = getenv('MEME_API_BASE') ?: 'http://127.0.0.1:8000';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>表情包模板工坊</title>
    <style>
        :root {
            color-scheme: light;
            --bg: #f4ecdf;
            --panel: rgba(255, 250, 243, 0.88);
            --panel-strong: #fffaf1;
            --ink: #201612;
            --muted: #7f6b61;
            --accent: #d95d39;
            --accent-dark: #9a3412;
            --line: rgba(77, 52, 42, 0.12);
            --shadow: 0 24px 70px rgba(70, 44, 27, 0.14);
            --radius-xl: 28px;
            --radius-lg: 20px;
            --radius-md: 16px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Microsoft YaHei", "PingFang SC", sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, rgba(255, 202, 154, 0.55), transparent 30%),
                radial-gradient(circle at top right, rgba(255, 233, 196, 0.65), transparent 28%),
                linear-gradient(180deg, #f7efe2 0%, var(--bg) 100%);
        }

        .shell {
            max-width: 1240px;
            margin: 0 auto;
            padding: 36px 24px 64px;
        }

        .hero {
            position: relative;
            overflow: hidden;
            padding: 36px;
            border: 1px solid var(--line);
            border-radius: var(--radius-xl);
            background: linear-gradient(135deg, rgba(255, 251, 247, 0.92), rgba(251, 236, 216, 0.92));
            box-shadow: var(--shadow);
        }

        .hero::after {
            content: "";
            position: absolute;
            inset: auto -50px -60px auto;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(217, 93, 57, 0.25) 0%, rgba(217, 93, 57, 0) 72%);
            pointer-events: none;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.72);
            color: var(--accent-dark);
            font-size: 13px;
            letter-spacing: 0.08em;
        }

        h1 {
            margin: 18px 0 14px;
            font-size: clamp(34px, 5vw, 56px);
            line-height: 1.02;
        }

        .hero p {
            max-width: 760px;
            margin: 0;
            color: var(--muted);
            font-size: 16px;
            line-height: 1.8;
        }

        .status-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 24px;
        }

        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            border-radius: 999px;
            background: rgba(32, 22, 18, 0.05);
            color: var(--muted);
            font-size: 14px;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #f59e0b;
        }

        .status-chip.online .status-dot {
            background: #16a34a;
        }

        .status-chip.error .status-dot {
            background: #dc2626;
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 16px;
            margin: 32px 0 18px;
        }

        .section-head h2 {
            margin: 0;
            font-size: 24px;
        }

        .section-head span {
            color: var(--muted);
            font-size: 14px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 260px));
            justify-content: center;
            gap: 24px;
            padding: 24px;
            border: 1px solid rgba(77, 52, 42, 0.08);
            border-radius: 26px;
            background:
                linear-gradient(180deg, rgba(255, 251, 246, 0.74), rgba(251, 241, 229, 0.74)),
                repeating-linear-gradient(
                    90deg,
                    rgba(77, 52, 42, 0.03) 0,
                    rgba(77, 52, 42, 0.03) 1px,
                    transparent 1px,
                    transparent 24px
                );
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.75);
        }

        .card {
            width: 100%;
            max-width: 260px;
            overflow: hidden;
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            background: rgba(255, 250, 243, 0.96);
            backdrop-filter: blur(10px);
            box-shadow: 0 14px 28px rgba(54, 37, 27, 0.08);
            transition: transform 180ms ease, box-shadow 180ms ease, border-color 180ms ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 22px 42px rgba(54, 37, 27, 0.14);
            border-color: rgba(217, 93, 57, 0.35);
        }

        .card button {
            width: 100%;
            padding: 0;
            border: 0;
            background: none;
            text-align: left;
            cursor: pointer;
            color: inherit;
        }

        .thumb-wrap {
            position: relative;
            aspect-ratio: 4 / 5;
            overflow: hidden;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.8), rgba(241, 223, 206, 0.8));
        }

        .thumb-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .thumb-badge {
            position: absolute;
            top: 14px;
            right: 14px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255, 250, 243, 0.86);
            backdrop-filter: blur(10px);
            font-size: 12px;
            color: var(--accent-dark);
        }

        .card-body {
            padding: 16px 16px 18px;
        }

        .card-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .card-title h3 {
            margin: 0;
            font-size: 18px;
        }

        .card-title span {
            color: var(--muted);
            font-size: 13px;
        }

        .card p {
            margin: 10px 0 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.65;
        }

        .empty-state {
            padding: 40px 24px;
            border: 1px dashed rgba(77, 52, 42, 0.24);
            border-radius: var(--radius-lg);
            background: rgba(255, 251, 247, 0.66);
            text-align: center;
            color: var(--muted);
        }

        .overlay {
            position: fixed;
            inset: 0;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: rgba(33, 23, 18, 0.48);
            backdrop-filter: blur(14px);
            z-index: 50;
        }

        .overlay.open {
            display: flex;
        }

        .dialog {
            width: min(940px, 100%);
            max-height: calc(100vh - 48px);
            overflow: auto;
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 32px;
            background: rgba(255, 249, 243, 0.96);
            box-shadow: 0 26px 90px rgba(29, 17, 11, 0.25);
        }

        .dialog-layout {
            display: grid;
            grid-template-columns: minmax(260px, 360px) minmax(300px, 1fr);
        }

        .dialog-preview {
            padding: 24px;
            background: linear-gradient(180deg, rgba(253, 243, 232, 0.96), rgba(247, 232, 214, 0.96));
            border-right: 1px solid var(--line);
        }

        .dialog-preview img {
            width: 100%;
            display: block;
            border-radius: 22px;
            box-shadow: 0 18px 42px rgba(70, 44, 27, 0.12);
        }

        .dialog-preview h3 {
            margin: 0 0 16px;
            font-size: 22px;
        }

        .preview-meta {
            margin-top: 16px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.8;
        }

        .dialog-main {
            padding: 28px;
        }

        .dialog-head {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: flex-start;
        }

        .dialog-head h3 {
            margin: 0;
            font-size: 24px;
        }

        .dialog-head p {
            margin: 8px 0 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
        }

        .close-btn {
            width: 42px;
            height: 42px;
            border: 1px solid var(--line);
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            cursor: pointer;
            font-size: 22px;
            line-height: 1;
        }

        .form-grid {
            display: grid;
            gap: 18px;
            margin-top: 24px;
        }

        label {
            display: grid;
            gap: 10px;
            font-weight: 600;
            font-size: 14px;
        }

        textarea,
        select {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid rgba(77, 52, 42, 0.14);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.9);
            color: var(--ink);
            font: inherit;
            outline: none;
            transition: border-color 160ms ease, box-shadow 160ms ease;
        }

        textarea {
            min-height: 160px;
            resize: vertical;
            line-height: 1.7;
        }

        textarea:focus,
        select:focus {
            border-color: rgba(217, 93, 57, 0.5);
            box-shadow: 0 0 0 4px rgba(217, 93, 57, 0.14);
        }

        .helper {
            margin: -2px 0 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.6;
        }

        .action-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 8px;
        }

        .primary-btn,
        .secondary-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 136px;
            padding: 14px 18px;
            border-radius: 16px;
            border: 0;
            cursor: pointer;
            font: inherit;
            font-weight: 700;
            transition: transform 160ms ease, opacity 160ms ease, box-shadow 160ms ease;
        }

        .primary-btn {
            background: linear-gradient(135deg, #dd6b44, #bc3f1d);
            color: #fffdf9;
            box-shadow: 0 16px 26px rgba(188, 63, 29, 0.28);
        }

        .secondary-btn {
            background: rgba(32, 22, 18, 0.06);
            color: var(--ink);
        }

        .primary-btn:hover,
        .secondary-btn:hover {
            transform: translateY(-1px);
        }

        .primary-btn:disabled {
            opacity: 0.64;
            cursor: wait;
            transform: none;
        }

        .feedback {
            min-height: 24px;
            margin-top: 14px;
            color: var(--muted);
            font-size: 14px;
        }

        .feedback.error {
            color: #b42318;
        }

        .feedback.success {
            color: #166534;
        }

        @media (max-width: 860px) {
            .shell {
                padding: 20px 16px 48px;
            }

            .hero {
                padding: 24px;
            }

            .grid {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                gap: 16px;
                padding: 16px;
            }

            .card {
                max-width: none;
            }

            .dialog-layout {
                grid-template-columns: 1fr;
            }

            .dialog-preview {
                border-right: 0;
                border-bottom: 1px solid var(--line);
            }

            .action-row {
                flex-direction: column;
            }

            .primary-btn,
            .secondary-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="shell">
        <section class="hero">
            <div class="eyebrow">PHP 模板工坊</div>
            <h1>一页完成模板挑选、字体切换与表情包生成</h1>
            <p>点击任意模板卡片即可打开编辑框，输入文案、切换字体并调用现有 FastAPI 服务生成结果图。生成成功后会自动在新链接中打开图片地址。</p>
            <div class="status-row">
                <div class="status-chip" id="apiStatusChip">
                    <span class="status-dot"></span>
                    <span id="apiStatusText">正在连接后端服务...</span>
                </div>
                <div class="status-chip">
                    <span class="status-dot online"></span>
                    <span id="templateCountText">模板数量读取中</span>
                </div>
                <div class="status-chip">
                    <span class="status-dot online"></span>
                    <span id="fontCountText">字体数量读取中</span>
                </div>
            </div>
        </section>

        <div class="section-head">
            <div>
                <h2>模板列表</h2>
                <span>点击图片即可弹出编辑框，直接生成专属文案表情包。</span>
            </div>
        </div>

        <section id="templateGrid" class="grid" aria-live="polite"></section>
    </div>

    <div class="overlay" id="editorOverlay" aria-hidden="true">
        <div class="dialog" role="dialog" aria-modal="true" aria-labelledby="dialogTitle">
            <div class="dialog-layout">
                <aside class="dialog-preview">
                    <h3 id="selectedTemplateName">模板预览</h3>
                    <img id="selectedTemplateImage" src="" alt="选中模板预览">
                    <div class="preview-meta" id="selectedTemplateMeta"></div>
                </aside>
                <section class="dialog-main">
                    <div class="dialog-head">
                        <div>
                            <h3 id="dialogTitle">编辑文字并选择字体</h3>
                            <p>输入你想替换的文案，字体下拉框会列出当前字体库中的所有可选字体。</p>
                        </div>
                        <button type="button" class="close-btn" id="closeDialogButton" aria-label="关闭编辑框">×</button>
                    </div>

                    <div class="form-grid">
                        <label for="memeTextInput">
                            输入文字
                            <textarea id="memeTextInput" placeholder="例如：今天也要继续加油"></textarea>
                        </label>
                        <p class="helper">如果内容过长，后端会自动缩小字号到模板允许的最小值。</p>

                        <label for="fontSelect">
                            选择字体
                            <select id="fontSelect"></select>
                        </label>
                        <p class="helper">字体列表来自后端扫描的 `fonts/` 目录，新增字体后刷新页面即可看到。</p>

                        <div class="action-row">
                            <button type="button" class="primary-btn" id="generateButton">生成并打开图片</button>
                            <button type="button" class="secondary-btn" id="cancelButton">取消</button>
                        </div>
                        <div class="feedback" id="feedbackText"></div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script>
        const API_BASE = <?php echo json_encode($apiBaseUrl, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

        const state = {
            templates: [],
            fonts: [],
            selectedTemplate: null,
            isGenerating: false
        };

        const templateGrid = document.getElementById('templateGrid');
        const editorOverlay = document.getElementById('editorOverlay');
        const selectedTemplateName = document.getElementById('selectedTemplateName');
        const selectedTemplateImage = document.getElementById('selectedTemplateImage');
        const selectedTemplateMeta = document.getElementById('selectedTemplateMeta');
        const memeTextInput = document.getElementById('memeTextInput');
        const fontSelect = document.getElementById('fontSelect');
        const feedbackText = document.getElementById('feedbackText');
        const generateButton = document.getElementById('generateButton');
        const cancelButton = document.getElementById('cancelButton');
        const closeDialogButton = document.getElementById('closeDialogButton');
        const apiStatusChip = document.getElementById('apiStatusChip');
        const apiStatusText = document.getElementById('apiStatusText');
        const templateCountText = document.getElementById('templateCountText');
        const fontCountText = document.getElementById('fontCountText');

        function joinUrl(base, path) {
            return `${base.replace(/\/$/, '')}/${String(path).replace(/^\//, '')}`;
        }

        function setFeedback(message, type = '') {
            feedbackText.textContent = message || '';
            feedbackText.className = `feedback${type ? ` ${type}` : ''}`;
        }

        function setApiStatus(mode, message) {
            apiStatusChip.classList.remove('online', 'error');
            if (mode) {
                apiStatusChip.classList.add(mode);
            }
            apiStatusText.textContent = message;
        }

        function renderFonts() {
            if (!state.fonts.length) {
                fontSelect.innerHTML = '<option value="">当前没有可用字体</option>';
                return;
            }

            fontSelect.innerHTML = state.fonts.map((font) => {
                return `<option value="${font.font_path}">${font.font_name}</option>`;
            }).join('');
        }

        function createTemplateCard(template) {
            const button = document.createElement('button');
            button.type = 'button';
            button.addEventListener('click', () => openEditor(template));
            button.innerHTML = `
                <div class="thumb-wrap">
                    <img src="${joinUrl(API_BASE, template.preview_url)}" alt="${template.name}">
                    <div class="thumb-badge">${template.image_exists ? '可编辑' : '图片缺失'}</div>
                </div>
                <div class="card-body">
                    <div class="card-title">
                        <h3>${template.name}</h3>
                        <span>${template.template_id}</span>
                    </div>
                    <p>默认字号 ${template.default_font_size}px，文字区域 ${template.text_box.width} × ${template.text_box.height}</p>
                </div>
            `;

            const article = document.createElement('article');
            article.className = 'card';
            article.appendChild(button);
            return article;
        }

        function renderTemplates() {
            if (!state.templates.length) {
                templateGrid.innerHTML = '<div class="empty-state">还没有检测到模板，请先在 `configs/templates/` 中添加模板配置并确认底图存在。</div>';
                return;
            }

            templateGrid.innerHTML = '';
            state.templates.forEach((template) => {
                templateGrid.appendChild(createTemplateCard(template));
            });
        }

        function openEditor(template) {
            state.selectedTemplate = template;
            selectedTemplateName.textContent = template.name;
            selectedTemplateImage.src = joinUrl(API_BASE, template.preview_url);
            selectedTemplateImage.alt = `${template.name} 模板预览`;
            selectedTemplateMeta.innerHTML = `
                模板编号：${template.template_id}<br>
                默认字号：${template.default_font_size}px<br>
                文字区域：x ${template.text_box.x} / y ${template.text_box.y} / ${template.text_box.width} × ${template.text_box.height}
            `;
            memeTextInput.value = '';
            fontSelect.value = template.font_path.includes('fonts/')
                ? `fonts/${template.font_path.split(/[\\/]/).pop()}`
                : state.fonts[0]?.font_path || '';
            setFeedback('');
            editorOverlay.classList.add('open');
            editorOverlay.setAttribute('aria-hidden', 'false');
            setTimeout(() => memeTextInput.focus(), 30);
        }

        function closeEditor() {
            editorOverlay.classList.remove('open');
            editorOverlay.setAttribute('aria-hidden', 'true');
            state.selectedTemplate = null;
            setFeedback('');
        }

        async function loadData() {
            try {
                setApiStatus('', '正在读取模板与字体...');
                const [templatesResponse, fontsResponse] = await Promise.all([
                    fetch(joinUrl(API_BASE, '/templates')),
                    fetch(joinUrl(API_BASE, '/api/fonts'))
                ]);

                if (!templatesResponse.ok) {
                    throw new Error('模板接口读取失败');
                }
                if (!fontsResponse.ok) {
                    throw new Error('字体接口读取失败');
                }

                state.templates = await templatesResponse.json();
                state.fonts = await fontsResponse.json();

                renderTemplates();
                renderFonts();
                templateCountText.textContent = `当前模板 ${state.templates.length} 个`;
                fontCountText.textContent = `当前字体 ${state.fonts.length} 个`;
                setApiStatus('online', '后端服务已连接，可以直接生成');
            } catch (error) {
                console.error(error);
                renderTemplates();
                renderFonts();
                templateCountText.textContent = '模板读取失败';
                fontCountText.textContent = '字体读取失败';
                setApiStatus('error', `接口连接失败：${error.message}`);
            }
        }

        async function generateMeme() {
            if (!state.selectedTemplate || state.isGenerating) {
                return;
            }

            const text = memeTextInput.value.trim();
            if (!text) {
                setFeedback('请先输入文字内容。', 'error');
                memeTextInput.focus();
                return;
            }

            if (!fontSelect.value) {
                setFeedback('请先选择一个字体。', 'error');
                fontSelect.focus();
                return;
            }

            state.isGenerating = true;
            generateButton.disabled = true;
            generateButton.textContent = '正在生成...';
            setFeedback('正在请求后端生成图片，请稍候...');

            try {
                const response = await fetch(joinUrl(API_BASE, '/generate'), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        template_id: state.selectedTemplate.template_id,
                        text,
                        font_path: fontSelect.value
                    })
                });

                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.detail || '生成失败');
                }

                const imageUrl = joinUrl(API_BASE, data.image_url);
                window.open(imageUrl, '_blank', 'noopener');
                setFeedback('生成成功，已在新标签页打开图片。', 'success');
            } catch (error) {
                console.error(error);
                setFeedback(`生成失败：${error.message}`, 'error');
            } finally {
                state.isGenerating = false;
                generateButton.disabled = false;
                generateButton.textContent = '生成并打开图片';
            }
        }

        generateButton.addEventListener('click', generateMeme);
        cancelButton.addEventListener('click', closeEditor);
        closeDialogButton.addEventListener('click', closeEditor);
        editorOverlay.addEventListener('click', (event) => {
            if (event.target === editorOverlay) {
                closeEditor();
            }
        });

        window.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && editorOverlay.classList.contains('open')) {
                closeEditor();
            }
        });

        loadData();
    </script>
</body>
</html>
