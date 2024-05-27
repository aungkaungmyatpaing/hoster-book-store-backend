<?php

namespace App\Filament\Resources\PaymentAccountResource\Pages;

use App\Filament\Resources\PaymentAccountResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPaymentAccount extends EditRecord
{
    protected static string $resource = PaymentAccountResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->action(function ($data, $record){

                if ($record->subscriptions()->count() > 0) {
                    Notification::make()
                        ->danger()
                        ->title('Payment account is in use')
                        ->body('The Payment is in use by subscriptions')
                        ->send();

                    return;
                }


                if ($record->histories()->count() > 0) {
                    Notification::make()
                        ->danger()
                        ->title('Payment account is in use')
                        ->body('The Payment is in use by histories')
                        ->send();

                    return;
                }

                Notification::make()
                    ->success()
                    ->title('Month deleted')
                    ->body('The Month data has been deleted')
                    ->send();

                $record->delete();
            })
        ];
    }
}
