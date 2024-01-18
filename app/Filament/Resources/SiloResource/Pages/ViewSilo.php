<?php

namespace App\Filament\Resources\SiloResource\Pages;

use App\Filament\Resources\SiloResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSilo extends ViewRecord
{
    protected static string $resource = SiloResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
