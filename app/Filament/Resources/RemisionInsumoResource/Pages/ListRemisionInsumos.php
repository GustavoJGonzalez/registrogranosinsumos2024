<?php

namespace App\Filament\Resources\RemisionInsumoResource\Pages;

use App\Filament\Resources\RemisionInsumoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRemisionInsumos extends ListRecords
{
    protected static string $resource = RemisionInsumoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
