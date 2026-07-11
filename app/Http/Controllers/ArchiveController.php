<?php
// 92点 → 96点へ整理版
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ArchiveController extends Controller
{
    /**
     * 共通のフィルタ処理
     */

    protected function applyFilters($query, Request $request)
    {
        // 年（デフォルト：今年）
        $year = $request->year_select && $request->year_select !== '選択してください'
            ? str_replace('年', '', $request->year_select)
            : date('Y');

        // 月（デフォルト：今月）
        $month = $request->month_select && $request->month_select !== '選択してください'
            ? str_replace('月', '', $request->month_select)
            : date('n');

        if ($request->user_name_select && $request->user_name_select !== '選択してください') {
            $query->where('user', $request->user_name_select);
        }

        if ($request->member_name_select && $request->member_name_select !== '選択してください') {
            $query->where('member', $request->member_name_select);
        }

        $query->whereYear('dates', $year);
        $query->whereMonth('dates', $month);

        //return $query->orderBy('dates')->get();
        return $query
            ->orderBy('dates', 'asc')
            ->orderBy('departureTime', 'asc')
            ->get();
    }

    /**
     * アーカイブページ表示
     */
    public function index(Request $request, UserService $userService)
    {
       
        $query = DB::table('smile_posts');
        $posts = $this->applyFilters($query, $request);


        //dd($posts->first());

        $members = DB::table('users')
            ->pluck('user_login')
            ->toArray();

        $groupedUsers = $userService->getGroupedUsers();

        return view('archive', [
            'posts'          => $posts,
            //'members'        => ['青山','江口','岩佐','岩脇','岸田','社長','会長','谷口','紙谷','谷','陽子','野田'],
            'members'        => $members,
            'groupedUsers'   => $groupedUsers, // ←統一
            'selectedUser'   => $request->user_name_select ?? '',
            'selectedMember' => $request->member_name_select ?? '',
            'selectedYear'   => $request->year_select && $request->year_select !== '選択してください'
                                ? $request->year_select
                                : date('Y').'年',
            'selectedMonth'  => $request->month_select && $request->month_select !== '選択してください'
                                ? $request->month_select
                                : date('n').'月',
        ]);
    }


    /**
     * CSVダウンロード（Mac / Windows文字化け対応）
     */
    public function downloadCsv(Request $request, UserService $userService)
    {
        $query = DB::table('smile_posts');

        $posts = $this->applyFilters($query, $request);

        //----------------------------------------------------------------------
        // ファイル名
        //----------------------------------------------------------------------

        $year = $request->year_select && $request->year_select !== '選択してください'
            ? trim($request->year_select)
            : date('Y') . '年';

        $month = $request->month_select && $request->month_select !== '選択してください'
            ? trim($request->month_select)
            : date('n') . '月';

        $filename = "運行実績{$year}{$month}度.csv";

        //----------------------------------------------------------------------
        // Mac判定
        //----------------------------------------------------------------------

        $agent = $request->header('User-Agent');

        $isMac = str_contains($agent, 'Macintosh');

        //----------------------------------------------------------------------

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($posts, $isMac) {

            $file = fopen('php://output', 'w');

            //------------------------------------------------------------------
            // Mac → UTF-8 BOM
            //------------------------------------------------------------------

            if ($isMac) {

                fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            }

            //------------------------------------------------------------------
            // header
            //------------------------------------------------------------------

            $header = [
                'ID',
                '利用者',
                '発時刻',
                '着時刻',
                '行き/帰り',
                '行先',
                '乗合',
                '乗務員',
                '区分',
                '距離',
                '料金'
            ];

            //------------------------------------------------------------------
            // Windows → SJIS
            //------------------------------------------------------------------

            if (!$isMac) {

                mb_convert_variables('SJIS-win', 'UTF-8', $header);

            }

            fputcsv($file, $header);

            //------------------------------------------------------------------
            // body
            //------------------------------------------------------------------

            foreach ($posts as $post) {

                $row = [

                    $post->id,
                    $post->user,
                    $post->departureTime,
                    $post->arrivalTime,
                    $post->goingBack,
                    $post->destination,

                    //$post->rideType ?? '',
                    //$post->shareRide ? '乗合' : '',
                    $post->shareRide == 1 ? '乗合' : '',

                    $post->member,
                    $post->insurance ?? '',
                    $post->distance,
                    $post->price,

                ];

                //--------------------------------------------------------------
                // WindowsのみSJIS
                //--------------------------------------------------------------

                if (!$isMac) {

                    mb_convert_variables('SJIS-win', 'UTF-8', $row);

                }

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}