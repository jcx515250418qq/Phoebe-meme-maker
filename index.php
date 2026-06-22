<?php
$apiBaseUrl = getenv('MEME_API_BASE') ?: '/api-proxy';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>菲比啾比-表情包模版网</title>
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

        button,
        input,
        select,
        textarea {
            font: inherit;
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

        .head-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .pill-link,
        .plus-btn,
        .primary-btn,
        .secondary-btn,
        .danger-btn,
        .ghost-btn {
            border: 0;
            cursor: pointer;
            transition: transform 160ms ease, opacity 160ms ease, box-shadow 160ms ease;
        }

        .pill-link,
        .ghost-btn,
        .secondary-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-width: 120px;
            padding: 14px 18px;
            border-radius: 16px;
            background: rgba(32, 22, 18, 0.06);
            color: var(--ink);
            text-decoration: none;
        }

        .plus-btn {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            background: linear-gradient(135deg, #dd6b44, #bc3f1d);
            color: #fffdf9;
            box-shadow: 0 16px 26px rgba(188, 63, 29, 0.28);
            font-size: 30px;
            line-height: 1;
        }

        .primary-btn,
        .danger-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 136px;
            padding: 14px 18px;
            border-radius: 16px;
            color: #fffdf9;
            font-weight: 700;
        }

        .primary-btn {
            background: linear-gradient(135deg, #dd6b44, #bc3f1d);
            box-shadow: 0 16px 26px rgba(188, 63, 29, 0.28);
        }

        .danger-btn {
            background: linear-gradient(135deg, #c64545, #991b1b);
        }

        .pill-link:hover,
        .plus-btn:hover,
        .primary-btn:hover,
        .secondary-btn:hover,
        .danger-btn:hover,
        .ghost-btn:hover {
            transform: translateY(-1px);
        }

        .primary-btn:disabled,
        .secondary-btn:disabled,
        .danger-btn:disabled,
        .ghost-btn:disabled {
            opacity: 0.64;
            cursor: wait;
            transform: none;
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

        .thumb-guide {
            position: absolute;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6px 8px;
            border: 2px dashed rgba(255, 255, 255, 0.96);
            border-radius: 14px;
            background: rgba(32, 22, 18, 0.22);
            color: #fffdf7;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-align: center;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.45);
            box-shadow: inset 0 0 0 1px rgba(32, 22, 18, 0.12);
            pointer-events: none;
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
            width: min(1040px, 100%);
            max-height: calc(100vh - 48px);
            overflow: auto;
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 32px;
            background: rgba(255, 249, 243, 0.96);
            box-shadow: 0 26px 90px rgba(29, 17, 11, 0.25);
        }

        .dialog-layout {
            display: grid;
            grid-template-columns: minmax(260px, 360px) minmax(320px, 1fr);
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

        .dialog-preview h3,
        .dialog-head h3 {
            margin: 0;
            font-size: 24px;
        }

        .preview-meta,
        .api-card {
            margin-top: 16px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.8;
        }

        .api-card {
            padding: 16px;
            border: 1px solid rgba(77, 52, 42, 0.1);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.66);
        }

        .api-card strong {
            display: block;
            margin-bottom: 8px;
            color: var(--ink);
        }

        .code-block {
            margin: 0;
            padding: 14px;
            border-radius: 16px;
            background: #221a16;
            color: #f8f2ec;
            font-size: 12px;
            line-height: 1.7;
            white-space: pre-wrap;
            word-break: break-word;
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

        .field {
            display: grid;
            gap: 10px;
        }

        label {
            display: grid;
            gap: 10px;
            font-weight: 600;
            font-size: 14px;
        }

        textarea,
        select,
        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid rgba(77, 52, 42, 0.14);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.9);
            color: var(--ink);
            outline: none;
            transition: border-color 160ms ease, box-shadow 160ms ease;
        }

        textarea {
            min-height: 140px;
            resize: vertical;
            line-height: 1.7;
        }

        textarea:focus,
        select:focus,
        input[type="text"]:focus,
        input[type="file"]:focus {
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

        .upload-layout {
            display: grid;
            grid-template-columns: minmax(320px, 1.15fr) minmax(280px, 0.85fr);
        }

        .selector-pane {
            padding: 24px;
            border-right: 1px solid var(--line);
            background: linear-gradient(180deg, rgba(253, 243, 232, 0.96), rgba(247, 232, 214, 0.96));
        }

        .selector-stage {
            position: relative;
            min-height: 420px;
            border: 1px dashed rgba(77, 52, 42, 0.22);
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.68);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            user-select: none;
        }

        .selector-stage.empty::before {
            content: "上传模版图片后，在图片上拖拽选择文字区域";
            color: var(--muted);
            font-size: 14px;
        }

        .selector-canvas {
            position: relative;
            max-width: 100%;
            max-height: 420px;
        }

        .selector-canvas img {
            display: block;
            max-width: 100%;
            max-height: 420px;
            border-radius: 18px;
            box-shadow: 0 12px 34px rgba(50, 32, 22, 0.14);
        }

        .selection-box {
            position: absolute;
            border: 2px solid rgba(217, 93, 57, 0.96);
            border-radius: 12px;
            background: rgba(217, 93, 57, 0.18);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.45);
            pointer-events: none;
        }

        .selection-box::after {
            content: "菲比啾比";
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff9f1;
            font-weight: 700;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.45);
            font-size: clamp(12px, 2vw, 20px);
        }

        .selection-meta {
            margin-top: 14px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .upload-main {
            padding: 28px;
        }

        .two-cols {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }

        .site-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 18px;
        }

        @media (max-width: 960px) {
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

            .dialog-layout,
            .upload-layout {
                grid-template-columns: 1fr;
            }

            .dialog-preview,
            .selector-pane {
                border-right: 0;
                border-bottom: 1px solid var(--line);
            }
        }

        @media (max-width: 720px) {
            .section-head,
            .site-footer {
                flex-direction: column;
                align-items: stretch;
            }

            .head-actions,
            .action-row,
            .two-cols {
                flex-direction: column;
                grid-template-columns: 1fr;
            }

            .pill-link,
            .plus-btn,
            .primary-btn,
            .secondary-btn,
            .danger-btn,
            .ghost-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="shell">
        <section class="hero">
            <div class="eyebrow">菲比啾比精选模版</div>
            <h1>菲比啾比-表情包模版网</h1>
            <p>挑选喜欢的表情包模版，填上你的专属文案，一键生成适合聊天、社交分享和日常整活的个性表情包。你也可以上传自己的图片，提交新模版，让更多人一起玩起来。</p>
            <div class="status-row">
                <div class="status-chip" id="apiStatusChip">
                    <span class="status-dot"></span>
                    <span id="apiStatusText">正在准备模版内容...</span>
                </div>
                <div class="status-chip">
                    <span class="status-dot online"></span>
                    <span id="templateCountText">模版加载中</span>
                </div>
                <div class="status-chip">
                    <span class="status-dot online"></span>
                    <span id="fontCountText">字体加载中</span>
                </div>
            </div>
        </section>

        <div class="section-head">
            <div>
                <h2>模版列表</h2>
                <span>挑一张你喜欢的图，写上想说的话，马上生成你的专属表情包。</span>
            </div>
            <div class="head-actions">
                <a class="pill-link" href="./admin.php">进入审核后台</a>
                <button class="plus-btn" type="button" id="openSubmissionButton" aria-label="投稿新模版">+</button>
            </div>
        </div>

        <section id="templateGrid" class="grid" aria-live="polite"></section>

        <div class="site-footer">
            <span class="helper">想让自己的图片也变成可生成的表情包模版？点击右上角的加号即可投稿。</span>
        </div>
    </div>

    <div class="overlay" id="editorOverlay" aria-hidden="true">
        <div class="dialog" role="dialog" aria-modal="true" aria-labelledby="dialogTitle">
            <div class="dialog-layout">
                <aside class="dialog-preview">
                    <h3 id="selectedTemplateName">模版预览</h3>
                    <img id="selectedTemplateImage" src="" alt="选中模版预览">
                    <div class="preview-meta" id="selectedTemplateMeta"></div>
                    <div class="api-card">
                        <strong>该模版调用 API</strong>
                        <pre class="code-block" id="templateApiCode"></pre>
                        <div class="action-row">
                            <button type="button" class="ghost-btn" id="copyApiButton">复制调用示例</button>
                        </div>
                    </div>
                </aside>
                <section class="dialog-main">
                    <div class="dialog-head">
                        <div>
                            <h3 id="dialogTitle">填写文案并选择字体</h3>
                            <p>输入你想展示的文字，再挑一个喜欢的字体风格，就可以开始生成专属表情包。</p>
                        </div>
                        <button type="button" class="close-btn" id="closeDialogButton" aria-label="关闭弹窗">×</button>
                    </div>

                    <div class="form-grid">
                        <label for="memeTextInput">
                            输入文字
                            <textarea id="memeTextInput" placeholder="例如：今天也要继续可可爱爱"></textarea>
                        </label>
                        <p class="helper">如果文字较长，系统会自动帮你调整到更适合的显示大小。</p>

                        <label for="fontSelect">
                            选择字体
                            <select id="fontSelect"></select>
                        </label>
                        <p class="helper">不同字体会带来不同气质，选一个最适合这张图的风格吧。</p>

                        <div class="action-row">
                            <button type="button" class="primary-btn" id="generateButton">立即生成表情包</button>
                            <button type="button" class="secondary-btn" id="cancelButton">取消</button>
                        </div>
                        <div class="feedback" id="feedbackText"></div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <div class="overlay" id="submissionOverlay" aria-hidden="true">
        <div class="dialog" role="dialog" aria-modal="true" aria-labelledby="submissionDialogTitle">
            <div class="upload-layout">
                <section class="selector-pane">
                    <h3 id="submissionDialogTitle">投稿新模版</h3>
                    <p class="helper">先上传图片，再在图片上拖拽出想要放文字的区域。选中的位置会用“菲比啾比”示意出来。</p>
                    <div class="selector-stage empty" id="selectorStage">
                        <div class="selector-canvas" id="selectorCanvas">
                            <img id="submissionPreviewImage" src="" alt="投稿模版预览" hidden>
                            <div class="selection-box" id="selectionBox" hidden></div>
                        </div>
                    </div>
                    <div class="selection-meta" id="selectionMeta">请先上传模版图片。</div>
                </section>
                <section class="upload-main">
                    <div class="dialog-head">
                        <div>
                            <h3>填写模版资料</h3>
                            <p>填写模版名字、模版介绍和你的昵称，确认后就能提交到后台审核。</p>
                        </div>
                        <button type="button" class="close-btn" id="closeSubmissionButton" aria-label="关闭投稿弹窗">×</button>
                    </div>

                    <div class="form-grid">
                        <label for="submissionImageInput">
                            上传模版图片
                            <input id="submissionImageInput" type="file" accept=".png,.jpg,.jpeg,.webp">
                        </label>

                        <div class="two-cols">
                            <label for="submissionNameInput">
                                模版名字
                                <input id="submissionNameInput" type="text" maxlength="10" placeholder="10字以内">
                            </label>
                            <label for="submissionNicknameInput">
                                上传者昵称
                                <input id="submissionNicknameInput" type="text" maxlength="16" placeholder="1-16位长度">
                            </label>
                        </div>

                        <label for="submissionDescriptionInput">
                            模版介绍
                            <textarea id="submissionDescriptionInput" maxlength="30" placeholder="30字以内，介绍这张模版适合什么场景"></textarea>
                        </label>

                        <div class="action-row">
                            <button type="button" class="primary-btn" id="submitTemplateButton">提交新模版</button>
                            <button type="button" class="secondary-btn" id="cancelSubmissionButton">取消</button>
                        </div>
                        <div class="feedback" id="submissionFeedback"></div>
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
            isGenerating: false,
            submissionImageFile: null,
            submissionNaturalWidth: 0,
            submissionNaturalHeight: 0,
            selectionBox: null,
            dragStart: null,
            isDraggingSelection: false
        };

        const templateGrid = document.getElementById('templateGrid');
        const editorOverlay = document.getElementById('editorOverlay');
        const selectedTemplateName = document.getElementById('selectedTemplateName');
        const selectedTemplateImage = document.getElementById('selectedTemplateImage');
        const selectedTemplateMeta = document.getElementById('selectedTemplateMeta');
        const templateApiCode = document.getElementById('templateApiCode');
        const memeTextInput = document.getElementById('memeTextInput');
        const fontSelect = document.getElementById('fontSelect');
        const feedbackText = document.getElementById('feedbackText');
        const generateButton = document.getElementById('generateButton');
        const cancelButton = document.getElementById('cancelButton');
        const closeDialogButton = document.getElementById('closeDialogButton');
        const copyApiButton = document.getElementById('copyApiButton');
        const apiStatusChip = document.getElementById('apiStatusChip');
        const apiStatusText = document.getElementById('apiStatusText');
        const templateCountText = document.getElementById('templateCountText');
        const fontCountText = document.getElementById('fontCountText');

        const submissionOverlay = document.getElementById('submissionOverlay');
        const openSubmissionButton = document.getElementById('openSubmissionButton');
        const closeSubmissionButton = document.getElementById('closeSubmissionButton');
        const cancelSubmissionButton = document.getElementById('cancelSubmissionButton');
        const submitTemplateButton = document.getElementById('submitTemplateButton');
        const submissionFeedback = document.getElementById('submissionFeedback');
        const submissionImageInput = document.getElementById('submissionImageInput');
        const submissionPreviewImage = document.getElementById('submissionPreviewImage');
        const selectorStage = document.getElementById('selectorStage');
        const selectorCanvas = document.getElementById('selectorCanvas');
        const selectionBox = document.getElementById('selectionBox');
        const selectionMeta = document.getElementById('selectionMeta');
        const submissionNameInput = document.getElementById('submissionNameInput');
        const submissionDescriptionInput = document.getElementById('submissionDescriptionInput');
        const submissionNicknameInput = document.getElementById('submissionNicknameInput');

        function joinUrl(base, path) {
            return `${base.replace(/\/$/, '')}/${String(path).replace(/^\//, '')}`;
        }

        function toAbsoluteUrl(path) {
            const raw = /^https?:\/\//i.test(path) ? path : joinUrl(API_BASE, path);
            return new URL(raw, window.location.origin).toString();
        }

        function escapeHtml(value) {
            return String(value ?? '').replace(/[&<>"']/g, (char) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            })[char]);
        }

        function setFeedback(target, message, type = '') {
            target.textContent = message || '';
            target.className = `feedback${type ? ` ${type}` : ''}`;
        }

        function setApiStatus(mode, message) {
            apiStatusChip.classList.remove('online', 'error');
            if (mode) {
                apiStatusChip.classList.add(mode);
            }
            apiStatusText.textContent = message;
        }

        function createThumbGuide(template) {
            if (!template.image_exists || !template.text_box) {
                return '';
            }

            const box = template.text_box;
            const imageWidth = Number(template.image_width);
            const imageHeight = Number(template.image_height);

            if (!imageWidth || !imageHeight || !box.width || !box.height) {
                return '<div class="thumb-guide" style="left:12%;top:68%;width:76%;height:16%;">菲比啾比</div>';
            }

            const containerWidth = 400;
            const containerHeight = 500;
            const containerAspect = containerWidth / containerHeight;
            const imageAspect = imageWidth / imageHeight;

            let renderedWidth;
            let renderedHeight;
            let offsetX = 0;
            let offsetY = 0;

            if (imageAspect > containerAspect) {
                renderedHeight = containerHeight;
                renderedWidth = renderedHeight * imageAspect;
                offsetX = (renderedWidth - containerWidth) / 2;
            } else {
                renderedWidth = containerWidth;
                renderedHeight = renderedWidth / imageAspect;
                offsetY = (renderedHeight - containerHeight) / 2;
            }

            const left = (((box.x / imageWidth) * renderedWidth) - offsetX) / containerWidth * 100;
            const top = (((box.y / imageHeight) * renderedHeight) - offsetY) / containerHeight * 100;
            const width = ((box.width / imageWidth) * renderedWidth) / containerWidth * 100;
            const height = ((box.height / imageHeight) * renderedHeight) / containerHeight * 100;

            const clampedLeft = Math.max(2, Math.min(88, left));
            const clampedTop = Math.max(2, Math.min(88, top));
            const clampedWidth = Math.max(14, Math.min(96 - clampedLeft, width));
            const clampedHeight = Math.max(10, Math.min(96 - clampedTop, height));
            const fontSize = Math.max(12, Math.min(22, clampedHeight * 0.42));

            return `
                <div
                    class="thumb-guide"
                    style="left:${clampedLeft}%;top:${clampedTop}%;width:${clampedWidth}%;height:${clampedHeight}%;font-size:${fontSize}px;"
                >菲比啾比</div>
            `;
        }

        function renderFonts() {
            if (!state.fonts.length) {
                fontSelect.innerHTML = '<option value="">当前暂无可选字体</option>';
                return;
            }

            fontSelect.innerHTML = state.fonts.map((font) => (
                `<option value="${font.font_path}">${escapeHtml(font.font_name)}</option>`
            )).join('');
        }

        function createTemplateCard(template) {
            const button = document.createElement('button');
            button.type = 'button';
            button.addEventListener('click', () => openEditor(template));
            button.innerHTML = `
                <div class="thumb-wrap">
                    <img src="${joinUrl(API_BASE, template.preview_url)}" alt="${escapeHtml(template.name)}">
                    ${createThumbGuide(template)}
                    <div class="thumb-badge">${template.image_exists ? '立即制作' : '暂不可用'}</div>
                </div>
                <div class="card-body">
                    <div class="card-title">
                        <h3>${escapeHtml(template.name)}</h3>
                        <span>${escapeHtml(template.template_id)}</span>
                    </div>
                    <p>${escapeHtml(template.description || '这张模版正在等待补充介绍。')}</p>
                </div>
            `;

            const article = document.createElement('article');
            article.className = 'card';
            article.appendChild(button);
            return article;
        }

        function renderTemplates() {
            if (!state.templates.length) {
                templateGrid.innerHTML = '<div class="empty-state">当前还没有可展示的模版，请稍后再来看看。</div>';
                return;
            }

            templateGrid.innerHTML = '';
            state.templates.forEach((template) => {
                templateGrid.appendChild(createTemplateCard(template));
            });
        }

        function buildTemplateApiSnippet(template) {
            const url = toAbsoluteUrl('/generate');
            const defaultFont = state.fonts[0]?.font_path || template.font_path || '';
            return [
                `POST ${url}`,
                'Content-Type: application/json',
                '',
                JSON.stringify({
                    template_id: template.template_id,
                    text: '菲比啾比',
                    font_path: defaultFont
                }, null, 2)
            ].join('\n');
        }

        function openEditor(template) {
            state.selectedTemplate = template;
            selectedTemplateName.textContent = template.name;
            selectedTemplateImage.src = joinUrl(API_BASE, template.preview_url);
            selectedTemplateImage.alt = `${template.name} 模版预览`;
            selectedTemplateMeta.innerHTML = `
                模版介绍：${escapeHtml(template.description || '暂无介绍')}<br>
                模版编号：${escapeHtml(template.template_id)}<br>
                调用时把 template_id 换成上面的编号即可
            `;
            templateApiCode.textContent = buildTemplateApiSnippet(template);
            memeTextInput.value = '';
            fontSelect.value = template.font_path.includes('fonts/')
                ? `fonts/${template.font_path.split(/[\\/]/).pop()}`
                : state.fonts[0]?.font_path || '';
            setFeedback(feedbackText, '');
            editorOverlay.classList.add('open');
            editorOverlay.setAttribute('aria-hidden', 'false');
            setTimeout(() => memeTextInput.focus(), 30);
        }

        function closeEditor() {
            editorOverlay.classList.remove('open');
            editorOverlay.setAttribute('aria-hidden', 'true');
            state.selectedTemplate = null;
            setFeedback(feedbackText, '');
        }

        async function loadData() {
            try {
                setApiStatus('', '正在加载模版和字体...');
                const [templatesResponse, fontsResponse] = await Promise.all([
                    fetch(joinUrl(API_BASE, '/templates')),
                    fetch(joinUrl(API_BASE, '/api/fonts'))
                ]);

                if (!templatesResponse.ok) {
                    throw new Error('模版读取失败');
                }
                if (!fontsResponse.ok) {
                    throw new Error('字体读取失败');
                }

                state.templates = await templatesResponse.json();
                state.fonts = await fontsResponse.json();

                renderTemplates();
                renderFonts();
                templateCountText.textContent = `精选模版 ${state.templates.length} 个`;
                fontCountText.textContent = `可选字体 ${state.fonts.length} 款`;
                setApiStatus('online', '内容已准备完成，开始挑选你喜欢的模版吧');
            } catch (error) {
                console.error(error);
                renderTemplates();
                renderFonts();
                templateCountText.textContent = '模版加载失败';
                fontCountText.textContent = '字体加载失败';
                setApiStatus('error', `页面暂时加载失败：${error.message}`);
            }
        }

        async function generateMeme() {
            if (!state.selectedTemplate || state.isGenerating) {
                return;
            }

            const text = memeTextInput.value.trim();
            if (!text) {
                setFeedback(feedbackText, '请先输入文字内容。', 'error');
                memeTextInput.focus();
                return;
            }

            if (!fontSelect.value) {
                setFeedback(feedbackText, '请先选择一个字体。', 'error');
                fontSelect.focus();
                return;
            }

            state.isGenerating = true;
            generateButton.disabled = true;
            generateButton.textContent = '正在生成...';
            setFeedback(feedbackText, '正在为你生成表情包，请稍候...');

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
                setFeedback(feedbackText, '生成成功，已在新标签页为你打开图片。', 'success');
            } catch (error) {
                console.error(error);
                setFeedback(feedbackText, `生成失败：${error.message}`, 'error');
            } finally {
                state.isGenerating = false;
                generateButton.disabled = false;
                generateButton.textContent = '立即生成表情包';
            }
        }

        function openSubmissionOverlay() {
            submissionOverlay.classList.add('open');
            submissionOverlay.setAttribute('aria-hidden', 'false');
            setFeedback(submissionFeedback, '');
        }

        function closeSubmissionOverlay() {
            submissionOverlay.classList.remove('open');
            submissionOverlay.setAttribute('aria-hidden', 'true');
            setFeedback(submissionFeedback, '');
        }

        function resetSubmissionForm() {
            state.submissionImageFile = null;
            state.submissionNaturalWidth = 0;
            state.submissionNaturalHeight = 0;
            state.selectionBox = null;
            state.dragStart = null;
            state.isDraggingSelection = false;
            submissionImageInput.value = '';
            submissionNameInput.value = '';
            submissionDescriptionInput.value = '';
            submissionNicknameInput.value = '';
            submissionPreviewImage.src = '';
            submissionPreviewImage.hidden = true;
            selectionBox.hidden = true;
            selectorStage.classList.add('empty');
            selectionMeta.textContent = '请先上传模版图片。';
            setFeedback(submissionFeedback, '');
        }

        function updateSelectionBox(selection) {
            if (!selection) {
                selectionBox.hidden = true;
                return;
            }

            selectionBox.hidden = false;
            selectionBox.style.left = `${selection.left}px`;
            selectionBox.style.top = `${selection.top}px`;
            selectionBox.style.width = `${selection.width}px`;
            selectionBox.style.height = `${selection.height}px`;
        }

        function setSelectionFromDisplay(selection) {
            const rect = submissionPreviewImage.getBoundingClientRect();
            const scaleX = state.submissionNaturalWidth / rect.width;
            const scaleY = state.submissionNaturalHeight / rect.height;

            state.selectionBox = {
                x: Math.round(selection.left * scaleX),
                y: Math.round(selection.top * scaleY),
                width: Math.round(selection.width * scaleX),
                height: Math.round(selection.height * scaleY)
            };

            selectionMeta.textContent = `文字区域已选中：x ${state.selectionBox.x} / y ${state.selectionBox.y} / ${state.selectionBox.width} × ${state.selectionBox.height}`;
        }

        function startSelection(event) {
            if (submissionPreviewImage.hidden) {
                return;
            }

            const rect = submissionPreviewImage.getBoundingClientRect();
            if (
                event.clientX < rect.left || event.clientX > rect.right ||
                event.clientY < rect.top || event.clientY > rect.bottom
            ) {
                return;
            }

            state.isDraggingSelection = true;
            state.dragStart = {
                x: event.clientX - rect.left,
                y: event.clientY - rect.top
            };
            updateSelectionBox({
                left: state.dragStart.x,
                top: state.dragStart.y,
                width: 0,
                height: 0
            });
        }

        function moveSelection(event) {
            if (!state.isDraggingSelection || !state.dragStart) {
                return;
            }

            const rect = submissionPreviewImage.getBoundingClientRect();
            const currentX = Math.max(0, Math.min(rect.width, event.clientX - rect.left));
            const currentY = Math.max(0, Math.min(rect.height, event.clientY - rect.top));
            const left = Math.min(state.dragStart.x, currentX);
            const top = Math.min(state.dragStart.y, currentY);
            const width = Math.abs(currentX - state.dragStart.x);
            const height = Math.abs(currentY - state.dragStart.y);

            updateSelectionBox({left, top, width, height});
        }

        function finishSelection(event) {
            if (!state.isDraggingSelection || !state.dragStart) {
                return;
            }

            const rect = submissionPreviewImage.getBoundingClientRect();
            const currentX = Math.max(0, Math.min(rect.width, event.clientX - rect.left));
            const currentY = Math.max(0, Math.min(rect.height, event.clientY - rect.top));
            const left = Math.min(state.dragStart.x, currentX);
            const top = Math.min(state.dragStart.y, currentY);
            const width = Math.abs(currentX - state.dragStart.x);
            const height = Math.abs(currentY - state.dragStart.y);

            state.isDraggingSelection = false;
            state.dragStart = null;

            if (width < 18 || height < 18) {
                state.selectionBox = null;
                selectionBox.hidden = true;
                selectionMeta.textContent = '选区太小了，请重新拖拽选择一个更大的文字区域。';
                return;
            }

            updateSelectionBox({left, top, width, height});
            setSelectionFromDisplay({left, top, width, height});
        }

        function loadSubmissionImage(file) {
            const reader = new FileReader();
            reader.onload = () => {
                submissionPreviewImage.onload = () => {
                    state.submissionNaturalWidth = submissionPreviewImage.naturalWidth;
                    state.submissionNaturalHeight = submissionPreviewImage.naturalHeight;
                    state.selectionBox = null;
                    selectionBox.hidden = true;
                    selectorStage.classList.remove('empty');
                    submissionPreviewImage.hidden = false;
                    selectionMeta.textContent = `图片已载入：${state.submissionNaturalWidth} × ${state.submissionNaturalHeight}，请在图片上拖拽选择文字区域。`;
                };
                submissionPreviewImage.src = reader.result;
            };
            reader.readAsDataURL(file);
        }

        async function submitTemplate() {
            if (!state.submissionImageFile) {
                setFeedback(submissionFeedback, '请先上传模版图片。', 'error');
                return;
            }
            if (!state.selectionBox) {
                setFeedback(submissionFeedback, '请先在图片上拖拽选择文字区域。', 'error');
                return;
            }

            const name = submissionNameInput.value.trim();
            const description = submissionDescriptionInput.value.trim();
            const nickname = submissionNicknameInput.value.trim();

            if (!name || name.length > 10) {
                setFeedback(submissionFeedback, '模版名字需要填写，且最多 10 个字。', 'error');
                return;
            }
            if (description.length > 30) {
                setFeedback(submissionFeedback, '模版介绍最多 30 个字。', 'error');
                return;
            }
            if (!nickname || nickname.length > 16) {
                setFeedback(submissionFeedback, '上传者昵称需要填写，且长度为 1 到 16 位。', 'error');
                return;
            }

            submitTemplateButton.disabled = true;
            submitTemplateButton.textContent = '正在提交...';
            setFeedback(submissionFeedback, '正在提交模版，请稍候...');

            try {
                const formData = new FormData();
                formData.append('image', state.submissionImageFile);
                formData.append('name', name);
                formData.append('description', description);
                formData.append('uploader_nickname', nickname);
                formData.append('text_box_json', JSON.stringify(state.selectionBox));

                const response = await fetch(joinUrl(API_BASE, '/api/submissions'), {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.detail || '投稿失败');
                }

                setFeedback(submissionFeedback, '投稿成功，已经提交到后台审核。', 'success');
                setTimeout(() => {
                    closeSubmissionOverlay();
                    resetSubmissionForm();
                }, 900);
            } catch (error) {
                console.error(error);
                setFeedback(submissionFeedback, `投稿失败：${error.message}`, 'error');
            } finally {
                submitTemplateButton.disabled = false;
                submitTemplateButton.textContent = '提交新模版';
            }
        }

        async function copyTemplateApi() {
            const content = templateApiCode.textContent.trim();
            if (!content) {
                return;
            }

            try {
                await navigator.clipboard.writeText(content);
                copyApiButton.textContent = '已复制';
                setTimeout(() => {
                    copyApiButton.textContent = '复制调用示例';
                }, 1200);
            } catch (error) {
                console.error(error);
                setFeedback(feedbackText, '复制失败，请手动复制下方内容。', 'error');
            }
        }

        generateButton.addEventListener('click', generateMeme);
        cancelButton.addEventListener('click', closeEditor);
        closeDialogButton.addEventListener('click', closeEditor);
        copyApiButton.addEventListener('click', copyTemplateApi);

        openSubmissionButton.addEventListener('click', openSubmissionOverlay);
        closeSubmissionButton.addEventListener('click', closeSubmissionOverlay);
        cancelSubmissionButton.addEventListener('click', closeSubmissionOverlay);
        submitTemplateButton.addEventListener('click', submitTemplate);
        submissionImageInput.addEventListener('change', (event) => {
            const file = event.target.files?.[0];
            if (!file) {
                return;
            }
            state.submissionImageFile = file;
            loadSubmissionImage(file);
        });

        selectorCanvas.addEventListener('mousedown', startSelection);
        window.addEventListener('mousemove', moveSelection);
        window.addEventListener('mouseup', finishSelection);

        editorOverlay.addEventListener('click', (event) => {
            if (event.target === editorOverlay) {
                closeEditor();
            }
        });

        submissionOverlay.addEventListener('click', (event) => {
            if (event.target === submissionOverlay) {
                closeSubmissionOverlay();
            }
        });

        window.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                if (editorOverlay.classList.contains('open')) {
                    closeEditor();
                }
                if (submissionOverlay.classList.contains('open')) {
                    closeSubmissionOverlay();
                }
            }
        });

        resetSubmissionForm();
        loadData();
    </script>
</body>
</html>
