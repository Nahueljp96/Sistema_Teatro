<?php

namespace App\Filament\Resources\CursoVentaResource\Pages;

use App\Filament\Resources\CursoVentaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCursoVentas extends ListRecords
{
    protected static string $resource = CursoVentaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
