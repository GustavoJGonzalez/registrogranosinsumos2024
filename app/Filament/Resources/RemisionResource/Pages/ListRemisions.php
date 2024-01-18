<?php

namespace App\Filament\Resources\RemisionResource\Pages;

use App\Filament\Resources\RemisionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRemisions extends ListRecords
{
    protected static string $resource = RemisionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
