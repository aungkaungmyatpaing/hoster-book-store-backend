<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->action(function ($data, $record){

                if ($record->articles()->count() > 0) {
                    Notification::make()
                        ->danger()
                        ->title('Category is in use')
                        ->body('The Category is in use by article')
                        ->send();

                    return;
                }

                Notification::make()
                    ->success()
                    ->title('Category deleted')
                    ->body('The Category data has been deleted')
                    ->send();

                $record->delete();
                redirect('/admin/categories');

            })
        ];
    }
}
