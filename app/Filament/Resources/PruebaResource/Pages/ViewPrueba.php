<?php

namespace App\Filament\Resources\PruebaResource\Pages;

use App\Filament\Resources\PruebaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPrueba extends ViewRecord
{
    protected static string $resource = PruebaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
