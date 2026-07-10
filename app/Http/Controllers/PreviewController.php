<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PreviewService;
use App\Services\UserService;

use App\Services\DateService;

use Carbon\Carbon;
use Illuminate\Support\Str;

class PreviewController extends Controller
{
    //public function changeDate(Request $request)
    /*
    public function changeDate(Request $request, DateService $dateService)
    {

        $date = $dateService->moveDate(
            $request->input('dates'),
            $request->input('move')
        );

        return redirect()->route('preview.page', [
            'dates' => $date->format('Y-m-d')
        ]);

    }
    */

    public function index(Request $request, UserService $userService, PreviewService $previewService)
    {
        $user = session('user_name');
        $car  = session('car');

        // --------------------------------------------------
        // 日付（URL優先）
        // --------------------------------------------------
        $dates = $request->input('dates');

        if (!$dates) {
            $dates = now()->format('Y-m-d');
        }

        // 安全にCarbon化
        $dateObj = Carbon::parse($dates);

        $dates = $dateObj->format('Y-m-d');

        // --------------------------------------------------
        // 投稿データ取得
        // --------------------------------------------------
        $posts = $previewService->getPosts($user, $car, $dates);

        
        $referer = request()->headers->get('referer');

        if ($posts->isEmpty()) {

            if (Str::contains($referer, 'dashboard')) {
                return redirect()->route('dashboard', ['error' => 'no_data']);
            }
            
        }

        $headerPost = $posts->first();

        // --------------------------------------------------
        // ユーザー一覧
        // --------------------------------------------------
        $groupedUsers = $userService->getGroupedUsers();

        //dd($groupedUsers);

        $selectedUser = $headerPost->user ?? '';

        // --------------------------------------------------
        // 件数
        // --------------------------------------------------
        $existingCount = \App\Models\SmilePost::where('member', $user)
            ->where('dates', $dates)
            ->where('car', $car)
            ->count();

        $startIndex = $existingCount + 1;

        // --------------------------------------------------
        // 日付分解
        // --------------------------------------------------
        $year  = $dateObj->year;
        $month = $dateObj->month;
        $day   = $dateObj->day;

        //dd($posts);

        // --------------------------------------------------
        // View
        // --------------------------------------------------
        return view('preview', [
            'posts'        => $posts,
            'headerPost'   => $headerPost,
            'groupedUsers' => $groupedUsers,
            'selectedUser' => $selectedUser,
            'date'         => $dates,
            'boardingUser' => $user,
            'car'          => $car,
            'startIndex'   => $startIndex,
            'year'         => $year,
            'month'        => $month,
            'day'          => $day,
        ]);
    }
}