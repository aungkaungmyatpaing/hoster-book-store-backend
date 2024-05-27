<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->action(function ($data, $record){
                if ($record->histories()->count() > 0) {
                    Notification::make()
                        ->danger()
                        ->title('User is in use')
                        ->body('The User is in use by history')
                        ->send();

                    return;
                }

                Notification::make()
                    ->success()
                    ->title('User deleted')
                    ->body('The User data has been deleted')
                    ->send();

                $record->delete();
                redirect('/admin/users');

            })
        ];
    }
}
