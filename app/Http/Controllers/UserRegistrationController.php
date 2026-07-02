<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserRegistrationController extends Controller
{
    public function index()
    {
        return view('user_registration');
    }

    public function store(Request $request)
    {

        $request->validate([
            'user1' => ['required', 'regex:/^[ぁ-んァ-ン一-龥々ー　]+$/u'],
            'user2' => ['required', 'regex:/^[ぁ-んァ-ン一-龥々ー　]+$/u'],
            'user_hurigana1' => ['required', 'regex:/^[ァ-ヶー　]+$/u'],
            'user_hurigana2' => ['required', 'regex:/^[ァ-ヶー　]+$/u'],
            'classification' => [
                'required',
                'in:介護保険,障害福祉,保険外'
            ],
        ], [
            'user1.required' => '日本語で正しく入力してください',
            'user2.required' => '日本語で正しく入力してください',
            'user1.regex' => '日本語で正しく入力してください',
            'user2.regex' => '日本語で正しく入力してください',

            'user_hurigana1.required' => '全角カタカナで正しく入力してください',
            'user_hurigana2.required' => '全角カタカナで正しく入力してください',
            'user_hurigana1.regex' => '全角カタカナで正しく入力してください',
            'user_hurigana2.regex' => '全角カタカナで正しく入力してください',
            'classification.required' => '区分を選択してください',
            'classification.in' => '区分を正しく選択してください',
        ]);

        $fullName = $request->user1 . ' ' . $request->user2;
        $fullNameKana = $request->user_hurigana1 . ' ' . $request->user_hurigana2;

        //DB::table('user_names')->insert([
        DB::table('customers')->insert([
            'name' => $fullName,
            'kana' => $fullNameKana,
            'support_notes' => $request->support_textarea,
            'classification' => $request->classification,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        /*return redirect()->route('user_registration.page')
            ->with('success', '登録完了しました');*/
            return redirect()->route('dashboard', ['success' => 'insert']);
    }
}