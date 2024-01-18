<?php

namespace App\Filament\Resources\CatgoriaProductoResource\Pages;

use App\Filament\Resources\CatgoriaProductoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCatgoriaProducto extends EditRecord
{
    protected static string $resource = CatgoriaProductoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
