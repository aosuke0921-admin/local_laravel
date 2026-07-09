<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use App\Models\SmileYoyaku;
use App\Models\SmileCancel;
use App\Models\Customer;
use App\Services\NotificationService;

class BoardingReservationController extends Controller
{
    // =========================
    // 一覧・登録画面
    // =========================
    public function index(Request $request, UserService $service)
    {

////////////////////////////////////////////////////////////
/*
        $userId = auth()->id();
        $this->badgeService->reset($userId);
*/
////////////////////////////////////////////////////////////






        
        $members = \App\Models\User::pluck('full_name')->toArray();

        $records = $service->getAllRecords();

        // ★統一（queryではなくinputに寄せる運用でもOKだが一旦維持）
        $mode = $request->query('mode');

        $selects = [
            ['start' => 2026, 'end' => 2030, 'label' => '年', 'step' => 1],
            ['start' => 1, 'end' => 12, 'label' => '月', 'step' => 1],
            ['start' => 1, 'end' => 31, 'label' => '日', 'step' => 1],
            ['start' => 7, 'end' => 19, 'label' => '時', 'step' => 1],
            ['start' => 0, 'end' => 55, 'label' => '分', 'step' => 5],
        ];

        $groupedUsers = $service->getGroupedUsers();

        return view('boarding_reservation', compact(
            'groupedUsers',
            'records',
            'selects',
            'mode',
            'members',
        ));
    }

    // =========================
    // 登録
    // =========================



    //public function store(Request $request)



    public function store(Request $request, NotificationService $notification)
    {

        //dd($request->all());

        // ★ここが重要：mode統一
        $action = $request->input('submit_value'); // ←修正ポイント

        //dd($action);

        $ymd = $request->ymdselect ?? [];

        // =========================
        // 支援（キャンセル）
        // =========================
        if ($action === 'cancel') {

            $cancelDate = null;

            if (is_array($ymd) && count($ymd) >= 3) {
                $cancelDate = sprintf('%04d-%02d-%02d', $ymd[0], $ymd[1], $ymd[2]);
            }

            SmileCancel::create([
                'user'         => $request->user_name,
                'destination'  => $request->destination, // disabledならnullOK前提
                'cancel_date'  => $cancelDate,

                'client_name'  => $request->client_name,
                'receptionist' => $request->receptionist,
                'input_date'   => now(),

                'attention'    => $request->attention,
                'remarks_txt'  => $request->remarks_txt,
                'place'        => $request->place,
            ]);

    //追加6.10
    //app(\App\Services\NotificationService::class)->send(
        $notification->send(
            '❌ キャンセル',
            "日付: {$ymd[0]}年{$ymd[1]}月{$ymd[2]}日\n"
            . "利用者: {$request->user_name}さん"
            . (!empty($request->destination)
                ? "\n行き先: {$request->destination}"
                : '')
        );
        /*
        $notification->send(
            'キャンセル受付',
            'キャンセル受付が登録されました'
        );
        */
        // =========================
        // 乗降予約
        // =========================
        } else {

            if (!is_array($ymd) || count($ymd) < 5) {
                return back()->with('error', '日時が不足しています');
            }

            $reservationDatetime = sprintf(
                '%04d-%02d-%02d %02d:%02d:00',
                $ymd[0],
                $ymd[1],
                $ymd[2],
                $ymd[3],
                $ymd[4]
            );

            SmileYoyaku::create([
                'user'                 => $request->user_name,
                'destination'          => $request->destination,
                'reservation_datetime' => $reservationDatetime,

                'client_name'          => $request->client_name,
                'receptionist'         => $request->receptionist,
                'input_date'           => now(),

                'attention'            => $request->attention,
                'remarks_txt'          => $request->remarks_txt,
                'place'                => $request->place,

                'is_reflected'         => 0,
            ]);



            //追加6.10
            //app(\App\Services\NotificationService::class)->send(
            $notification->send(
                '📝 乗降予約',
                "日付: {$ymd[0]}年{$ymd[1]}月{$ymd[2]}日\n"
                . "時刻: {$ymd[3]}時{$ymd[4]}分\n"
                . "利用者: {$request->user_name}さん\n"
                . "行き先: {$request->destination}"
            );

        }

        return redirect()->route('dashboard', [
            'success' => 'insert'
        ]);
    }

    // =========================
    // 反映
    // =========================
    public function reflect($id)
    {
        $yoyaku = SmileYoyaku::findOrFail($id);

        $yoyaku->timestamps = false;

        $yoyaku->update([
            'is_reflected' => 1,
            'reflected_at' => now(),
            'reflected_by' => auth()->user()->full_name ?? null,
        ]);

        return back()->with('success', '反映しました');
    }

    // =========================
    // 削除
    // =========================
    public function destroy($id)
    {
        $yoyaku = SmileYoyaku::findOrFail($id);
        $yoyaku->delete();

        return back()->with('success', '削除しました');
    }

    // =========================
    // 編集
    // =========================
public function edit(Request $request, $id, UserService $service)
{

    $members = \App\Models\User::pluck('full_name')->toArray();

    $mode = $request->query('mode', 'boarding');
    $isSupport = ($mode === 'support');

    // 編集対象データ
    $data = $isSupport
        ? SmileCancel::findOrFail($id)
        : SmileYoyaku::findOrFail($id);

    // 利用者グループ
    $groupedUsers = $service->getGroupedUsers();

    /*
    ======================================================
    ★ ここ重要（行き先マスタはこれだけ使う）
    ======================================================
    */
    $user_destination_records = $service->getAllRecords();

    /*
    ======================================================
    ★ 予約/キャンセル履歴（これは別用途）
    ※JSの行き先生成には使わない
    ======================================================
    */
    $records = $isSupport
        ? SmileCancel::all()
        : SmileYoyaku::all();

    // 日付セレクト
    $selects = [
        ['start' => 2026, 'end' => 2030, 'label' => '年', 'step' => 1],
        ['start' => 1, 'end' => 12, 'label' => '月', 'step' => 1],
        ['start' => 1, 'end' => 31, 'label' => '日', 'step' => 1],
        ['start' => 7, 'end' => 19, 'label' => '時', 'step' => 1],
        ['start' => 0, 'end' => 55, 'label' => '分', 'step' => 5],
    ];

    return view('edit', compact(
        'data',
        'groupedUsers',
        'user_destination_records', // ★これ追加
        'records',
        'selects',
        'mode',
        'isSupport',
        'members'
    ));
}

    // =========================
    // 更新
    // =========================
    public function update(Request $request, $id)
    {
        //$mode = $request->query('mode', 'boarding');

        $mode = $request->input('mode', 'boarding'); // 2026.7.9


        $isSupport = ($mode === 'support');

        $ymd = $request->ymdselect ?? [];

        // =========================
        // キャンセル
        // =========================
        if ($isSupport) {

            $data = SmileCancel::findOrFail($id);

            $cancelDate = null;

            if (is_array($ymd) && count($ymd) >= 3) {
                $cancelDate = sprintf('%04d-%02d-%02d', $ymd[0], $ymd[1], $ymd[2]);
            }

            $data->update([
                'user'         => $request->user_name,
                'destination'  => $request->destination,
                'cancel_date'  => $cancelDate,
                'client_name'  => $request->client_name,
                'receptionist' => $request->receptionist,
                'place'        => $request->place,
                'attention'    => $request->attention,
                'remarks_txt'  => $request->remarks_txt,
            ]);

        // =========================
        // 乗降予約
        // =========================
        } else {

            $data = SmileYoyaku::findOrFail($id);

            $reservationDatetime = null;

            if (is_array($ymd) && count($ymd) >= 5) {
                $reservationDatetime = sprintf(
                    '%04d-%02d-%02d %02d:%02d:00',
                    $ymd[0],
                    $ymd[1],
                    $ymd[2],
                    $ymd[3],
                    $ymd[4]
                );
            }

            // =========================
            // before（DB側）
            // =========================
            $before = [
                'user'                 => $data->user,
                'destination'          => $data->destination,
                'reservation_datetime' => optional($data->reservation_datetime)->format('Y-m-d H:i:s'),
                'client_name'          => $data->client_name,
                'receptionist'         => $data->receptionist,
                'place'                => $data->place,
                'attention'            => $data->attention,
                'remarks_txt'          => $data->remarks_txt,
            ];

            // =========================
            // after（入力値）
            // =========================
            $after = [
                'user'                 => $request->user_name,
                'destination'          => $request->destination,
                'reservation_datetime' => $reservationDatetime ?? $data->reservation_datetime,
                'client_name'          => $request->client_name,
                'receptionist'         => $request->receptionist,
                'place'                => $request->place,
                'attention'            => $request->attention,
                'remarks_txt'          => $request->remarks_txt,
            ];

            // =========================
            // 変更チェック
            // =========================
            $isChanged = json_encode($before) !== json_encode($after);

            // =========================
            // 更新
            // =========================
            // 変更があった場合だけ編集日時を保存
            if ($isChanged) {
                $after['edited_at'] = now();

                $after['is_reflected'] = 0;
                $after['reflected_at'] = null;
            }

            // 更新
            $data->update($after);
        }

        return redirect()->route('reservation_search.page', [
            'mode' => $mode,
            'year_select' => $request->year_select, // ←2026.7.9追加
            'month_select' => $request->month_select, // ←2026.7.9追加
        ]);
    }
}