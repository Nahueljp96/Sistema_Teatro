<?php

namespace App\Filament\Resources\AltaResource\Pages;

use App\Filament\Resources\AltaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAltas extends ListRecords
{
    protected static string $resource = AltaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
