<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetMonthRequest;
use App\Http\Resources\MonthResource;
use App\Services\MonthService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class MonthController extends Controller
{
    use ApiResponse;

    private MonthService $monthService;

    public function __construct(MonthService $monthService)
    {
        $this->monthService = $monthService;
    }

    public function getMonths(GetMonthRequest $request)
    {
        $data = $this->monthService->getMonths($request->validated());
        return $this->success("Get Months successfully", MonthResource::collection($data));
    }
}
