<?php

namespace App\Filament\Resources\DioceseResource\Pages;

use App\Filament\Resources\DioceseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDioceses extends ListRecords
{
    protected static string $resource = DioceseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
