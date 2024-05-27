<?php

namespace App\Filament\Resources\DioceseResource\Pages;

use App\Filament\Resources\DioceseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDiocese extends CreateRecord
{
    protected static string $resource = DioceseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
