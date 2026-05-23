<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;  // ← これが必須
use Illuminate\Session\TokenMismatchException;

/*

Handler.php / web.phpに追記不要

例外処理専用
POST フォームで CSRF トークンが切れた場合（419）や、セッションが切れた場合にどうするかをまとめて書く場所
Ajax 送信の場合もここで捕捉 → JS でリダイレクト

*/

class Handler extends ExceptionHandler
{
    // 既存のコードはそのまま

    public function render($request, Throwable $exception)
    {
        // CSRF切れをログインページへリダイレクト
        if ($exception instanceof TokenMismatchException) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => 'セッションが切れました。ログインしてください。',
                ], 419);
            }
            return redirect()->route('login')->with('message', 'セッションが切れました。再度ログインしてください。');
        }

        return parent::render($request, $exception);
    }
}