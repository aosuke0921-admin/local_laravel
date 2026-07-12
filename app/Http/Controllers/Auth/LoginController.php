<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use App\Services\RecaptchaService;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    //public function login(Request $request)
public function login(Request $request, RecaptchaService $recaptcha)
    {


//dd($request->all());



        // バリデーション
        $credentials = $request->validate([
            'user_login' => ['required', 'string'],
            'password'   => ['required', 'string'],
        ]);

/*    ローカル開発だけここはコメントアウト
if (!$request->filled('recaptcha_token')) {
    return back()->withErrors([
        'recaptcha' => '認証に失敗しました。',
    ]);
}

   if (!$recaptcha->verify(
    $request->recaptcha_token,
    $request->ip(),
    'login'
)) {
    return back()->withErrors([
        'recaptcha' => '不正なアクセスの可能性があります。',
    ]);
}
*/

//dd($credentials);

        // 認証
        if (Auth::attempt([
            'user_login' => $credentials['user_login'],
            'password'   => $credentials['password'],
        ])) {

            $request->session()->regenerate();

            session([
                'show_distance_alert' => true
            ]);

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'user_login' => 'ユーザー名またはパスワードが違います。',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }



}