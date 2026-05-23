<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RecaptchaService
{
    public function verify($token, $ip, $action = 'login')
    {
        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret' => config('recaptcha.secret_key'),
                'response' => $token,
                'remoteip' => $ip,
            ]
        );

        $result = $response->json();

        //dd($result); // ←ここ
        

        // 判定
        if (
            !$result['success'] ||
            $result['score'] < config('recaptcha.score') ||
            $result['action'] !== $action
        ) {
            return false;
        }

        return true;
    }
}