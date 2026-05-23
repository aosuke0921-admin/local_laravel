<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * 利用者一覧（頭文字グルーピング）
     */
    public function getGroupedUsers()
    {
    $users = DB::table('customers')
        ->select('name', 'kana', 'support_notes') // ★追加
        ->distinct()
        ->whereNotNull('kana')
        ->where('kana', '!=', '')
        ->get()
        ->unique('name') // ←これ追加
        ->map(function ($item) {
            $item->kana = mb_convert_kana(trim($item->kana), 'c');
            return $item;
        });

            return $users->groupBy(function ($item) {
                //return mb_substr($item->kana, 0, 1);

                // 先頭1文字取得
                $initial = mb_substr($item->kana, 0, 1);

                // 濁音・半濁音を通常かなへ変換
                $initial = strtr($initial, [
                    'が'=>'か','ぎ'=>'き','ぐ'=>'く','げ'=>'け','ご'=>'こ',
                    'ざ'=>'さ','じ'=>'し','ず'=>'す','ぜ'=>'せ','ぞ'=>'そ',
                    'だ'=>'た','ぢ'=>'ち','づ'=>'つ','で'=>'て','ど'=>'と',
                    'ば'=>'は','び'=>'ひ','ぶ'=>'ふ','べ'=>'へ','ぼ'=>'ほ',
                    'ぱ'=>'は','ぴ'=>'ひ','ぷ'=>'ふ','ぺ'=>'へ','ぽ'=>'ほ',
                ]);

                return $initial;

            });
        }


    /**
     * 行き先一覧（ユーザー単位グルーピング）
     */
    public function getGroupedDestinations()
    {
        $destinations = DB::table('destinations')
            ->select('destination', 'destination_hurigana')
            ->get()
            ->unique('destination')
            ->map(function ($item) {

                // ひらがな or カタカナ統一（必要なら）
                $kana = mb_convert_kana($item->destination_hurigana, 'c');

                $item->kana = $kana;

                return $item;
            });

        return $destinations->groupBy(function ($item) {
            //return mb_substr($item->kana, 0, 1);

            $initial = mb_substr($item->kana, 0, 1);

            $initial = strtr($initial, [
                'が'=>'か','ぎ'=>'き','ぐ'=>'く','げ'=>'け','ご'=>'こ',
                'ざ'=>'さ','じ'=>'し','ず'=>'す','ぜ'=>'せ','ぞ'=>'そ',
                'だ'=>'た','ぢ'=>'ち','づ'=>'つ','で'=>'て','ど'=>'と',
                'ば'=>'は','び'=>'ひ','ぶ'=>'ふ','べ'=>'へ','ぼ'=>'ほ',
                'ぱ'=>'は','ぴ'=>'ひ','ぷ'=>'ふ','ぺ'=>'へ','ぽ'=>'ほ',
            ]);

            return $initial;

            
        });
    }

    /**
     * JS用フルデータ
     */
    public function getAllRecords()
    {
        return DB::table('user_destination_records')
            ->select(
                'id',   // ←これ絶対必要
                'user',
                'destination',
                'pickup_location',
                'dialysis',
                'transport_fee',
                'distance'
            )
            ->get();
    }
}