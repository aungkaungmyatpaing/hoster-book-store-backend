<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentAccountResource;
use App\Http\Resources\PaymentResource;
use App\Services\PaymentService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use ApiResponse;

    private PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function getPaymentAccounts()
    {
        $data = $this->paymentService->getPaymentAccounts();
        return $this->success("Get PaymentAccounts successfully", PaymentAccountResource::collection($data));
    }

    public function getPayments()
    {
        $data = $this->paymentService->getPayments();
        return $this->success("Get Payments successfully", PaymentResource::collection($data));
    }

}
