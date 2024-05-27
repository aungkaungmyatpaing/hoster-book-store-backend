<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateFavoriteRequest;
use App\Http\Requests\DeleteFavoriteRequest;
use App\Http\Resources\FavoriteResource;
use App\Services\FavoriteService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    use ApiResponse;

    private FavoriteService $favoriteService;

    public function __construct(FavoriteService $favoriteService)
    {
        $this->favoriteService = $favoriteService;
    }

    public function createFav(CreateFavoriteRequest $request)
    {
        $this->favoriteService->createFav($request->validated());
        return $this->success("Add to fav successfully");
    }

    public function getFav()
    {
        $data = $this->favoriteService->getFav();
        return $this->success("Get Categories successfully", FavoriteResource::collection($data));
    }

    public function deleteFav(DeleteFavoriteRequest $request)
    {
        $this->favoriteService->deleteFav($request->validated());
        return $this->success("Remove from fav successfully");

    }

}
