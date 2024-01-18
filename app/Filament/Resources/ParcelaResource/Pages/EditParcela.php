<?php

namespace App\Filament\Resources\ParcelaResource\Pages;

use App\Filament\Resources\ParcelaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditParcela extends EditRecord
{
    protected static string $resource = ParcelaResource::class;

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
