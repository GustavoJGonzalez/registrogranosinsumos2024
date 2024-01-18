<?php

namespace App\Filament\Resources\RecepcionInsumoResource\Pages;

use App\Filament\Resources\RecepcionInsumoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRecepcionInsumos extends ListRecords
{
    protected static string $resource = RecepcionInsumoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
