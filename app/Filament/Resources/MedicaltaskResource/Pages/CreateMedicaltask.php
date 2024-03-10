<?php

namespace App\Filament\Resources\MedicaltaskResource\Pages;

use App\Filament\Resources\MedicaltaskResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMedicaltask extends CreateRecord
{
    protected static string $resource = MedicaltaskResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['doctor_id'] = auth()->user()->id;
    
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
