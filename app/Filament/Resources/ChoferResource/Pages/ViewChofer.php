<?php

namespace App\Filament\Resources\ChoferResource\Pages;

use App\Filament\Resources\ChoferResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewChofer extends ViewRecord
{
    protected static string $resource = ChoferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
