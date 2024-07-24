<?php

namespace App\Filament\Resources\RemisionInsumoResource\Pages;

use App\Filament\Resources\RemisionInsumoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRemisionInsumo extends ViewRecord
{
    protected static string $resource = RemisionInsumoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
