<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\PaymentAccount;

class PaymentService
{
    public function getPaymentAccounts()
    {
        $paymentAccounts = PaymentAccount::all();

        return $paymentAccounts;
    }

    public function getPayments()
    {
        $payments = Payment::all();

        return $payments;
    }

}
