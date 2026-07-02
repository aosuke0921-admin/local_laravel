<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DateService;

class DateController extends Controller
{
    public function changeDate(Request $request, DateService $dateService)
    {
        $date = $dateService->moveDate(
            $request->input('dates'),
            $request->input('move')
        );

        $target = $request->input('redirect_to', 'preview');

        $route = $target === 'delete'
            ? 'delete.page'
            : 'preview.page';

        return redirect()->route($route, [
            'dates' => $date->format('Y-m-d')
        ]);
    }
}