<?php

namespace App\Services;

use App\Models\Article;

class ArticleService
{

    public function getArticles($filter)
    {
        $query = Article::query()
            ->when(isset($filter['year']), function ($q) use ($filter) {
                $q->where('year_id', $filter['year']);
            })
            ->when(isset($filter['month']), function ($q) use ($filter) {
                $q->where('month_id', $filter['month']);
            })
            ->when(isset($filter['category']), function ($q) use ($filter) {
                $q->where('category_id', $filter['category']);
            })
            ->when(isset($filter['keyword']), function ($q) use ($filter) {
                $q->where(function ($query) use ($filter) {
                    $query->where('title', 'like', '%' . $filter['keyword'] . '%')
                        ->orWhereHas('category', function ($q) use ($filter) {
                            $q->where('name', 'like', '%' . $filter['keyword'] . '%');
                        })
                        ->orWhereHas('month', function ($q2) use ($filter) {
                            $q2->where('name', 'like', '%' . $filter['keyword'] . '%')
                                ->orWhere('mm_name', 'like', '%' . $filter['keyword'] . '%');
                        })
                        ->orWhereHas('year', function ($q3) use ($filter) {
                            $q3->where('name', 'like', '%' . $filter['keyword'] . '%')
                                ->orWhere('mm_name', 'like', '%' . $filter['keyword'] . '%');
                        });
                });
            })
            ->whereHas('month', function ($q) {
                $q->where('publish', true);
            })
            ->orderByDesc('created_at');

        $perPage = $filter['limit'] ?? 20;
        $data = $query->paginate($perPage);

        return $data;
    }

    public function articleDetail($filter)
    {
        $query = Article::find($filter['article']);
        return $query;
    }

}
