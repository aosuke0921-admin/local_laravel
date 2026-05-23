<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SmileYoyaku;
use App\Models\SmileCancel;
use Carbon\Carbon;
use App\Services\UserService;

class ReservationController extends Controller
{

    public function index(Request $request, UserService $userService)
    {

        $mode = $request->query('mode', 'boarding');

        $cancel = ($mode === 'support');

        $year  = (int) $request->input('year_select', now()->year);
        $month = (int) $request->input('month_select', now()->month);

        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end   = Carbon::create($year, $month, 1)->endOfMonth();

        // =========================
        // メインデータ
        // =========================
        $query = $cancel
            ? SmileCancel::query()
            : SmileYoyaku::query();

        $query->whereBetween(
            $cancel ? 'cancel_date' : 'reservation_datetime',
            [$start, $end]
        );

        $user = $request->input('user_name_select');
        $sien = $request->input('sien_select');

        if (!empty($user)) {
            $query->where('user', $user);
        }

        if (!empty($sien) && $sien !== '選択してください') {
            $query->where('attention', $sien);
        }

        $query->with('customer');

        $data = $query
            ->orderBy($cancel ? 'cancel_date' : 'reservation_datetime')
            ->get();

        // =========================
        // attention（キャンセルは対象外）
        // =========================
        if (!$cancel) {

            $attentionsQuery = SmileYoyaku::query();

            $attentionsQuery->whereBetween(
                'reservation_datetime',
                [$start, $end]
            );

            if (!empty($user)) {
                $attentionsQuery->where('user', $user);
            }

            if (!empty($sien) && $sien !== '選択してください') {
                $attentionsQuery->where('attention', $sien);
            }

            $attentions = $attentionsQuery
                ->pluck('attention')
                ->filter()
                ->unique()
                ->values();

        } else {

            // キャンセルは空
            $attentions = collect();
        }

        // =========================
        // ユーザー
        // =========================
        $groupedUsers = $userService->getGroupedUsers();

        return view('reservation_search', compact(
            'data',
            'cancel',
            'attentions',
            'groupedUsers',
            'year',
            'month'
        ));
    }

    /*public function deleteCancel($id)
    {
        $data = SmileCancel::findOrFail($id);
        $data->delete();

        return redirect()->route('reservation_search.page', ['cancel' => true]);
    }*/

    public function deleteCancel(Request $request, $id)
    {
        $data = SmileCancel::findOrFail($id);
        $data->delete();

        //$mode = $request->mode;
        $mode = $request->input('mode');

        //dd($mode); //ここ

        if ($mode === 'support') {
            return redirect()->route('reservation_search.page', [
                'mode' => 'support'
            ]);
        }else{
            return redirect()->route('reservation_search.page');
        }
    }

    public function updateCancel(Request $request, $id)
    {
        $data = SmileCancel::findOrFail($id);

        // 日付は ymdselect から生成（storeと統一）
        $ymd = $request->ymdselect ?? [];

        $cancelDate = null;

        if (is_array($ymd) && count($ymd) >= 3) {
            $cancelDate = sprintf('%04d-%02d-%02d', $ymd[0], $ymd[1], $ymd[2]);
        }

        $data->update([
            'user' => $request->user_name,
            'destination' => $request->destination,
            'cancel_date' => $cancelDate,
            'client_name' => $request->client_name,
            'receptionist' => $request->receptionist,
            'attention' => $request->attention,
            'remarks_txt' => $request->remarks_txt,
            'place' => $request->place,
        ]);

        return redirect()->route('reservation_search.page', [
            'mode' => 'support'
        ]);
    }
}