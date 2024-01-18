<?php

namespace App\Filament\Resources\CatgoriaProductoResource\Pages;

use App\Filament\Resources\CatgoriaProductoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCatgoriaProductos extends ListRecords
{
    protected static string $resource = CatgoriaProductoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
