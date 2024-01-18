<?php

namespace App\Filament\Resources\RemisionResource\Pages;

use App\Filament\Resources\RemisionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRemision extends EditRecord
{
    protected static string $resource = RemisionResource::class;

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
