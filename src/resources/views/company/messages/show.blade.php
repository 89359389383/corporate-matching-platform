<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>応募案件チャット（企業）- AITECH</title>
    <style>
        :root { --header-height: 104px; --header-height-mobile: 91px; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { font-size: 130%; }
        body {
            font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #fafbfc;
            color: #24292e;
            line-height: 1.5;
        }

        /* Header */
        .header {
            background-color: #ffffff;
            border-bottom: 1px solid #e1e4e8;
            padding: 0 3rem;
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .header-content {
            max-width: 1600px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: var(--header-height);
            position: relative;
        }
        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            justify-content: center;
            flex-wrap: nowrap;
        }
        .nav-link {
            text-decoration: none;
            color: #586069;
            font-weight: 500;
            font-size: 1.05rem;
            padding: 0.75rem 1.25rem;
            border-radius: 8px;
            transition: all 0.15s ease;
            position: relative;
            letter-spacing: -0.01em;
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }
        .nav-link.has-badge { padding-right: 3rem; }
        .nav-link:hover { background-color: #f6f8fa; color: #24292e; }
        .nav-link.active {
            background-color: #0366d6;
            color: white;
            box-shadow: 0 2px 8px rgba(3, 102, 214, 0.3);
        }
        .badge {
            background-color: #d73a49;
            color: white;
            border-radius: 999px;
            padding: 0.15rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 700;
            min-width: 18px;
            height: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 1px 3px rgba(209, 58, 73, 0.3);
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
        }
        .user-menu { display: flex; align-items: center; position: absolute; right: 0; top: 50%; transform: translateY(-50%); }
        .user-avatar {
            width: 36px; height: 36px; border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; cursor: pointer; transition: all 0.15s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: none; padding: 0; appearance: none;
        }
        .user-avatar:hover { transform: scale(1.08); box-shadow: 0 4px 16px rgba(0,0,0,0.2); }
        .dropdown { position: relative; }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: white;
            min-width: 240px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12), 0 2px 8px rgba(0,0,0,0.08);
            border-radius: 12px;
            z-index: 1000;
            border: 1px solid #e1e4e8;
            margin-top: 0.5rem;
        }
        .dropdown.is-open .dropdown-content { display: block; }
        .dropdown-item {
            display: block;
            padding: 0.875rem 1.25rem;
            text-decoration: none;
            color: #586069;
            transition: all 0.15s ease;
            border-radius: 6px;
            margin: 0.25rem;
            white-space: nowrap;
        }
        .dropdown-item:hover { background-color: #f6f8fa; color: #24292e; }
        .dropdown-divider { height: 1px; background-color: #e1e4e8; margin: 0.5rem 0; }

        /* Page */
        .main-content { max-width: 1600px; margin: 0 auto; padding: 3rem; }
        .panel {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 1px 2px rgba(0,0,0,0.06);
            border: 1px solid #e1e4e8;
            overflow: hidden;
        }
        .chat-head {
            padding: 1.5rem 1.75rem;
            border-bottom: 1px solid #e1e4e8;
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            align-items: flex-start;
            background: #fff;
        }
        .chat-title { font-size: 1.35rem; font-weight: 900; line-height: 1.2; margin-bottom: 0.25rem; }
        .chat-sub { color: #586069; font-weight: 700; }
        .chat-tools { display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; justify-content: flex-end; }
        .select {
            padding: 0.7rem 0.85rem;
            border: 2px solid #e1e4e8;
            border-radius: 10px;
            background: #fafbfc;
            font-weight: 900;
            color: #24292e;
            cursor: pointer;
        }
        .select:focus { outline: none; border-color: #0366d6; box-shadow: 0 0 0 3px rgba(3,102,214,0.1); background: #fff; }

        .chat-body {
            padding: 1.25rem 1.75rem;
            background: #fafbfc;
            height: 520px;
            overflow: auto;
        }
        .msg-row { display: flex; margin: 0.75rem 0; }
        .msg-row.me { justify-content: flex-end; }
        .bubble {
            max-width: 72%;
            padding: 0.9rem 1rem;
            border-radius: 14px;
            border: 1px solid #e1e4e8;
            background: #fff;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .msg-row.me .bubble { background: #f1f8ff; border-color: #c8e1ff; }
        .bubble .meta { font-size: 0.8rem; color: #6a737d; font-weight: 800; margin-top: 0.5rem; }
        .bubble .del {
            margin-left: 0.5rem;
            font-size: 0.8rem;
            color: #b31d28;
            font-weight: 900;
            text-decoration: none;
        }
        .bubble .del:hover { text-decoration: underline; }
        .system {
            margin: 1rem 0;
            text-align: center;
            color: #6a737d;
            font-weight: 800;
            font-size: 0.9rem;
        }

        .chat-foot {
            border-top: 1px solid #e1e4e8;
            padding: 1rem 1.25rem;
            display: flex;
            gap: 0.75rem;
            align-items: center;
            background: #fff;
        }
        .input {
            flex: 1;
            padding: 0.95rem 1rem;
            border: 2px solid #e1e4e8;
            border-radius: 12px;
            background: #fafbfc;
            font-size: 0.95rem;
            transition: all 0.15s ease;
        }
        .input:focus { outline: none; border-color: #0366d6; box-shadow: 0 0 0 3px rgba(3,102,214,0.1); background: #fff; }
        .btn {
            padding: 0.9rem 1.25rem;
            border-radius: 12px;
            font-weight: 900;
            border: none;
            cursor: pointer;
            transition: all 0.15s ease;
            white-space: nowrap;
        }
        .btn-primary { background: #0366d6; color: #fff; }
        .btn-primary:hover { background: #0256cc; transform: translateY(-1px); box-shadow: 0 4px 16px rgba(3,102,214,0.25); }
        .btn-secondary { background: #586069; color: #fff; }
        .btn-secondary:hover { background: #4c5561; transform: translateY(-1px); }
        .hint { padding: 0.75rem 1.25rem; color: #6a737d; font-weight: 800; font-size: 0.9rem; background: #fff; border-top: 1px solid #e1e4e8; }

        @media (max-width: 920px) {
            .header-content { height: var(--header-height-mobile); }
            .nav-links { position: static; left: auto; transform: none; justify-content: flex-start; }
            .user-menu { position: static; transform: none; margin-left: auto; }
            .main-content { padding: 1.5rem; }
            .chat-body { height: 420px; }
            .bubble { max-width: 88%; }
            .chat-foot { flex-direction: column; align-items: stretch; }
            .btn { width: 100%; }
        }
        @media (max-width: 1200px) {
            .nav-links { gap: 1rem; }
            .nav-link { font-size: 0.95rem; padding: 0.6rem 0.9rem; }
            .nav-link.has-badge { padding-right: 2.6rem; }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <nav class="nav-links">
                <a href="#" class="nav-link">フリーランス一覧</a>
                <a href="#" class="nav-link">案件一覧</a>
                <a href="#" class="nav-link has-badge active">応募された案件 <span class="badge">3</span></a>
                <a href="#" class="nav-link has-badge">スカウト <span class="badge">1</span></a>
            </nav>
            <div class="user-menu">
                <div class="dropdown" id="userDropdown">
                    <button class="user-avatar" id="userDropdownToggle" type="button" aria-haspopup="menu" aria-expanded="false" aria-controls="userDropdownMenu">企</button>
                    <div class="dropdown-content" id="userDropdownMenu" role="menu" aria-label="ユーザーメニュー">
                        <a href="#" class="dropdown-item" role="menuitem">プロフィール設定</a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item" role="menuitem">ログアウト</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="panel" aria-label="応募チャット">
            <div class="chat-head">
                <div style="min-width:0;">
                    <div class="chat-title">ECサイト機能拡張プロジェクト</div>
                    <div class="chat-sub">応募者：山田 太郎 / 企業：株式会社AITECH</div>
                </div>
                <div class="chat-tools">
                    <label style="font-weight:900; color:#586069;">応募ステータス</label>
                    <select class="select" id="statusSelect" aria-label="応募ステータス">
                        <option value="new" selected>未対応</option>
                        <option value="progress">対応中</option>
                        <option value="closed">クローズ（終了）</option>
                    </select>
                    <a class="btn btn-secondary" href="#" style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center;">一覧へ</a>
                </div>
            </div>

            <div class="hint" id="readHint">
                この画面を開いた時点で「スレッド単位の未読」は解除された想定です（WebSocket不要の運用）。
            </div>

            <div class="chat-body" id="chatBody">
                <div class="system">応募メッセージ（最初の1通）</div>

                <div class="msg-row">
                    <div class="bubble">
                        はじめまして。Laravel/VueでのEC改善経験があります。<br>
                        週20〜30hで参画可能です。要件整理から実装・運用改善まで対応します。<br>
                        よろしくお願いします。
                        <div class="meta">山田 太郎 ・ 2025/12/20 10:14</div>
                    </div>
                </div>

                <div class="msg-row me">
                    <div class="bubble">
                        ご応募ありがとうございます！まずは現状の課題感を共有します。<br>
                        管理画面の速度改善と、商品登録フローの短縮が主な目的です。
                        <div class="meta">あなた（企業）・ 2025/12/20 10:30 <a class="del" href="#" onclick="return false;">削除</a></div>
                    </div>
                </div>

                <div class="msg-row">
                    <div class="bubble">
                        承知しました。現状のボトルネック確認（SQL/キャッシュ/画面設計）から入り、<br>
                        影響範囲を見ながら段階的に改善する方針が良いと思います。
                        <div class="meta">山田 太郎 ・ 2025/12/20 10:38</div>
                    </div>
                </div>

                <div class="msg-row me">
                    <div class="bubble">
                        ありがとうございます。まずは今週、画面とDBの現状を一緒に確認しましょう。<br>
                        進め方の提案もいただけると助かります。
                        <div class="meta">あなた（企業）・ 2025/12/20 10:45 <a class="del" href="#" onclick="return false;">削除</a></div>
                    </div>
                </div>
            </div>

            <div class="chat-foot">
                <input class="input" id="messageInput" type="text" placeholder="メッセージを入力（クローズ時は送信不可）" aria-label="メッセージ入力">
                <button class="btn btn-primary" id="sendBtn" type="button">送信</button>
            </div>
        </div>
    </main>

    <script>
        (function () {
            const dropdown = document.getElementById('userDropdown');
            const toggle = document.getElementById('userDropdownToggle');
            const menu = document.getElementById('userDropdownMenu');
            if (!dropdown || !toggle || !menu) return;
            const open = () => { dropdown.classList.add('is-open'); toggle.setAttribute('aria-expanded', 'true'); };
            const close = () => { dropdown.classList.remove('is-open'); toggle.setAttribute('aria-expanded', 'false'); };
            const isOpen = () => dropdown.classList.contains('is-open');
            toggle.addEventListener('click', (e) => { e.stopPropagation(); isOpen() ? close() : open(); });
            document.addEventListener('click', (e) => { if (!dropdown.contains(e.target)) close(); });
            document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });
        })();
    </script>
    <script>
        (function () {
            const status = document.getElementById('statusSelect');
            const input = document.getElementById('messageInput');
            const btn = document.getElementById('sendBtn');
            if (!status || !input || !btn) return;

            const apply = () => {
                const closed = status.value === 'closed';
                input.disabled = closed;
                btn.disabled = closed;
                btn.style.opacity = closed ? '0.5' : '1';
                btn.style.pointerEvents = closed ? 'none' : 'auto';
            };
            status.addEventListener('change', apply);
            apply();
        })();
    </script>
</body>
</html>
