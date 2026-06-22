<?php
$apiBaseUrl = getenv('MEME_API_BASE') ?: '/api-proxy';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>菲比啾比-模版审核后台</title>
    <style>
        :root {
            color-scheme: light;
            --bg: #f4ecdf;
            --panel: rgba(255, 250, 243, 0.94);
            --ink: #201612;
            --muted: #7f6b61;
            --accent: #d95d39;
            --line: rgba(77, 52, 42, 0.12);
            --shadow: 0 24px 70px rgba(70, 44, 27, 0.14);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Microsoft YaHei", "PingFang SC", sans-serif;
            color: var(--ink);
            background: linear-gradient(180deg, #f7efe2 0%, var(--bg) 100%);
        }

        .shell {
            max-width: 1200px;
            margin: 0 auto;
            padding: 28px 20px 48px;
        }

        .hero,
        .panel {
            border: 1px solid var(--line);
            border-radius: 28px;
            background: var(--panel);
            box-shadow: var(--shadow);
        }

        .hero {
            padding: 28px;
        }

        h1,
        h2,
        h3,
        p {
            margin: 0;
        }

        .hero p,
        .muted {
            color: var(--muted);
            line-height: 1.7;
        }

        .hero-actions,
        .action-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .hero-actions {
            margin-top: 18px;
        }

        .panel {
            padding: 24px;
            margin-top: 22px;
        }

        .panel-head {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            margin-bottom: 16px;
        }

        .grid {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        }

        .card {
            border: 1px solid var(--line);
            border-radius: 22px;
            overflow: hidden;
            background: rgba(255, 255, 255, 0.7);
        }

        .card img {
            width: 100%;
            aspect-ratio: 4 / 5;
            object-fit: cover;
            display: block;
        }

        .card-body {
            padding: 16px;
        }

        .meta {
            margin-top: 10px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(32, 22, 18, 0.06);
            color: var(--muted);
            font-size: 12px;
        }

        label {
            display: grid;
            gap: 8px;
            font-size: 14px;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid rgba(77, 52, 42, 0.14);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.9);
            outline: none;
        }

        input:focus {
            border-color: rgba(217, 93, 57, 0.5);
            box-shadow: 0 0 0 4px rgba(217, 93, 57, 0.14);
        }

        .login-grid {
            display: grid;
            gap: 14px;
            max-width: 420px;
        }

        .primary-btn,
        .secondary-btn,
        .danger-btn,
        .link-btn {
            border: 0;
            cursor: pointer;
            font: inherit;
            padding: 13px 18px;
            border-radius: 16px;
            transition: transform 160ms ease, opacity 160ms ease;
            text-decoration: none;
        }

        .primary-btn {
            background: linear-gradient(135deg, #dd6b44, #bc3f1d);
            color: #fffdf9;
        }

        .secondary-btn,
        .link-btn {
            background: rgba(32, 22, 18, 0.06);
            color: var(--ink);
        }

        .danger-btn {
            background: linear-gradient(135deg, #c64545, #991b1b);
            color: #fffdf9;
        }

        .primary-btn:hover,
        .secondary-btn:hover,
        .danger-btn:hover,
        .link-btn:hover {
            transform: translateY(-1px);
        }

        .feedback {
            min-height: 24px;
            margin-top: 10px;
            color: var(--muted);
        }

        .feedback.error {
            color: #b42318;
        }

        .feedback.success {
            color: #166534;
        }

        .hidden {
            display: none;
        }

        @media (max-width: 720px) {
            .panel-head,
            .hero-actions,
            .action-row {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <div class="shell">
        <section class="hero">
            <span class="badge">菲比啾比后台</span>
            <h1 style="margin-top: 12px;">模版审核与管理后台</h1>
            <p style="margin-top: 10px;">这里可以查看用户投稿的新模版、通过或拒绝审核，也可以管理已经上架的模版。</p>
            <div class="hero-actions">
                <a class="link-btn" href="./index.php">返回前台首页</a>
                <button class="secondary-btn hidden" type="button" id="logoutButton">退出登录</button>
            </div>
        </section>

        <section class="panel" id="loginPanel">
            <div class="panel-head">
                <div>
                    <h2>后台登录</h2>
                    <p class="muted">请输入管理员账号和密码后进入审核后台。</p>
                </div>
            </div>
            <div class="login-grid">
                <label for="usernameInput">
                    账号
                    <input id="usernameInput" type="text" value="xiaohai123" autocomplete="username">
                </label>
                <label for="passwordInput">
                    密码
                    <input id="passwordInput" type="password" value="888888" autocomplete="current-password">
                </label>
                <div class="action-row">
                    <button class="primary-btn" id="loginButton" type="button">登录后台</button>
                </div>
                <div class="feedback" id="loginFeedback"></div>
            </div>
        </section>

        <section class="panel hidden" id="dashboardPanel">
            <div class="panel-head">
                <div>
                    <h2>待审核投稿</h2>
                    <p class="muted">通过后会直接上架到前台模版列表，拒绝后会移除当前投稿。</p>
                </div>
                <button class="secondary-btn" type="button" id="refreshButton">刷新列表</button>
            </div>
            <div class="grid" id="pendingGrid"></div>
        </section>

        <section class="panel hidden" id="templatePanel">
            <div class="panel-head">
                <div>
                    <h2>已上架模版</h2>
                    <p class="muted">这里可以删除已经上架的模版。新增上架通过“待审核投稿”中的通过操作完成。</p>
                </div>
            </div>
            <div class="grid" id="templateGrid"></div>
        </section>
    </div>

    <script>
        const API_BASE = <?php echo json_encode($apiBaseUrl, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
        const ADMIN_TOKEN_KEY = 'phoebe_admin_token';

        const loginPanel = document.getElementById('loginPanel');
        const dashboardPanel = document.getElementById('dashboardPanel');
        const templatePanel = document.getElementById('templatePanel');
        const usernameInput = document.getElementById('usernameInput');
        const passwordInput = document.getElementById('passwordInput');
        const loginButton = document.getElementById('loginButton');
        const loginFeedback = document.getElementById('loginFeedback');
        const pendingGrid = document.getElementById('pendingGrid');
        const templateGrid = document.getElementById('templateGrid');
        const refreshButton = document.getElementById('refreshButton');
        const logoutButton = document.getElementById('logoutButton');

        function joinUrl(base, path) {
            return `${base.replace(/\/$/, '')}/${String(path).replace(/^\//, '')}`;
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

        function getAdminToken() {
            return window.localStorage.getItem(ADMIN_TOKEN_KEY) || '';
        }

        function setLoggedIn(isLoggedIn) {
            loginPanel.classList.toggle('hidden', isLoggedIn);
            dashboardPanel.classList.toggle('hidden', !isLoggedIn);
            templatePanel.classList.toggle('hidden', !isLoggedIn);
            logoutButton.classList.toggle('hidden', !isLoggedIn);
        }

        async function apiRequest(path, options = {}) {
            const token = getAdminToken();
            const headers = new Headers(options.headers || {});
            if (token) {
                headers.set('Authorization', `Bearer ${token}`);
            }
            const response = await fetch(joinUrl(API_BASE, path), {...options, headers});
            const contentType = response.headers.get('content-type') || '';
            const data = contentType.includes('application/json') ? await response.json() : await response.text();
            if (!response.ok) {
                throw new Error(data.detail || data || '请求失败');
            }
            return data;
        }

        function renderPending(items) {
            if (!items.length) {
                pendingGrid.innerHTML = '<div class="muted">当前没有新的投稿，稍后再来看看。</div>';
                return;
            }

            pendingGrid.innerHTML = items.map((item) => `
                <article class="card">
                    <img src="${joinUrl(API_BASE, item.preview_url)}" alt="${escapeHtml(item.name)}">
                    <div class="card-body">
                        <h3>${escapeHtml(item.name)}</h3>
                        <div class="meta">
                            模版介绍：${escapeHtml(item.description || '暂无介绍')}<br>
                            上传者：${escapeHtml(item.uploader_nickname)}<br>
                            文字区域：${item.text_box.width} × ${item.text_box.height}<br>
                            提交时间：${escapeHtml(item.submitted_at)}
                        </div>
                        <div class="action-row" style="margin-top: 14px;">
                            <button class="primary-btn" type="button" data-approve="${item.submission_id}">通过上架</button>
                            <button class="danger-btn" type="button" data-reject="${item.submission_id}">拒绝投稿</button>
                        </div>
                    </div>
                </article>
            `).join('');
        }

        function renderTemplates(items) {
            if (!items.length) {
                templateGrid.innerHTML = '<div class="muted">当前还没有已上架模版。</div>';
                return;
            }

            templateGrid.innerHTML = items.map((item) => `
                <article class="card">
                    <img src="${joinUrl(API_BASE, item.preview_url)}" alt="${escapeHtml(item.name)}">
                    <div class="card-body">
                        <h3>${escapeHtml(item.name)}</h3>
                        <div class="meta">
                            模版编号：${escapeHtml(item.template_id)}<br>
                            模版介绍：${escapeHtml(item.description || '暂无介绍')}
                        </div>
                        <div class="action-row" style="margin-top: 14px;">
                            <button class="danger-btn" type="button" data-delete="${item.template_id}">删除模版</button>
                        </div>
                    </div>
                </article>
            `).join('');
        }

        async function loadDashboard() {
            try {
                const [pendingItems, templateItems] = await Promise.all([
                    apiRequest('/api/admin/pending-submissions'),
                    apiRequest('/api/admin/templates')
                ]);
                renderPending(pendingItems);
                renderTemplates(templateItems);
            } catch (error) {
                console.error(error);
                if (String(error.message).includes('登录')) {
                    window.localStorage.removeItem(ADMIN_TOKEN_KEY);
                    setLoggedIn(false);
                    setFeedback(loginFeedback, '登录状态已失效，请重新登录。', 'error');
                    return;
                }
                pendingGrid.innerHTML = `<div class="feedback error">加载失败：${escapeHtml(error.message)}</div>`;
                templateGrid.innerHTML = `<div class="feedback error">加载失败：${escapeHtml(error.message)}</div>`;
            }
        }

        async function login() {
            setFeedback(loginFeedback, '正在登录...');
            loginButton.disabled = true;

            try {
                const data = await apiRequest('/api/admin/login', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        username: usernameInput.value.trim(),
                        password: passwordInput.value
                    })
                });
                window.localStorage.setItem(ADMIN_TOKEN_KEY, data.token);
                setLoggedIn(true);
                setFeedback(loginFeedback, '登录成功。', 'success');
                await loadDashboard();
            } catch (error) {
                console.error(error);
                setFeedback(loginFeedback, `登录失败：${error.message}`, 'error');
            } finally {
                loginButton.disabled = false;
            }
        }

        async function approveSubmission(submissionId) {
            if (!window.confirm('确认通过这个投稿并上架吗？')) {
                return;
            }
            await apiRequest(`/api/admin/submissions/${submissionId}/approve`, {method: 'POST'});
            await loadDashboard();
        }

        async function rejectSubmission(submissionId) {
            if (!window.confirm('确认拒绝这个投稿吗？')) {
                return;
            }
            await apiRequest(`/api/admin/submissions/${submissionId}`, {method: 'DELETE'});
            await loadDashboard();
        }

        async function deleteTemplate(templateId) {
            if (!window.confirm('确认删除这个已上架模版吗？删除后前台将不再显示。')) {
                return;
            }
            await apiRequest(`/api/admin/templates/${templateId}`, {method: 'DELETE'});
            await loadDashboard();
        }

        pendingGrid.addEventListener('click', async (event) => {
            const approveId = event.target.getAttribute('data-approve');
            const rejectId = event.target.getAttribute('data-reject');

            try {
                if (approveId) {
                    await approveSubmission(approveId);
                }
                if (rejectId) {
                    await rejectSubmission(rejectId);
                }
            } catch (error) {
                console.error(error);
                window.alert(error.message);
            }
        });

        templateGrid.addEventListener('click', async (event) => {
            const deleteId = event.target.getAttribute('data-delete');
            if (!deleteId) {
                return;
            }
            try {
                await deleteTemplate(deleteId);
            } catch (error) {
                console.error(error);
                window.alert(error.message);
            }
        });

        loginButton.addEventListener('click', login);
        refreshButton.addEventListener('click', loadDashboard);
        logoutButton.addEventListener('click', () => {
            window.localStorage.removeItem(ADMIN_TOKEN_KEY);
            setLoggedIn(false);
        });

        if (getAdminToken()) {
            setLoggedIn(true);
            loadDashboard();
        } else {
            setLoggedIn(false);
        }
    </script>
</body>
</html>
