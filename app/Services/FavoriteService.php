<?php

namespace App\Services;

use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoriteService
{
    public function createFav($filter)
    {
        $fav = Favorite::create([
            'user_id' => $filter['user'],
            'article_id' => $filter['article']
        ]);

        return $fav;
    }

    public function getFav()
    {
        $user = Auth::guard('user')->user();

        $data = Favorite::where('user_id', $user->id)->get();

        return $data;
    }

    public function deleteFav($filter)
    {
        $data = Favorite::find($filter['favorite']);

        $data->delete();

        return true;
    }
}
