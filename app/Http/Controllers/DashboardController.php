<?php //96点
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    // Serviceは「内部部品」だから private
    private $service;

    // ここでServiceを受け取り、クラス内で使えるように保持する
    public function __construct(DashboardService $service)
    {
        // 受け取ったServiceをプロパティに保存
        // これで $this->service としてどのメソッドからも使える
        $this->service = $service;

    }

    public function getStartDistance(Request $request)
    {
        try {
            $dates = $request->input('dates');
            $car   = $request->input('car');

            // Serviceに移行済み
            $start_distance = $this->service->getStartDistance($dates, $car);

            return response()->json([
                'start_distance' => $start_distance
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index(Request $request)
    {

        // ログインユーザー
        $user_name = auth()->user()->user_login;

        $distanceErrors = $this->service->getDistanceErrors($user_name);

        $showDistanceAlert = session()->pull('show_distance_alert', false);
        
        // Androidか調べる
        $isAndroid = str_contains(request()->userAgent(), 'Android');

        //sessionの有効期限を確認
        //dd(ini_get('session.gc_maxlifetime'));

        // sesseion削除
        session()->forget(['user_name', 'car', 'dates']);

        $id = $request->query('id');

        // car（URL優先 → session）
        $car = $request->query('car', session('car'));

        // dates（なければ今日）
        $dates = session('dates', now()->format('Y年n月d日'));

        $start_distance = session('start_distance', '');

        $today = now()->format('Y年n月d日');

        // config/cars.php 車両一覧、めったに変更ないからconfigへ
        $cars = config('cars.list');

        // Serviceに移行済み
        $this->service->saveDashboardSession(
            $user_name,
            $car,
            $dates,
            $start_distance
        );

        return view('dashboard', compact(
            'id',
            'car',
            'dates',
            'cars',
            'start_distance',
            'today',
            'isAndroid',
            'distanceErrors',   // ←これを渡す
            'showDistanceAlert'
        ));
    }

    /**
     * フォーム送信処理
     * ・入力データをセッションに保存
     * ・ボタン種別に応じて各処理へリダイレクト
     * ・業務ロジックはService側で実行
     */
    public function submitForm(Request $request)
    {
        // Serviceに移行済み
        return $this->service->handleSubmit($request);
    }
}