<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Models\SmilePost;
use App\Models\Customer;
use App\Models\Destination;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


use App\Models\UserDestinationRecord;


use App\Http\Requests\StoreMasterUserDestinationRequest;


class MasterController extends Controller
{

    // バリデーションコントローラからの返り値を取得
    /*public function userDestinationUpdate(StoreMasterUserDestinationRequest $request, $id)
    {
        $data = $request->validated();

        UserDestinationRecord::where('id', $id)->update($data);
    }*/

    //------------------------------------------------------------------------------
    // 初期表示
    //------------------------------------------------------------------------------
    public function index(UserService $userService)
    {
        $member = DB::table('users')->get();

        $groups = [
            'あ' => ['ア','イ','ウ','エ','オ'],
            'か' => ['カ','キ','ク','ケ','コ'],
            'さ' => ['サ','シ','ス','セ','ソ'],
            'た' => ['タ','チ','ツ','テ','ト'],
            'な' => ['ナ','ニ','ヌ','ネ','ノ'],
            'は' => ['ハ','ヒ','フ','ヘ','ホ'],
            'ま' => ['マ','ミ','ム','メ','モ'],
            'や' => ['ヤ','ユ','ヨ'],
            'ら' => ['ラ','リ','ル','レ','ロ'],
            'わ' => ['ワ','ヲ','ン'],
        ];

        $initial = 'あ';

        $user_destination_records = DB::table('user_destination_records as udr')
            ->leftJoin('customers as c', 'udr.user', '=', 'c.name')
            ->whereNotNull('c.kana')
            ->whereIn(DB::raw('LEFT(c.kana,1)'), $groups[$initial])
            ->select('udr.*')
            ->orderBy('c.kana', 'asc')
            ->get();



        $groupedUsers = $userService->getGroupedUsers();


        $groupedDestinations = $userService->getGroupedDestinations();//追記

        if (
            !session()->has('user_name') ||
            !session()->has('car') ||
            !session()->has('dates')
        ) {
            return redirect('/dashboard');
        }

        $user = trim(session('user_name'));
        $car  = trim(session('car'));
        $dates = session('dates');

        if (str_contains($dates, '年')) {
            $dates = str_replace(['年','月','日'], ['-','-',''], $dates);
        }

        $dates = Carbon::parse($dates)->format('Y-m-d');

        $existingCount = SmilePost::where('member', $user)
            ->where('dates', $dates)
            ->where('car', $car)
            ->count();

        $displayDate = Carbon::parse($dates)->format('Y年n月j日');

        $startIndex = $existingCount + 1;

        return view('master', compact(
            'groupedUsers',
            'startIndex',
            'existingCount',
            'user',
            'car',
            'dates',
            'displayDate',
            'member',
            'user_destination_records',
            'groupedDestinations'
        ));
    }

    //------------------------------------------------------------------------------
    // Ajax（五十音フィルタ）
    //------------------------------------------------------------------------------
public function getUsers(Request $request)
{
    $groups = [
        'あ' => ['ア','イ','ウ','エ','オ'],
        'か' => ['カ','キ','ク','ケ','コ'],
        'さ' => ['サ','シ','ス','セ','ソ'],
        'た' => ['タ','チ','ツ','テ','ト'],
        'な' => ['ナ','ニ','ヌ','ネ','ノ'],
        'は' => ['ハ','ヒ','フ','ヘ','ホ'],
        'ま' => ['マ','ミ','ム','メ','モ'],
        'や' => ['ヤ','ユ','ヨ'],
        'ら' => ['ラ','リ','ル','レ','ロ'],
        'わ' => ['ワ','ヲ','ン'],
    ];

    $initial = trim($request->initial);

    if (!isset($groups[$initial])) {
        return response()->json(['error' => 'invalid initial'], 200);
    }

$user_destination_records = DB::table('user_destination_records as udr')
    ->leftJoin('customers as c', 'udr.user', '=', 'c.name')
    ->whereNotNull('c.kana')
    ->whereIn(DB::raw('LEFT(c.kana,1)'), $groups[$initial])
    ->select('udr.*')
    ->orderBy('c.kana', 'asc')
    ->get();



    return view('master_parts.user_destination_rows', [
        'records' => $user_destination_records
    ]);
}

    //------------------------------------------------------------------------------
    // 社員登録
    //------------------------------------------------------------------------------
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required',
            'user_name' => 'required',
            'user_pass' => 'required',
        ]);

        DB::table('users')->insert([
            'full_name' => $request->full_name,
            'user_login' => $request->user_name,
            'password' => bcrypt($request->user_pass),
        ]);

        return redirect()->route('master.page');
    }

    //------------------------------------------------------------------------------
    // 社員更新
    //------------------------------------------------------------------------------
    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name'  => 'required',
            'user_login' => 'required',
        ]);

        $data = [
            'full_name'  => $request->full_name,
            'user_login' => $request->user_login,
        ];

        //dd($request->password);


        if (!empty($request->password)) {
            $data['password'] = bcrypt($request->password);
        }

        //dd($data);

        DB::table('users')
            ->where('id', $id)
            ->update($data);

        return redirect()->route('master.page');
    }

    //------------------------------------------------------------------------------
    // 社員削除
    //------------------------------------------------------------------------------
    public function destroy($id)
    {
        DB::table('users')
            ->where('id', $id)
            ->delete();

        return redirect()->route('master.page');
    }














    //------------------------------------------------------------------------------
    // 利用者更新
    //------------------------------------------------------------------------------
    public function customerUpdate(Request $request, $id)
    {

        //dd($id);

        Customer::where('id', $id)->update([

            'name' => $request->name,
            'kana' => $request->kana,
            'support_notes' => $request->support_notes,
            'classification' => $request->classification,
            'status' => $request->status,

        ]);

        //return redirect()->back();
        return redirect()->route('master.page', ['success' => 'master_update']);
    }

    //------------------------------------------------------------------------------
    // 利用者削除
    //------------------------------------------------------------------------------
    public function customerDelete($id)
    {
        Customer::where('id', $id)->delete();

        //return redirect()->back();
        return redirect()->route('master.page', ['success' => 'master_delete']);
    }


    //------------------------------------------------------------------------------
    // 行き先更新
    //------------------------------------------------------------------------------
    public function destinationUpdate(Request $request, $id)
    {

        Destination::where('id', $id)->update([

            'destination' => $request->destination,
            'destination_hurigana' => $request->destination_hurigana,

        ]);

        //return redirect()->back();
        return redirect()->route('master.page', ['success' => 'master_update']);
    }

    //------------------------------------------------------------------------------
    // 行き先削除
    //------------------------------------------------------------------------------
    public function destinationDelete($id)
    {
        Destination::where('id', $id)->delete();

        //return redirect()->back();
        return redirect()->route('master.page', ['success' => 'master_delete']);
    }


    //------------------------------------------------------------------------------
    // 利用者・行き先 更新
    //------------------------------------------------------------------------------
public function userDestinationUpdate(StoreMasterUserDestinationRequest $request, $id)
{
    $data = $request->validated();

    UserDestinationRecord::where('id', $id)->update([
        //'user'            => $data['user_name'],
        //'destination'     => $data['destination'],

        'user'        => $request->input("user_$id"),
        'destination' => $request->input("destination_$id"),

        'pickup_location' => $request->pickup_location, // ←ここはOK（UI項目なら）

        'dialysis'        => $request->input("dialysis_$id"),
        'transport_fee'   => $request->input("transport_fee_$id"),

        'distance'        => $request->distance,
    ]);

    return redirect()->route('master.page', ['success' => 'master_update']);
}

    //------------------------------------------------------------------------------
    // 利用者・行き先 削除
    //------------------------------------------------------------------------------
    public function userDestinationDelete($id)
    {
        UserDestinationRecord::where('id', $id)->delete();

        //return redirect()->back();
        return redirect()->route('master.page', ['success' => 'master_delete']);
    }

















    //------------------------------------------------------------------------------
    // ラジオボタン切替 Ajax
    //------------------------------------------------------------------------------
    public function changeMode(Request $request)
    {
        $mode = $request->mode;

        $initial = $request->initial ?? 'あ';

        $groups = [
            'あ' => ['ア','イ','ウ','エ','オ'],
            'か' => ['カ','キ','ク','ケ','コ'],
            'さ' => ['サ','シ','ス','セ','ソ'],
            'た' => ['タ','チ','ツ','テ','ト'],
            'な' => ['ナ','ニ','ヌ','ネ','ノ'],
            'は' => ['ハ','ヒ','フ','ヘ','ホ'],
            'ま' => ['マ','ミ','ム','メ','モ'],
            'や' => ['ヤ','ユ','ヨ'],
            'ら' => ['ラ','リ','ル','レ','ロ'],
            'わ' => ['ワ','ヲ','ン'],
        ];

        if ($mode === 'customers') {

            //$records = Customer::all();


            $records = Customer::whereNotNull('kana')
                ->whereIn(DB::raw('LEFT(kana,1)'), $groups[$initial])
                ->orderBy('kana', 'asc')
                ->get();

            return response()->json([
                'head' => view('master_parts.customer_th')->render(),
                'body' => view('master_parts.customer_rows', compact('records'))->render(),
            ]);

        } elseif ($mode === 'destinations') {

            //$records = Destination::all();


            $records = Destination::whereNotNull('destination_hurigana')
                ->whereIn(DB::raw('LEFT(destination_hurigana,1)'), $groups[$initial])
                ->orderBy('destination_hurigana', 'asc')
                ->get();

            return response()->json([
                'head' => view('master_parts.destination_th')->render(),
                'body' => view('master_parts.destination_rows', compact('records'))->render(),
            ]);

        } else {

            $records = DB::table('user_destination_records as udr')
                ->leftJoin('customers as c', 'udr.user', '=', 'c.name')
                ->whereNotNull('c.kana')
                ->whereIn(DB::raw('LEFT(c.kana,1)'), $groups[$initial])
                ->select('udr.*')
                ->orderBy('c.kana', 'asc')
                ->get();

            return response()->json([
                'head' => view('master_parts.user_destination_th')->render(),
                'body' => view('master_parts.user_destination_rows', compact('records'))->render(),
            ]);

        }
    }
}