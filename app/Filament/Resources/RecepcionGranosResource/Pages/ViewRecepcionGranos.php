<?php

namespace App\Filament\Resources\RecepcionGranosResource\Pages;

use App\Filament\Resources\RecepcionGranosResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRecepcionGranos extends ViewRecord
{
    protected static string $resource = RecepcionGranosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
