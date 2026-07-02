<?php //88点
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PrintController extends Controller
{
    /**
     * 印刷ページ表示
     */
    public function index(Request $request)
    {
        //--------------------------------------------------------------------------
        $currentUser = session('user_name'); // ログイン中の利用者
        $car = session('car'); // 開始走行距離
        $dates = session('dates'); // 日付

        // nullのまま進む可能性防止
        if (!$currentUser || !$car || !$dates) {
            //return redirect()->route('dashboard', ['id' => 'セッションが不正です']);
            return redirect()->route('dashboard', ['error' => 'car']);
        }

        // 変換（日本語フォーマット → DATE型）/ 念のため、sessionに値なかったらリダイレクト
        try {
            $dates = Carbon::createFromFormat('Y年n月j日', $dates)->format('Y-m-d');
        } catch (\Exception $e) {
            return redirect()->route('dashboard', ['id' => '日付形式が不正です']);
        }
        //-----------------------------------------------------------------------

        // SELECT
        $posts = DB::table('smile_posts')
            ->where('car', $car)
            ->where('dates', $dates)
            ->where('member', $currentUser)
            ->orderBy('departureTime', 'asc') // 小さい順 asc
            ->get();

        //--------------------------------------------------------------------------

        // データがなければ dashboard にリダイレクト
        if ($posts->isEmpty()) {
            // パラメータ付きリダイレクト例
            return redirect()->route('dashboard', ['error' => 'no_data']);
        }

        //--------------------------------------------------------------------------

        // ★ 表示用
        $displayDate = \Carbon\Carbon::parse($dates)->format('Y年n月j日'); //ここ

        // 合計乗車
        $total_rides = $posts->count();

        // 合計料金
        $total_price = $posts->sum('price'); // price カラムの合計

        // 合計距離
        $total_distance = $posts->sum('distance'); // distance カラムの合計

        // ログインしてるmember
        $driver_name = $currentUser;

        // 発時刻
        $start_time = $posts->first()?->departureTime ?? '';

        // 着時刻
        $end_time   = $posts->last()?->arrivalTime ?? '';

        // 1レコード目の開始距離
        $start_distance = $posts->first()?->start_distance ?? 0;

        // 最後のレコードの終了距離
        $end_distance = $posts->last()?->end_distance ?? 0;

        return view('print', [                      // 配列の値を渡すページ
            'posts' => $posts,                      // car,dates,member
            'total_rides'       => $total_rides,    // 合計乗車
            'total_price'       => $total_price,    // 合計料金
            'total_distance'    => $total_distance, // 合計距離
            'driver_name'       => $driver_name,    // ログインしてるmember
            'start_time'        => $start_time,     // 発時刻
            'end_time'          => $end_time,       // 着時刻
            'start_distance'    => $start_distance, // 1レコード目の開始距離
            'end_distance'      => $end_distance,   // 最後のレコードの終了距離

            'displayDate' => $displayDate,//ここ
        ]);
    }
}