<?php

namespace App\Services;

use App\Models\PushSubscription;
use App\Models\User;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;



class NotificationService
{


public function send($title, $body)
{
    \Log::info('SERVICE START');

    $auth = [
        'VAPID' => [
            'subject' => 'mailto:test@example.com',
            'publicKey' => env('VAPID_PUBLIC_KEY'),
            'privateKey' => env('VAPID_PRIVATE_KEY'),
        ],
    ];

    $webPush = new WebPush($auth);

    $subs = PushSubscription::all();

    foreach ($subs as $sub) {

        \Log::info('QUEUE PUSH', [
            'user_id' => $sub->user_id,
            'endpoint' => $sub->endpoint
        ]);

        try {
            $subscription = Subscription::create([
                'endpoint' => $sub->endpoint,
                'keys' => [
                    'p256dh' => $sub->public_key,
                    'auth'   => $sub->auth_token,
                ],
            ]);

            $webPush->queueNotification(
                $subscription,
                json_encode([
                    'title' => $title,
                    'body'  => $body,
                ])
            );

        } catch (\Exception $e) {
            \Log::error('QUEUE ERROR: ' . $e->getMessage());
        }
    }

    //$incrementedUsers = [];











$successCount = 0;

foreach ($webPush->flush() as $report) {

    $success = $report->isSuccess();

    \Log::info('PUSH RESULT', [
        'success' => $success,
        'reason'  => $report->getReason(),
    ]);

    if ($success) {

        $endpoint = (string) $report->getRequest()->getUri();

        $sub = PushSubscription::where('endpoint', $endpoint)->first();

        \Log::info('MATCH DEBUG', [
            'endpoint' => $endpoint,
            'found' => $sub ? true : false,
            'user_id' => $sub->user_id ?? null,
        ]);

        $successCount++;
    }

    if (
        !$success &&
        $report->getReason() &&
        (
            str_contains($report->getReason(), '410') ||
            str_contains($report->getReason(), 'Gone') ||
            str_contains($report->getReason(), 'expired') ||
            str_contains($report->getReason(), 'unsubscribed')
        )
    ) {
        $endpoint = (string) $report->getRequest()->getUri();

        PushSubscription::where('endpoint', $endpoint)->delete();

        \Log::info('DELETED INVALID SUB', [
            'endpoint' => $endpoint
        ]);
    }
}

/*
|--------------------------------------------------------------------------
| 通知が1件でも成功したら全員のバッジを+1
|--------------------------------------------------------------------------
*/

if ($successCount > 0) {

    User::query()->increment('badge_count');

    \Log::info('BADGE INCREMENTED ALL USERS');
}






    
}
}