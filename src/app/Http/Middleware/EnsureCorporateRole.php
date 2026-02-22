<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureCorporateRole
{
    /**
     * 法人以外のアクセスをブロックする。
     */
    public function handle(Request $request, Closure $next)
    {
        // このリクエストでは corporate guard を使用する
        Auth::shouldUse('corporate');

        if (!auth('corporate')->check()) {
            return redirect()->route('auth.login.form');
        }

        if (auth('corporate')->user()->role !== 'corporate') {
            abort(403, '法人権限が必要です。');
        }

        return $next($request);
    }
}

