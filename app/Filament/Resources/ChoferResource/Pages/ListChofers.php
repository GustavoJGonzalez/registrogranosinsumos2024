<?php

namespace App\Filament\Resources\ChoferResource\Pages;

use App\Filament\Resources\ChoferResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChofers extends ListRecords
{
    protected static string $resource = ChoferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
