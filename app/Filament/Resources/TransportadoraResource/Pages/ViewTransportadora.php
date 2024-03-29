<?php

namespace App\Filament\Resources\TransportadoraResource\Pages;

use App\Filament\Resources\TransportadoraResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTransportadora extends ViewRecord
{
    protected static string $resource = TransportadoraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
