<?php

namespace App\Filament\Resources\SpecialistsResource\Pages;

use App\Filament\Resources\SpecialistsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpecialists extends ListRecords
{
    protected static string $resource = SpecialistsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
