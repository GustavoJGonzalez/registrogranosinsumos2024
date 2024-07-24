<?php

namespace App\Filament\Resources\ControlAccesoResource\Pages;

use App\Filament\Resources\ControlAccesoResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewControlAcceso extends ViewRecord
{
    protected static string $resource = ControlAccesoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
