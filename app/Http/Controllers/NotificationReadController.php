<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BadgeService;

class NotificationReadController extends Controller
{
    private BadgeService $badgeService;

    public function __construct(BadgeService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    /**
     * ベルクリック → 既読処理 → ダッシュボードへ
     */
    public function __invoke(Request $request)
    {
        $userId = auth()->id();

        // バッジリセット（既読扱い）
        $this->badgeService->reset($userId);

        // ダッシュボードへ遷移
        return redirect()->route('dashboard');
    }
}