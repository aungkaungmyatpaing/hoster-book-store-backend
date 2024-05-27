<?php

namespace App\Filament\Resources\SubscriptionResource\Pages;

use App\Filament\Resources\SubscriptionResource;
use App\Models\SubscriptionMonth;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateSubscription extends CreateRecord
{
    protected static string $resource = SubscriptionResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $subscription = static::getModel()::create($data);
        foreach($data['month_id'] as $id)
        {
            SubscriptionMonth::create([
                'subscription_id' => $subscription->id,
                'month_id' => $id
            ]);
        }
        return $subscription;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
