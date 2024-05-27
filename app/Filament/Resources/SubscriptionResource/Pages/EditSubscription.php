<?php

namespace App\Filament\Resources\SubscriptionResource\Pages;

use App\Filament\Resources\SubscriptionResource;
use App\Models\SubscriptionMonth;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditSubscription extends EditRecord
{
    protected static string $resource = SubscriptionResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if(empty($data['month_id'])){
            if ($record->subscriptionMonths->count() === 0 ) {
                Notification::make()
                    ->danger()
                    ->title('Empty months')
                    ->body('Please select months for user subscription')
                    ->send();

                return $record;
            }else{
                $record->update($data);
                return $record;
            }
        }else{
            $subMonths = SubscriptionMonth::where('subscription_id', $record->id)->get();
            if ($subMonths) {
                foreach ($subMonths as $key => $value) {
                    $value->delete();
                }
            }
            foreach($data['month_id'] as $id)
            {
                SubscriptionMonth::create([
                    'subscription_id' => $record->id,
                    'month_id' => $id
                ]);
            }
            $record->update($data);
            return $record;
        }

    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
