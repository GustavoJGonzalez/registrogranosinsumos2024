<?php

namespace App\Filament\Resources\RecepcionInsumoResource\Pages;

use App\Filament\Resources\RecepcionInsumoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecepcionInsumo extends EditRecord
{
    protected static string $resource = RecepcionInsumoResource::class;

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
