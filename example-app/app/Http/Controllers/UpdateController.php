<?php //94点
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UpdateController extends Controller
{
    public function bulkUpdate(Request $request)
    {

        //--------------------------------------------------------------------------
        $dates = session('dates');

        //dd($request->all());

        //dd($dates);

        // DBの形式( Y-m-d )に変換
        //$dates = Carbon::createFromFormat('Y年n月j日', $dates)->format('Y-m-d');

        if (preg_match('/^\d{4}年\d{1,2}月\d{1,2}日$/', $dates)) {

            // 2026年5月23日
            $dates = Carbon::createFromFormat('Y年n月j日', $dates)
                ->format('Y-m-d');

        } else {

            // 2026-05-23
            $dates = Carbon::parse($dates)
                ->format('Y-m-d');
        }

        //dd($dates);

        //--------------------------------------------------------------------------

        // ループ内で各行を更新
        foreach ($request->id as $index => $id) {

            //dd($id, $request->all());

            DB::table('smile_posts')
                ->where('id', $id)
                ->update([
                    'user' => $request->user[$index] ?? '',
                    'dates' => $dates ?? '',
                    'departureTime' => $request->departureTime[$index] ?? null,
                    'arrivalTime' => $request->arrivalTime[$index] ?? null,
                    'goingBack' => $request->goingBack[$index] ?? '',
                    'destination' => $request->destinations[$index] ?? '',
                    'any' => $request->any[$index] ?? '',
                    'shareRide' => $request->shareRide[$index] ?? 0,
                    'classification' => $request->classification[$index] ?? '',
                    'remarks' => $request->remarks[$index] ?? '',
                    'distance' => $request->distance[$index] ?? 0,
                    'price' => $request->price[$index] ?? 0,
                    'start_distance' => $request->start_distance ?? 0, // ← ここで各行を更新
                    'end_distance'   => $request->end_distance ?? 0,   // ← 各行更新

                ]);
        }

        return redirect()->route('dashboard', ['success' => 'update']);
    }
}