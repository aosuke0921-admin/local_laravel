<?php //86点 → 95点整理版

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\UserService;

use App\Models\User;

class MonthArchiveController extends Controller
{
    public function index(Request $request, UserService $userService)
    {

        //phpinfo();






        // 🔽 検索条件
        $selectedYear  = $request->input('year_select', '');
        $selectedMonth = $request->input('month_select', '');
        $search_day    = $request->input('day_select', '');
        $selectedUser  = $request->input('user_select', '');




//dd($request->all());





        // 🔽 クエリ準備
        $query = DB::table('smile_posts');

        // 初回アクセス（全未選択）
        if (!$selectedYear && !$selectedMonth && !$search_day && !$selectedUser) {
            $query->whereBetween('dates', [
                now()->startOfMonth()->toDateString(),
                now()->endOfMonth()->toDateString()
            ]);
        } else {

            if ($selectedYear) {
                $query->whereYear('dates', (int)$selectedYear);
            }

            if ($selectedMonth) {
                $query->whereMonth('dates', (int)$selectedMonth);
            }

            if ($search_day && str_contains($search_day, '〜')) {
                switch ($search_day) {
                    case '1日〜10日':
                        $query->whereBetween(DB::raw('DAY(dates)'), [1, 10]);
                        break;
                    case '10日〜20日':
                        $query->whereBetween(DB::raw('DAY(dates)'), [10, 20]);
                        break;
                    case '20日〜31日':
                        $query->whereBetween(DB::raw('DAY(dates)'), [20, 31]);
                        break;
                    case '1日〜15日':
                        $query->whereBetween(DB::raw('DAY(dates)'), [1, 15]);
                        break;
                    case '15日〜20日':
                        $query->whereBetween(DB::raw('DAY(dates)'), [15, 20]);
                        break;
                    case '1日〜31日':
                        $query->whereBetween(DB::raw('DAY(dates)'), [1, 31]);
                        break;
                }
            }

            if ($selectedUser && $selectedUser !== '選択してください') {
                $query->where('user', $selectedUser);
            }
        }

        // 🔽 実行
        $select_tb = $query
            ->orderBy('dates', 'asc')
            ->get()
            ->map(fn($item) => (array)$item)
            ->toArray();

        $groupedUsers = app(UserService::class)->getGroupedUsers();

        return view('month_archive', [
            'groupedUsers'   => $groupedUsers, // ←統一
            'select_tb'     => $select_tb,
            'selectedYear'  => $selectedYear,
            'selectedMonth' => $selectedMonth,
            'sarch_day'     => $search_day,
            'selectedUser'  => $selectedUser,
            'array'         => [],
            'arrayArea1'    => [],
            'arrayArea2'    => [],
            'arrayArea3'    => [],
            'newArray'      => [],
        ]);
    }

    //-------------------------------------------------------------------------------------

    public function downloadCsv(Request $request)
    {






        $query = DB::table('smile_posts');

        $selectedYear  = $request->input('year_select', '');
        $selectedMonth = $request->input('month_select', '');
        $search_day    = $request->input('day_select', '');
        $selectedUser  = $request->input('user_select', '');

        if ($selectedYear) {
            $query->whereYear('dates', (int)$selectedYear);
        }

        if ($selectedMonth) {
            $query->whereMonth('dates', (int)$selectedMonth);
        }

        if ($search_day && str_contains($search_day, '〜')) {

            switch ($search_day) {

                case '1日〜10日':
                    $query->whereBetween(DB::raw('DAY(dates)'), [1, 10]);
                    break;

                case '10日〜20日':
                    $query->whereBetween(DB::raw('DAY(dates)'), [10, 20]);
                    break;

                case '20日〜31日':
                    $query->whereBetween(DB::raw('DAY(dates)'), [20, 31]);
                    break;

                case '1日〜15日':
                    $query->whereBetween(DB::raw('DAY(dates)'), [1, 15]);
                    break;

                case '15日〜20日':
                    $query->whereBetween(DB::raw('DAY(dates)'), [15, 20]);
                    break;

                case '1日〜31日':
                    $query->whereBetween(DB::raw('DAY(dates)'), [1, 31]);
                    break;
            }
        }

        if ($selectedUser && $selectedUser !== '選択してください') {
            $query->where('user', $selectedUser);
        }

        //----------------------------------------------------------------------
        // Mac判定
        //----------------------------------------------------------------------

        $agent = $request->header('User-Agent');

        $isMac = str_contains($agent, 'Macintosh');

        //----------------------------------------------------------------------

        $posts = $query->orderBy('dates')->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=月報.csv",
        ];


        //dd($posts);


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
                '保険',
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

                    $post->shareRide ? '乗合' : '',

                    $post->member,
                    $post->classification ?? '',
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