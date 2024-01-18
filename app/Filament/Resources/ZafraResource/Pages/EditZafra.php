<?php

namespace App\Filament\Resources\ZafraResource\Pages;

use App\Filament\Resources\ZafraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditZafra extends EditRecord
{
    protected static string $resource = ZafraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
