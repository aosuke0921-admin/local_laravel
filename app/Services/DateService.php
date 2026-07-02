<?php

namespace App\Services;

use Carbon\Carbon;

class DateService
{
    public function moveDate(string $date, ?string $move): Carbon
    {
        $date = Carbon::parse($date);

        switch ($move) {
            case '-1d':
                $date->subDay();
                break;

            case '1d':
                $date->addDay();
                break;

            case '-1m':
                $date->subMonth();
                break;

            case '1m':
                $date->addMonth();
                break;
        }

        return $date;
    }
}