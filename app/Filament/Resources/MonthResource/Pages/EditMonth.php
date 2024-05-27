<?php

namespace App\Filament\Resources\MonthResource\Pages;

use App\Filament\Resources\MonthResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditMonth extends EditRecord
{
    protected static string $resource = MonthResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->action(function ($data, $record){

                if ($record->categories()->count() > 0) {
                    Notification::make()
                        ->danger()
                        ->title('Month is in use')
                        ->body('The Month is in use by category')
                        ->send();

                    return;
                }

                if ($record->articles()->count() > 0) {
                    Notification::make()
                        ->danger()
                        ->title('Month is in use')
                        ->body('The Month is in use by article')
                        ->send();

                    return;
                }

                Notification::make()
                    ->success()
                    ->title('Month deleted')
                    ->body('The Month data has been deleted')
                    ->send();

                $record->delete();
                redirect('/admin/months');

            })
        ];
    }
}
