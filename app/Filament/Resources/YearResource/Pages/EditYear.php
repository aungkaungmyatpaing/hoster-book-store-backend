<?php

namespace App\Filament\Resources\YearResource\Pages;

use App\Filament\Resources\YearResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditYear extends EditRecord
{
    protected static string $resource = YearResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->action(function ($data, $record){
                if ($record->months()->count() > 0) {
                    Notification::make()
                        ->danger()
                        ->title('Year is in use')
                        ->body('The Year is in use by month')
                        ->send();

                    return;
                }

                if ($record->categories()->count() > 0) {
                    Notification::make()
                        ->danger()
                        ->title('Year is in use')
                        ->body('The Year is in use by category')
                        ->send();

                    return;
                }

                if ($record->articles()->count() > 0) {
                    Notification::make()
                        ->danger()
                        ->title('Year is in use')
                        ->body('The Year is in use by article')
                        ->send();

                    return;
                }

                Notification::make()
                    ->success()
                    ->title('Year deleted')
                    ->body('The Year data has been deleted')
                    ->send();

                $record->delete();
                redirect('/admin/years');

            }),
        ];
    }
}
