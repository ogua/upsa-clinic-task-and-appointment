<?php

namespace App\Filament\Resources\SpecialistsResource\Pages;

use App\Filament\Resources\SpecialistsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpecialists extends EditRecord
{
    protected static string $resource = SpecialistsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
