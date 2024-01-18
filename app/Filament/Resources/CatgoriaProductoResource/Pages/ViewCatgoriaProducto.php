<?php

namespace App\Filament\Resources\CatgoriaProductoResource\Pages;

use App\Filament\Resources\CatgoriaProductoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCatgoriaProducto extends ViewRecord
{
    protected static string $resource = CatgoriaProductoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
