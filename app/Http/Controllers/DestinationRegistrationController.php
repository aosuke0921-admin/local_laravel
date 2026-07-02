<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DestinationRegistrationController extends Controller
{
    // 画面表示
    public function index()
    {
        return view('destination_registration');
    }

    // 登録処理（PHP側）
    public function store(Request $request)
    {
        // =========================
        // バリデーション（ここがPHPエラー防止）
        // =========================
        /*
        $validated = $request->validate([
            'destination' => 'required',
            'destination_hurigana' => 'required',
        ], [
            'destination.required' => '行き先を入力してください',
            'destination_hurigana.required' => 'フリガナを入力してください',
        ]);*/
        $validated = $request->validate([
            'destination' => ['required', 'regex:/^[ぁ-んァ-ン一-龥々ー　]+$/u'],
            'destination_hurigana' => ['required', 'regex:/^[ァ-ヶー　]+$/u'],
        ], [
            'destination.required' => '日本語で正しく入力してください',
            'destination.regex' => '日本語で正しく入力してください',

            'destination_hurigana.required' => '全角カタカナで正しく入力してください',
            'destination_hurigana.regex' => '全角カタカナで正しく入力してください',
        ]);

        // =========================
        // DB保存
        // =========================
        DB::table('destinations')->insert([
            'destination' => $validated['destination'],
            'destination_hurigana' => $validated['destination_hurigana'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // =========================
        // リダイレクト（成功）
        // =========================
        /*return redirect()
            ->route('destination_registration.page')
            ->with('success', '登録完了しました');*/
            return redirect()->route('dashboard', ['success' => 'insert']);
    }
}