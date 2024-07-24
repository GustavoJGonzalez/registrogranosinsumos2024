<?php

namespace App\Filament\Resources\ChoferResource\Pages;

use App\Filament\Resources\ChoferResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChofer extends EditRecord
{
    protected static string $resource = ChoferResource::class;

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
