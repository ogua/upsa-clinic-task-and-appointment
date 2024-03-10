<?php

namespace App\Filament\Resources\AppointmentsResource\Pages;

use App\Filament\Resources\AppointmentsResource;
use App\Models\Appointments;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAppointments extends CreateRecord
{
    protected static string $resource = AppointmentsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->user()->id;
    
        return $data;
    }

    protected function afterCreate(): void
{
    $data = $this->getRecord();
    $id = $data->id;
    $appointment_datetime = $data->appointment_datetime;
    $time = $data->appointment_time;

    $updated_date = $appointment_datetime." ".$time;

    Appointments::where('id',$id)->update(['appointment_datetime' => $updated_date]);

}

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
