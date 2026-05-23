<?php //96点

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DeleteService
{
    /**
     * セッション情報取得＋日付変換
     */
    public function getSessionData()
    {
        $user  = trim(session('user_name'));
        $car   = trim(session('car'));
        $dates = trim(session('dates'));

        
        //if ($hoge !== false && $hoge !== 0 && $hoge !== "" && $hoge !== null)
        if ($dates) {
            try {
                // 日本語日付 → Y-m-d変換
                $dates = Carbon::createFromFormat('Y年n月j日', $dates)->format('Y-m-d');

            } catch (\Exception $e) {

                $dates = null;
                
            }
        }

        return compact('user', 'car', 'dates');
    }

    /**
     * 一覧取得（削除画面用）
     */
    public function getPosts($user, $car, $dates)
    {
        return DB::table('smile_posts')
            ->when($user, fn($q) => $q->where('member', $user))
            ->when($car, fn($q) => $q->where('car', $car))
            ->when($dates, fn($q) => $q->where('dates', $dates))
            ->get();
    }

    /**
     * 複数削除
     */
    public function deleteByIds(array $ids)
    {
        if (empty($ids)) return 0;

        return DB::table('smile_posts')
            ->whereIn('id', $ids)
            ->delete();
    }
}