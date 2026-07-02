<?php //92点
namespace App\Services;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PreviewService
{
    public function getPosts($user, $car, $dates)
    {
        return DB::table('smile_posts')
            ->when($user, fn($q) => $q->where('member', $user))
            ->when($car, fn($q) => $q->where('car', $car))
            ->when($dates, fn($q) => $q->where('dates', $dates))
            ->get();
    }

    /*
    public function getSessionData()
    {
        $user  = trim(session('user_name'));
        $car   = trim(session('car'));
        $dates = trim(session('dates'));

        //------------------------------------------------------------------------------------


        //$dates = "2026年5月21日"; //←仮にPOSTで新たに受け取ったとしてこうした


        //------------------------------------------------------------------------------------

        if ($dates) {
            $dates = Carbon::createFromFormat('Y年n月j日', $dates)
                ->format('Y-m-d');
        }

        return compact('user', 'car', 'dates');
    }
    */

    public function getUserData(UserService $userService)
    {
        return [
            'allUsers' => $userService->getNames(),
            'grouped'  => $userService->groupedByInitial(),
        ];
    }
}