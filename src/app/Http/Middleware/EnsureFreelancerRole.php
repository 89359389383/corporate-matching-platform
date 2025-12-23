<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureFreelancerRole
{
    /**
     * フリーランス以外�EアクセスをブロチE��する、E
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // 未ログインなら、ログイン画面へ誘導すめE
        if (!auth()->check()) {
            return redirect()->route('auth.login.form');
        }

        // roleがfreelancer以外なら、権限エラーとして拒否する
        if (auth()->user()->role !== 'freelancer') {
            abort(403, 'フリーランス権限が忁E��です、E);
        }

        return $next($request);
    }
}

