<?php

namespace App\Filament\Resources\ParcelaResource\Pages;

use App\Filament\Resources\ParcelaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListParcelas extends ListRecords
{
    protected static string $resource = ParcelaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
