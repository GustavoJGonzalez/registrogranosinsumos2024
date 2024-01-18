<?php

namespace App\Filament\Resources\EmpresaClienteResource\Pages;

use App\Filament\Resources\EmpresaClienteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmpresaClientes extends ListRecords
{
    protected static string $resource = EmpresaClienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
