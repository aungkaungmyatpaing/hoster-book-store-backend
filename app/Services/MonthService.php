<?php

namespace App\Services;

use App\Models\Month;

class MonthService

{
    public function getMonths($filter)
    {
        $query = Month::query()
            ->where('publish', true)
            ->when(isset($filter['year']), function ($q) use ($filter) {
                $q->where('year_id', $filter['year']);
            })
            ->when(isset($filter['keyword']), function ($q) use ($filter) {
                $q->where(function ($query) use ($filter) {
                    $query->where('name', 'like', '%' . $filter['keyword'] . '%');
                });
            });

        $perPage = $filter['limit'] ?? 20;
        $data = $query->paginate($perPage);

        return $data;
    }
}
