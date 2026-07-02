<?php
namespace App\Services;

use App\Models\User;

class BadgeService
{
    // 増やす
    public function increment($userId)
    {
        User::where('id', $userId)->increment('badge_count');
    }

    // リセット
    public function reset($userId)
    {
        User::where('id', $userId)->update([
            'badge_count' => 0
        ]);
    }

    // 取得
    public function get($userId)
    {
        return User::where('id', $userId)->value('badge_count') ?? 0;
    }
}