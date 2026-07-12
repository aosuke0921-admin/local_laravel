<?php //96点

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{

public function getDistanceErrors($member)
{
    $today = now()->format('Y-m-d');

    return DB::table('smile_posts')
        ->where('member', $member)
        ->where('dates', '!=', $today)
         ->where('car', '!=', '選択してください')
        ->where(function ($query) {
            $query->where('start_distance', 0)
                  ->orWhere('start_distance', '')
                  ->orWhere('end_distance', 0)
                  ->orWhere('end_distance', '')
                  ->orWhereRaw('CAST(start_distance AS UNSIGNED) < 10'); //100キロ未満
        })
        ->select('dates', 'car')
        ->groupBy('dates', 'car')
        ->orderBy('dates')
        ->get();
}

    //public function getStartDistance($dates, $car)
    public function getStartDistance($dates, $car)
    {
        return DB::table('smile_posts')
            ->where('dates', $dates)
            ->where('car', $car)
            ->orderBy('id', 'desc')
            ->value('start_distance') ?? 0;
    }

    public function saveDashboardSession($user_name, $car, $dates, $start_distance)
    {
        session([
            'user_name'      => $user_name,
            'car'            => $car,
            'dates'          => $dates,
            'start_distance' => $start_distance,
        ]);
    }

    // 🔥 追加：submitForm全部のロジック
    public function handleSubmit($request)
    {
        $user_name = session('user_name');
        $id = $request->query('id');
        $dates = $request->input('dates');
        $car = $request->input('car');
        $start_distance = $request->input('start_distance');
        $submitText = $request->input('submitText');

        //dd($submitText);//user_registration

        session([
            'id'             => $id,
            'dates'          => $dates ?? now()->format('Y年n月d日'),
            'car'            => $car ?? '',
            'start_distance' => $start_distance ?? 0,
            'submit_value'   => $submitText,
            //'cancel'         => $request->input('cancel'), // ←追加
            //'cancel' => $request->input('cancel', 0),
            //'cancel' => $request->boolean('cancel'),
            'mode' => $submitText,
        ]);

        // 🚫 エラー系
        if (
            ($submitText === 'insert' || $submitText === 'update' || $submitText === 'delete')
            && empty($car)
        ) {
            return redirect()->route('dashboard', ['error' => 'car']);
        }

        if ($submitText === 'insert' && $start_distance == 0) {
            return redirect()->route('dashboard', ['error' => 'start_distance']);
        }

        if (empty($user_name)) {
            return redirect('/login');
        }

        //$date = $request->input('dates');

        $date = $request->input('dates');

        $date = Carbon::parse(
            str_replace(['年','月','日'], ['-','-',''], $date)
        )->format('Y-m-d');


        // 🔀 分岐
        switch ($submitText) {
            case 'insert':
                return redirect()->route('inspection.check');

            /*case 'update':
                return redirect()->route('preview.page');*/
            case 'update':
                return redirect()->route('preview.page', [
                    'dates' => $date
                ]);

            case 'delete':
                //return redirect()->route('delete.page');
                return redirect()->route('delete.page', [
                    'dates' => $date
                ]);

            case 'archive':
                return redirect()->route('archive.page');

            case 'month-archive':
                return redirect()->route('month_archive.page');

            case 'print':
                return redirect()->route('print.page');

            case 'user_registration':
                return redirect()->route('user_registration.page');

            case 'destination_registration':
                return redirect()->route('destination_registration.page');

            case 'user_destination_registration':
                return redirect()->route('user_destination_registration.page');

            case 'pop_select':
                return redirect()->route('pop_select.page'); //これ

            case 'cancel_boarding':
                return redirect()->route('boarding_reservation.page', [
                    'mode' => 'support'
                ]);

            case 'cancel_search':
                return redirect()->route('reservation_search.page', [
                    'mode' => 'support'
                ]);

            case 'boarding_reservation':
                return redirect()->route('boarding_reservation.page');

            case 'reservation_search':
                return redirect()->route('reservation_search.page');

            case 'master':
                return redirect()->route('master.page');

            default:
                return redirect()->back();
        }
    }
}