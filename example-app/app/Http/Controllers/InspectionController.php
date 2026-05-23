<?php //96点
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InspectionService;
use App\Services\WeatherService;
use Carbon\Carbon;
use App\Models\SmileCheck;

class InspectionController extends Controller
{      
    public function index2(
        Request $request,
        InspectionService $inspectionService,
        WeatherService $weatherService
    )
    {
        $user_login = trim(session('user_name'));

        $selectedCar = trim(session('car') ?? 'car1');
       
        // セッション日付（Serviceで変換済み）
        $dates = $inspectionService->formatDate(session('dates'));

        $displayDate = \Carbon\Carbon::parse($dates)->format('Y年m月j日');

        //dd($displayDate.' / '.$dates);


        $userId = auth()->id();

        // ⭐ここで天気取得
        $weather = $weatherService->getTodayWeather();
        session(['weather' => $weather]);

        // 今月範囲
        $start = now()->startOfMonth()->format('Y-m-d');
        $end   = now()->endOfMonth()->format('Y-m-d');

        // =========================================
        // ★修正ポイント：車×月で「1件でもあるか」
        // =========================================
        $targetDate = Carbon::parse($dates);

        $existsThisMonth = SmileCheck::where('user_id', $userId)
            ->where('car', $selectedCar)
            ->whereYear('dates', $targetDate->year)
            ->whereMonth('dates', $targetDate->month)
            ->exists();

        $isFirstCheckThisMonth = !$existsThisMonth;

        //dd($existsThisMonth, $isFirstCheckThisMonth);

        // 月1項目
        $monthlyItems = ['c9','c10','c11','c12','c13','c14','c15','c17'];

        // 全項目
        $allItems = [
            'c1','c2','c3','c4','c5','c6','c7','c8','c9','c10',
            'c11','c12','c13','c14','c15','c16','c17','c18','c19','c20','c21'
        ];

        $checkedItems = $request->input('checks', []);

        // 既にチェック済みなら弾く
        if ($inspectionService->alreadyChecked($userId, $selectedCar, $dates)) {
            return redirect('/post');
        }

        $roll_call = $request->roll_call;
        session(['roll_call' => $roll_call]);

        $weather = session('weather') ?? '不明';

        // =========================================
        // 初回（月1あり）
        // =========================================
        if ($isFirstCheckThisMonth && count($checkedItems) === count($allItems)) {

            $inspectionService->saveCheck(
                $userId,
                $selectedCar,
                $roll_call,
                $weather,
                $dates
            );

            return redirect('/post');
        }

        // =========================================
        // 2回目以降（月1なし）
        // =========================================
        $remainingItems = array_diff($allItems, $monthlyItems);

        if (!$isFirstCheckThisMonth && count($checkedItems) === count($remainingItems)) {

            $inspectionService->saveCheck(
                $userId,
                $selectedCar,
                $roll_call,
                $weather,
                $dates
            );

            return redirect('/post');
        }

        return view('inspection_check', [
            'checkedItems' => $checkedItems,
            'monthlyItems' => $monthlyItems,
            'allItems' => $allItems,
            'selectedCar' => $selectedCar,
            'isFirstCheckThisMonth' => $isFirstCheckThisMonth,

            'user_login' => $user_login, // ←これ
            'dates' => $dates,           // ←これ
            'weather' => $weather,       // ←これ

            'displayDate' => $displayDate,
        ]);
    }

    public function index()
    {
        $now = Carbon::now();

        $checkedCarsRaw = SmileCheck::whereYear('dates', $now->year)
            ->whereMonth('dates', $now->month)
            ->pluck('car')
            ->toArray();

        $checkedCars = array_map('trim', $checkedCarsRaw);

        return view('inspection_check', [
            'checkedCars' => $checkedCars,
            'carArray' => $checkedCars,
        ]);
    }

    public function submitCheck(Request $request)
    {
        return redirect()->route('inspection.check', ['error' => 'no_check']);
    }
}