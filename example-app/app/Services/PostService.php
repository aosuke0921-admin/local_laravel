<?php //97点
namespace App\Services;
use App\Models\SmilePost;

// RideService.php → PostService.php

//class RideService
class PostService
{

    public function updateDistance($car, $member, $dates, $start_distance, $end_distance)
    {
        SmilePost::where('car', $car)
            ->where('dates', $dates)
            ->where('member', $member)
            ->update([
                'start_distance' => $start_distance,
                'end_distance' => $end_distance,
            ]);
    }

    // 中だけで使ってる private
    private function formatDate($dates)
    {
        if (!$dates) return null;

        try {
            return \Carbon\Carbon::createFromFormat('Y年n月j日', $dates)
                ->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
    // 外で使うpublic
    public function saveRides(
        $car,
        $member,
        $dates,
        $start_distance,
        $end_distance,
        $users,
        $departures,
        $arrivals,
        $goingBacks,
        $destinations,
        $anys,
        $shareRides,
        $classifications,
        $remarks,
        $distances,
        $prices
    ) {
        // セッションの日付（日本語形式）をDB用のY-m-d形式に変換
        $dates = $this->formatDate($dates);

        foreach ($users as $i => $user) {

            if (empty($user)) continue;

            //$shared = ($shareRides[$i] ?? '') === "乗合" ? 1 : 0;
            $shared = ($shareRides[$i] ?? '0') == '1' ? 1 : 0;

            SmilePost::create([
                'car' => $car,
                'member' => $member,
                'dates' => $dates,
                'user' => $user,
                'start_distance' => $start_distance,
                'end_distance' => $end_distance,
                'departureTime' => $departures[$i] ?? '',
                'arrivalTime' => $arrivals[$i] ?? '',
                'goingBack' => $goingBacks[$i] ?? '',
                'destination' => $destinations[$i] ?? '',
                'any' => $anys[$i] ?? '',
                'shareRide' => $shared,
                'classification' => $classifications[$i] ?? '',
                'remarks' => $remarks[$i] ?? '',
                'distance' => $distances[$i] ?? '',
                'price' => $prices[$i] ?? 0,
                'datetimes' => now(),
            ]);
        }
    }
}