<?php

namespace App\Filament\Resources\ZafraResource\Pages;

use App\Filament\Resources\ZafraResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewZafra extends ViewRecord
{
    protected static string $resource = ZafraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
