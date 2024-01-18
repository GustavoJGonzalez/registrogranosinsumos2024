<?php

namespace App\Filament\Resources\RecepcionGranosResource\Pages;

use App\Filament\Resources\RecepcionGranosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecepcionGranos extends EditRecord
{
    protected static string $resource = RecepcionGranosResource::class;

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
