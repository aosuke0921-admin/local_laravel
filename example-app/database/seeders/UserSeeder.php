<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ⚠️ 既存ユーザー削除する場合のみ
        User::truncate();

        $user1 = new User();
        $user1->full_name = '青山 直樹';
        $user1->user_login = '青山';
        $user1->user_pass = Hash::make('naoki593939');
        $user1->save();

        $user2 = new User();
        $user2->full_name = '江口 裕';
        $user2->user_login = '江口';
        $user2->user_pass = Hash::make('eguchi912');
        $user2->save();
    }
}