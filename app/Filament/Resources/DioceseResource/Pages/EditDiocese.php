<?php

namespace App\Filament\Resources\DioceseResource\Pages;

use App\Filament\Resources\DioceseResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditDiocese extends EditRecord
{
    protected static string $resource = DioceseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->action(function ($data, $record){

                    if ($record->users()->count() > 0) {
                        Notification::make()
                            ->danger()
                            ->title('Diocese is in use')
                            ->body('The Diocese is in use by users')
                            ->send();

                        return;
                    }

                    Notification::make()
                        ->success()
                        ->title('Diocese deleted')
                        ->body('The Diocese data has been deleted')
                        ->send();

                    $record->delete();
                })
        ];
    }
}
