<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>プロフィール作成 - AITECH</title>
    <style>
        /* 既存スタイルを踏襲（省略） */
    </style>
</head>
<body>
    <main class="main-content">
        <!-- Sidebar preview -->
        <aside class="sidebar">
            <div class="panel profile-card">
                <div class="panel-title">プレビュー</div>
                <div class="profile-head">
                    <div class="big-avatar" id="preview-avatar">{{ mb_substr($user->email ?? 'U', 0, 1) }}</div>
                    <div style="min-width:0;">
                        <div class="name" id="preview-name">未入力</div>
                        <div class="headline" id="preview-headline">未入力</div>
                    </div>
                </div>
                <div class="skills" id="preview-skills" aria-label="スキル">
                </div>
                <div class="divider"></div>
                <div class="kv" aria-label="条件">
                    <div class="k">希望単価</div>
                    <div class="v" id="preview-rate">未設定</div>
                    <div class="k">稼働</div>
                    <div class="v" id="preview-hours">未設定</div>
                    <div class="k">日</div>
                    <div class="v" id="preview-days">未設定</div>
                </div>
                <p class="help" style="margin-top:1rem;">プロフィールが充実しているほどスカウトが届きやすくなります。</p>
            </div>
        </aside>

        <!-- Form -->
        <div class="content-area">
            <h1 class="page-title">プロフィール作成</h1>
            @include('partials.error-panel')

            <div class="panel">
                <div class="panel-title">基本情報</div>
                <form class="form" action="{{ route('corporate.profile.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <!-- フォーム内容は法人側でも同様 -->
                    <div class="actions">
                        <a class="btn btn-secondary" href="{{ route('corporate.jobs.index') }}" role="button">キャンセル</a>
                        <button class="btn btn-primary" type="submit">登録</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script>
        // 各種スクリプト（省略）
    </script>
</body>
</html>

