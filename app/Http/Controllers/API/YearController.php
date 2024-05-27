<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\YearResource;
use App\Services\YearService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class YearController extends Controller
{
    use ApiResponse;

    private YearService $yearService;

    public function __construct(YearService $yearService)
    {
        $this->yearService = $yearService;
    }

    public function getYears()
    {
        $data = $this->yearService->getYears();

        return $this->success("Get Years successfully", YearResource::collection($data));

    }
}
