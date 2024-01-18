<?php

namespace App\Filament\Resources\RemisionResource\Pages;

use App\Filament\Resources\RemisionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRemision extends ViewRecord
{
    protected static string $resource = RemisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
