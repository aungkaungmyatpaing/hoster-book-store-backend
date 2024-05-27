<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleDetailRequest;
use App\Http\Requests\GetArticleRequest;
use App\Http\Resources\ArticleDetailResource;
use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    use ApiResponse;

    private ArticleService $articleService;

    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    public function getArticles(GetArticleRequest $request)
    {
        $data = $this->articleService->getArticles($request->validated());
        return $this->success("Get Articles successfully", ArticleResource::collection($data));
    }

    public function articleDetail(ArticleDetailRequest $request)
    {
        $data = $this->articleService->articleDetail($request->validated());
        return $this->success("Get Article Detail successfully", new ArticleDetailResource($data));
    }
}
