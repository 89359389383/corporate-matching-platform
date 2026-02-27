<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スカウト詳細 - AITECH</title>
    @include('partials.corporate-header-style')
    @include('partials.corporate-chat-style')
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    @include('partials.corporate-header')

    <main class="main-content max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-6 md:py-10">
        <section class="panel chat-pane" aria-label="チャット">
            <div class="chat-header">
                <div class="chat-title">
                    <strong>{{ $thread->company->name ?? '企業名不明' }}とのチャット</strong>
                </div>
                <a class="btn" href="{{ route('corporate.threads.contracts.index', ['thread' => $thread->id]) }}">契約</a>
                <a class="btn" href="{{ route('corporate.scouts.index') }}">一覧へ</a>
            </div>

            <div class="messages max-h-[70vh] md:max-h-[64vh] lg:max-h-[66vh]" id="messages" aria-label="メッセージ一覧">
                @forelse($messages as $message)
                    @php
                        $isMe = $message->sender_type === 'corporate';
                        $isFirst = $loop->first;
                        $sentAt = $message->sent_at ? $message->sent_at->format('m/d H:i') : '';
                        $isLatest = $loop->last;
                        $canDelete = $isMe && $message->sender_type === 'corporate';
                    @endphp
                    <div class="bubble-row {{ $isMe ? 'me' : '' }} {{ $isFirst ? 'is-first' : '' }}">
                        <div class="bubble {{ $isMe ? 'me' : '' }}">
                            <p>{{ $message->body }}</p>
                            <small>
                                {{ $sentAt }}
                                @if($canDelete && $isLatest)
                                    <span style="margin-left:0.75rem;">
                                        <form action="{{ route('corporate.messages.destroy', ['message' => $message->id]) }}" method="POST" style="display:inline;" class="delete-form" data-message-id="{{ $message->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="delete-trigger" style="background:none;border:none;color:#d73a49;font-weight:900;cursor:pointer;">削除</button>
                                        </form>
                                    </span>
                                @endif
                            </small>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 2rem; color: #6a737d;">
                        <p>メッセージがありません。</p>
                    </div>
                @endforelse
            </div>

            <form class="composer" action="{{ route('corporate.threads.messages.store', ['thread' => $thread->id]) }}" method="post">
                @csrf
                <textarea class="input @error('content') is-invalid @enderror" name="content" placeholder="メッセージを入力…" aria-label="メッセージを入力"></textarea>
                @error('content')
                    <span class="error-message">{{ $message }}</span>
                @enderror
                <button class="send w-full md:w-auto" type="submit">送信</button>
            </form>
        </section>
    </main>

    <script>
        (function () {
            const el = document.getElementById('messages');
            if (el) el.scrollTop = el.scrollHeight;
        })();
    </script>
    <!-- 削除確認モーダル -->
    <div id="confirmDeleteModal" role="dialog" aria-hidden="true" aria-labelledby="confirmDeleteTitle" style="display:block;">
        <div style="position:fixed;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none;z-index:1000;">
            <div id="confirmDeleteDialog" style="pointer-events:auto;width:min(540px,92%);background:#fff;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,0.15);padding:1.25rem;display:none;" aria-modal="true">
                <h2 id="confirmDeleteTitle" style="margin:0 0 0.5rem;font-size:1.05rem;font-weight:800;color:#0f172a;">本当に削除しますか？</h2>
                <p style="margin:0 0 1rem;color:#64748b;">この操作は取り消せません。よろしければ「削除する」をクリックしてください。</p>
                <div style="display:flex;gap:0.75rem;">
                    <button id="cancelDeleteBtn" style="flex:1;padding:0.6rem 0.9rem;border-radius:8px;border:1px solid #e6eaf2;background:#fafbfc;cursor:pointer;">キャンセル</button>
                    <button id="confirmDeleteBtn" style="flex:1;padding:0.6rem 0.9rem;border-radius:8px;border:none;background:#d73a49;color:#fff;cursor:pointer;">削除する</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function () {
            let pendingForm = null;
            const modal = document.getElementById('confirmDeleteModal');
            const dialog = document.getElementById('confirmDeleteDialog');
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            const cancelBtn = document.getElementById('cancelDeleteBtn');
            function openModal(form) {
                pendingForm = form;
                if (modal && dialog) {
                    modal.setAttribute('aria-hidden','false');
                    dialog.style.display = 'block';
                    modal.classList.add('is-open');
                    confirmBtn && confirmBtn.focus();
                }
            }
            function closeModal() {
                pendingForm = null;
                if (modal && dialog) {
                    dialog.style.display = 'none';
                    modal.setAttribute('aria-hidden','true');
                    modal.classList.remove('is-open');
                }
            }
            document.addEventListener('click', (e) => {
                const trigger = e.target.closest && e.target.closest('.delete-trigger');
                if (trigger) { e.preventDefault(); const form = trigger.closest('form'); if (form) openModal(form); }
            });
            modal && modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });
            cancelBtn && cancelBtn.addEventListener('click', (e) => { e.preventDefault(); closeModal(); });
            confirmBtn && confirmBtn.addEventListener('click', (e) => { e.preventDefault(); if (!pendingForm) return closeModal(); pendingForm.submit(); });
            document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && modal && modal.classList.contains('is-open')) closeModal(); });
        })();
    </script>
</body>
</html>
