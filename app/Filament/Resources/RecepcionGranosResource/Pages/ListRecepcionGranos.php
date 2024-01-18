<?php

namespace App\Filament\Resources\RecepcionGranosResource\Pages;

use App\Filament\Resources\RecepcionGranosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRecepcionGranos extends ListRecords
{
    protected static string $resource = RecepcionGranosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
