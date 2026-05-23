<?php //95点

namespace App\Services;

use Carbon\Carbon;
use App\Models\SmileCheck;

class InspectionService
{
    /**
     * 日付変換（YYYY年M月D日 → Y-m-d）
     */
    public function formatDate($dates)
    {
        if (!$dates) return null;

        if (preg_match('/(\d{4})年(\d{1,2})月(\d{1,2})日/', $dates, $m)) {
            return sprintf('%04d-%02d-%02d', $m[1], $m[2], $m[3]);
        }

        return $dates;
    }

    /**
     * 重複チェック
     */
    public function alreadyChecked($userId, $car, $dates)
    {
        return SmileCheck::where('user_id', $userId)
            ->where('car', $car)
            ->where('dates', $dates)
            ->exists();
    }

    /**
     * チェック保存（INSERT）
     */
    public function saveCheck($userId, $car, $rollCall, $weather, $dates)
    {
        return \App\Models\SmileCheck::create([
            'user_id'   => $userId,
            'car'       => $car,
            'roll_call' => $rollCall,
            'weather'   => $weather,
            'dates'     => $dates,
            'datetimes' => now(),
        ]);
    }
}