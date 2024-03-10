<?php

namespace App\Filament\Resources\MedicaltaskResource\Pages;

use App\Filament\Resources\MedicaltaskResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMedicaltasks extends ListRecords
{
    protected static string $resource = MedicaltaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
