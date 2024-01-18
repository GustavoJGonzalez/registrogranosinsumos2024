<?php

namespace App\Filament\Resources\EmpresaClienteResource\Pages;

use App\Filament\Resources\EmpresaClienteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmpresaCliente extends EditRecord
{
    protected static string $resource = EmpresaClienteResource::class;

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
