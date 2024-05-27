<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DioceseResource;
use App\Services\DioceseService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class DioceseController extends Controller
{
    use ApiResponse;

    private DioceseService $dioceseService;

    public function __construct(DioceseService $dioceseService)
    {
        $this->dioceseService = $dioceseService;
    }

    public function getDioceses()
    {
        $data = $this->dioceseService->getDioceses();
        return $this->success("Get Categories successfully", DioceseResource::collection($data));

    }
}
