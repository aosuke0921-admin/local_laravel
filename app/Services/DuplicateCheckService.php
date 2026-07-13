<?php
namespace App\Services;

class DuplicateCheckService
{
    public function findDuplicates($data)
    {
        return $data->groupBy(function ($item) {
            return json_encode($item->toArray());
        })
        ->filter(function ($group) {
            return $group->count() > 1;
        });
    }
}