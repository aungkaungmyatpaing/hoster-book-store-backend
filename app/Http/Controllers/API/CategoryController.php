<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetCategoryRequest;
use App\Http\Resources\ArticleDisplayCategoryResource;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponse;

    private CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function getCategories(GetCategoryRequest $request)
    {
        $data = $this->categoryService->getCategories($request->validated());
        if ($request->has('article_limit')) {
            return $this->success("Get Categories successfully", ArticleDisplayCategoryResource::collection($data));
        }else{
            return $this->success("Get Categories successfully", CategoryResource::collection($data));
        }
    }

    public function homePageCategories()
    {
        $data = $this->categoryService->homePageCategories();
        return $this->success("Get Categories successfully", CategoryResource::collection($data));

    }
}
