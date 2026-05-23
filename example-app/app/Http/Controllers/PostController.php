<?php
//RideController.php → PostController.php

namespace App\Http\Controllers;

use App\Services\PostService;
use App\Services\UserService;
use App\Http\Requests\StorePostRequest;
use App\Models\SmilePost; // ← 忘れがち

class PostController extends Controller
{
    public function index(UserService $userService)
    {
    $groupedUsers = $userService->getGroupedUsers();


    if (
        !session()->has('user_name') ||
        !session()->has('car') ||
        !session()->has('dates')
    ) {
        return redirect('/dashboard');
    }
    

    $user = trim(session('user_name'));
    $car  = trim(session('car'));
    $dates = session('dates');

    //dd($user.$car.$dates);

    if (str_contains($dates, '年')) {
        $dates = str_replace(['年','月','日'], ['-','-',''], $dates);
    }

    $dates = \Carbon\Carbon::parse($dates)->format('Y-m-d');

    $existingCount = \App\Models\SmilePost::where('member', $user)
        ->where('dates', $dates)
        ->where('car', $car)
        ->count();

    // ★ 表示用
    $displayDate = \Carbon\Carbon::parse($dates)->format('Y年n月j日');

    $startIndex = $existingCount + 1;

        return view('post', compact(
            'groupedUsers',
            'startIndex',
            'existingCount',
            'user',
            'car',
            'dates',
            'displayDate'
        ));
    }

    public function store(StorePostRequest $request, PostService $postService)
    {
        
        //dd($request->all());

        //--------------------------------------------------------------------------
        // セッション値取得（全てここに統一）
        $user = trim(session('user_name'));
        $car  = trim(session('car'));
        $dates = session('dates');
        $start_distance = session('start_distance', 0);
        $end_distance   = session('end_distance', 0);
        //--------------------------------------------------------------------------
        // 配列取得・この3つバリデーションしてる        
        $users = $request->input('user', []);
        $departures = $request->input('departureTime', []);
        $arrivals = $request->input('arrivalTime', []);
        //--------------------------------------------------------------------------
        // 配列取得
        $goingBacks = $request->input('goingBack', []);
        $destinations = $request->input('destinations', []);
        $anys = $request->input('any', []);
        $shareRides = $request->input('shareRide', []);
        $classifications = $request->input('classification', []);
        $remarks = $request->input('remarks', []);
        $distances = $request->input('distance', []);
        $prices = $request->input('price', []);
        $member = $user;

        //--------------------------------------------------------------------------
        // 既存レコード更新（距離だけはsession統一）
        $postService->updateDistance(
            $car,
            $member,
            $dates,
            $start_distance,
            $end_distance
        );
        //--------------------------------------------------------------------------
        // 新規登録
        $postService->saveRides(
            $car,
            $member,
            $dates,
            $start_distance,
            $end_distance,
            $users,
            $departures,
            $arrivals,
            $goingBacks,
            $destinations,
            $anys,
            $shareRides,
            $classifications,
            $remarks,
            $distances,
            $prices
        );

        // スマホのホーム画面ショートカットをタップ。この３つがsessionにあれば登録画面・なければダッシュボードの所で使ってる
        // 登録でこの３つのsessionは削除
        session()->forget(['user_name', 'car', 'dates']);

        // 登録処理完了 → successパラメータを付けてダッシュボードへ遷移
        return redirect()->route('dashboard', ['success' => 'insert']);
    }
}