<?php

namespace App\Filament\Resources\RecepcionInsumoResource\Pages;

use App\Filament\Resources\RecepcionInsumoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRecepcionInsumo extends ViewRecord
{
    protected static string $resource = RecepcionInsumoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
