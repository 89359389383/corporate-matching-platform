<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>応募一覧 - AITECH</title>
    @include('partials.corporate-header-style')
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    @include('partials.corporate-header')

    <main class="main-content max-w-7xl mx-auto px-4 md:px-6 lg:px-8 py-6 md:py-10">
        <div class="content-area">
            <h1 class="page-title">応募一覧</h1>
            <p class="page-subtitle">状態: {{ $status }}</p>

            @forelse($applications as $app)
                <div class="card mb-5">
                    <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;">
                        <div>
                            <div style="font-weight:900;font-size:1.1rem;">{{ $app->job->title ?? '案件' }}</div>
                            <div style="color:#6a737d;font-weight:700;margin-top:0.25rem;">{{ $app->job->company->name ?? '' }}</div>
                            <div style="margin-top:0.5rem;">ステータス: {{ $app->status }}</div>
                        </div>
                        <div style="text-align:right;">
                            @if(isset($app->thread) && $app->thread)
                                <a class="btn btn-primary" href="{{ route('corporate.threads.show', ['thread' => $app->thread->id]) }}">チャットを開く</a>
                            @else
                                <span style="color:#6a737d;font-weight:700;">未チャット</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="card">応募はありません。</div>
            @endforelse

            <div style="margin-top:2rem;">{{ $applications->links() }}</div>
        </div>
    </main>
</body>
</html>
