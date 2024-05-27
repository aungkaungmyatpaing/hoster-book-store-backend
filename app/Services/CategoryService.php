<?php

namespace App\Services;

use App\Models\Category;
use Carbon\Carbon;

class CategoryService
{
    public function getCategories($filter)
    {
        $query = Category::query()
            ->when(isset($filter['year']), function ($q) use ($filter) {
                $q->where('year_id', $filter['year']);
            })
            ->when(isset($filter['month']), function ($q) use ($filter) {
                $q->where('month_id', $filter['month']);
            })
            ->when(isset($filter['keyword']), function ($q) use ($filter) {
                $q->where(function ($query) use ($filter) {
                    $query->where('name', 'like', '%' . $filter['keyword'] . '%');
                });
            })
            ->whereHas('month', function ($q) {
                $q->where('publish', true);
            });

        $perPage = $filter['limit'] ?? 20;
        $data = $query->paginate($perPage);

        return $data;
    }

    public function homePageCategories()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        return Category::whereHas('year', function ($query) use ($currentYear) {
            $query->where('year', $currentYear);
        })
        ->whereHas('month', function ($query) use ($currentMonth) {
            $query->where('month', $currentMonth)->where('publish', true);
        })
        ->orderBy('created_at', 'desc')
        ->paginate(9);
    }
}
