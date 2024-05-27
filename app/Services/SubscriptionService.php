<?php

namespace App\Services;

use App\Exceptions\CreateDataFailException;
use App\Exceptions\ResourceForbiddenException;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\SubscriptionMonth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SubscriptionService
{
    public function createSubscription($filter)
    {
        $monthIds = json_decode($filter['month'], true);
        $price = Payment::first();
        if(!$price)
        {
            throw new ResourceForbiddenException('Subscription failed, price not found');
        }
        DB::beginTransaction();
        try {

            $subscriptions = Subscription::create([
                'user_id' => $filter['user'],
                'year_id' => $filter['year'],
                'months' => $filter['month'],
                'payment_account_id' => $filter['payment_account'],
            ]);
            try {
                $subscriptions->addMedia($filter['slip'])
                    ->toMediaCollection('payment-slip');
            } catch (\Exception $e) {
                throw new CreateDataFailException('Subscription failed, slip upload failed');
            }
            if ($subscriptions) {
                $totalPrice = 0;
                foreach ($monthIds as $month) {
                    $totalPrice += $price->price;
                    SubscriptionMonth::create([
                        'subscription_id' => $subscriptions->id,
                        'month_id'  => $month
                    ]);
                };
                $subscriptions->price = $totalPrice;
                $subscriptions->save();
            }
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new CreateDataFailException('Subscription failed');
        }

    }

}
