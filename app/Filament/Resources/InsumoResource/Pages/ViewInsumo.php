<?php

namespace App\Filament\Resources\InsumoResource\Pages;

use App\Filament\Resources\InsumoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewInsumo extends ViewRecord
{
    protected static string $resource = InsumoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
