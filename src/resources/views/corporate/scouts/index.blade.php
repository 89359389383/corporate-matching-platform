<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>スカウト一覧 - AITECH</title>
    @include('partials.corporate-header-style')
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    @include('partials.corporate-header')

    <main class="main-content max-w-6xl mx-auto px-4 md:px-6 lg:px-8 py-6 md:py-10">
        <div class="content-area">
            <h1 class="page-title">スカウト</h1>
            <p class="page-subtitle">企業からのスカウトを確認できます。スレッドを開くとチャット画面に遷移します。</p>

            @if($threads->count() > 0)
                <div class="list grid grid-cols-1 gap-5 lg:gap-6">
                    @foreach($threads as $thread)
                        @php
                            $threadUrl = route('corporate.threads.show', ['thread' => $thread->id]);
                        @endphp
                        <article class="card rounded-2xl bg-white border border-slate-200 shadow-sm p-5 md:p-7 relative overflow-hidden" role="link" tabindex="0" onclick="window.location.href='{{ $threadUrl }}'" style="cursor: pointer;">
                            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:1rem;margin-bottom:0.75rem;">
                                <div>
                                    <h2 style="font-size:1.25rem;font-weight:900;color:#24292e;">{{ $thread->company->name ?? '企業名不明' }}</h2>
                                    <div style="color:#6a737d;font-weight:700;font-size:0.9rem;">スカウト</div>
                                </div>
                                <div>
                                    @if($thread->is_unread)
                                        <span style="display:inline-flex;padding:0.2rem 0.6rem;border-radius:999px;font-size:0.75rem;font-weight:700;background:#d73a49;color:#fff;">未読</span>
                                    @else
                                        <span style="display:inline-flex;padding:0.2rem 0.6rem;border-radius:999px;font-size:0.75rem;font-weight:700;background:#fafbfc;border:1px solid #e1e4e8;color:#6a737d;">既読</span>
                                    @endif
                                </div>
                            </div>
                            @php
                                $latestMessage = $thread->messages->first();
                                $scoutMessage = $thread->scout ? $thread->scout->message : null;
                                $displayMessage = $latestMessage ? $latestMessage->body : ($scoutMessage ?? 'メッセージがありません');
                            @endphp
                            <p style="color:#586069;line-height:1.6;margin-bottom:1rem;">{{ $displayMessage }}</p>
                            <div style="display:flex;flex-wrap:wrap;justify-content:flex-end;gap:0.75rem;border-top:1px solid #e1e4e8;padding-top:1rem;margin-top:1rem;">
                                <a class="btn btn-primary" href="{{ route('corporate.threads.show', ['thread' => $thread->id]) }}">チャットへ</a>
                            </div>
                        </article>
                    @endforeach
                </div>

                @if($threads->hasPages())
                    <div style="margin-top:2rem;display:flex;justify-content:center;">
                        {{ $threads->links() }}
                    </div>
                @endif
            @else
                <div class="card text-center" style="padding:3rem;">
                    <p style="font-size:1rem;font-weight:700;color:#6a737d;">スカウトはまだありません</p>
                </div>
            @endif
        </div>
    </main>
</body>
</html>
