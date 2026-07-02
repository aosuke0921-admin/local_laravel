<?php //95点

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DeleteService;
use App\Services\DateService;
use Carbon\Carbon;

use Illuminate\Support\Str;

class DeleteController extends Controller
{

    public function index(Request $request, DeleteService $deleteService)
    {
        // セッション取得（Service側で変換済み）
        $session = $deleteService->getSessionData();

        $user  = $session['user'];
        $car   = $session['car'];
        //$dates = $session['dates'];

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

        //dd($dates); // 2026-07-01

        // DB取得（Serviceに移動）
        $posts = $deleteService->getPosts($user, $car, $dates);



        //dump($posts);

        
        // データなし
        /*
        if ($posts->isEmpty()) {
            
            return redirect()->route('dashboard', [
                'error' => 'no_data'
            ]);
            
            //dd('nodata');
        }
        */
        $referer = request()->headers->get('referer');

       if ($posts->isEmpty()) {

            if (Str::contains($referer, 'dashboard')) {
                return redirect()->route('dashboard', ['error' => 'no_data']);
            }
            
        }

        $headerPost = $posts->first();

        // --------------------------------------------------
        // 日付分解
        // --------------------------------------------------
        $year  = $dateObj->year;
        $month = $dateObj->month;
        $day   = $dateObj->day;

        return view('delete', [
            //'date' => \Carbon\Carbon::parse($dates)->format('Y年n月j日'),
            'date' => $dateObj->toDateString(),
            'posts'      => $posts,
            'headerPost' => $headerPost,
            'year'         => $year,
            'month'        => $month,
            'day'          => $day,
        ]);
    }

    public function destroyMultiple(Request $request, DeleteService $deleteService)
    {
        // チェックされたID配列
        $selectedIds = $request->input('delete_check', []);

        // Serviceで削除処理
        $deleteService->deleteByIds($selectedIds);

        return redirect()->route('dashboard', [
            'success' => 'delete'
        ]);
    }
}