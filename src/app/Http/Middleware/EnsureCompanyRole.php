<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureCompanyRole
{
    /**
     * 企業以外�EアクセスをブロチE��する、E
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // 未ログインなら、ログイン画面へ誘導すめE
        if (!auth()->check()) {
            return redirect()->route('auth.login.form');
        }

        // roleがcompany以外なら、権限エラーとして拒否する
        if (auth()->user()->role !== 'company') {
            abort(403, '企業権限が忁E��です、E);
        }

        return $next($request);
    }
}

