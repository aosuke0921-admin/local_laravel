<?php //96点
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PreviewService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PreviewController extends Controller
{

    public function changeDate(Request $request)
    {

        $date = $request->input('dates');

        $date = Carbon::parse($date)->format('Y-m-d');

        session(['dates' => $date]);

        //dd(session('dates'));

        // ヒット件数

        $user  = session('user_name');
        $car   = session('car');
        $dates = session('dates');

        $count = \App\Models\SmilePost::where('member', $user)
            ->where('dates', $dates)
            ->where('car', $car)
            ->count();

        //dd($count);

        if($count == 0){

            return redirect()->route('preview.page', [
                'error' => 'no_data'
            ]);

        }else{

            return redirect()->route('preview.page', [
                'count' => $count
            ]);

        }

    }

    public function index(UserService $userService, PreviewService $previewService)
    {
        //--------------------------------------------------------------------------
        // セッション取得
        //--------------------------------------------------------------------------

        $session = session()->all();

        $user  = session('user_name');
        $car   = session('car');
        $dates = session('dates');

        if ($dates && str_contains($dates, '年')) {
            $dates = Carbon::createFromFormat('Y年n月j日', $dates)
                ->format('Y-m-d');
        }

        //--------------------------------------------------------------------------
        // 投稿データ取得
        //--------------------------------------------------------------------------

        $posts = $previewService->getPosts($user, $car, $dates);

        $referer = request()->headers->get('referer');

        if ($posts->isEmpty()) {

            if (Str::contains($referer, 'dashboard')) {

                //dd('ダッシュボード');
                return redirect()->route('dashboard', ['error' => 'no_data']);

            }else{

                //dd('更新');

            }

        }

        $headerPost = $posts->first();

        //--------------------------------------------------------------------------
        // ユーザー一覧（グルーピング）
        //--------------------------------------------------------------------------
        $groupedUsers = $userService->getGroupedUsers();

        //--------------------------------------------------------------------------
        // 更新ページの初期値（ここが重要）
        //--------------------------------------------------------------------------
        $selectedUser = $headerPost->user ?? '';

        $existingCount = \App\Models\SmilePost::where('member', $user)
            ->where('dates', $dates)
            ->where('car', $car)
            ->count();

        $startIndex = $existingCount + 1;

        //--------------------------------------------------------------------------
        // View
        //--------------------------------------------------------------------------
        return view('preview', [
            'posts'        => $posts,
            'headerPost'   => $headerPost,
            'groupedUsers' => $groupedUsers,
            'selectedUser' => $selectedUser,
            'date'         => $dates,
            //'date' => \Carbon\Carbon::parse($dates)->format('Y年n月j日'),
            'boardingUser' => $user,
            'car'          => $car,
            'startIndex' => $startIndex,
        ]);
    }
}