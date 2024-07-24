<?php

namespace App\Filament\Resources\RemisionInsumoResource\Pages;

use App\Filament\Resources\RemisionInsumoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRemisionInsumo extends EditRecord
{
    protected static string $resource = RemisionInsumoResource::class;

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
