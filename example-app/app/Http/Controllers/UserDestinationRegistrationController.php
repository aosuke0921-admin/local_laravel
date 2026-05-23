<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\UserDestinationRecord;

use App\Services\UserService;

use Illuminate\Support\Facades\DB; // ←これ必須

class UserDestinationRegistrationController extends Controller
{

    public function index(UserService $userService)
    {

        $groupedUsers = $userService->getGroupedUsers();
        $groupedDestinations = $userService->getGroupedDestinations();

        //dd($groupedDestinations);

        return view('user_destination_registration', compact(
        'groupedUsers',
        'groupedDestinations'
        ));
    }
    //----------------------------------------------------------------------
    //登録
    public function store(Request $request)
    {
        DB::table('user_destination_records')->insert([
            'user' => $request->user_name,
            'destination' => $request->destination,
            'pickup_location' => $request->pickup_location,
            'dialysis' => $request->dialysis ?? 0,
            'transport_fee' => $request->transport_fee ?? 0,
            'distance' => $request->distance ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 登録処理
        return redirect()->route('dashboard', ['success' => 'insert']);
    }
    //----------------------------------------------------------------------
    //更新
public function update(Request $request, $id)
{

    //dd($id); //idちゃんと入ってる

    //dd($request->all());

    /*dd(
        $id,
        DB::table('user_destination_records')
            ->where('id', $id)
            ->first()
    );*/


    DB::table('user_destination_records')
        ->where('id', $id)
        ->update([

            'user' => $request->user,

            'destination' => $request->destination,

            'pickup_location' => $request->pickup_location,

            'dialysis' => (int)$request->input('dialysis_'.$id, 0),

            'distance' => $request->distance,

            'transport_fee' => (int)$request->input('transport_fee_'.$id, 0),

            'updated_at' => now(),

        ]);

    return back();
}
    //----------------------------------------------------------------------
    //削除
    public function delete($id)
    {
        DB::table('user_destination_records')
            ->where('id', $id)
            ->delete();

        return redirect()->back();
    }

}