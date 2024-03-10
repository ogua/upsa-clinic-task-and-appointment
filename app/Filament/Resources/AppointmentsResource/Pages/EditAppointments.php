<?php

namespace App\Filament\Resources\AppointmentsResource\Pages;

use App\Filament\Resources\AppointmentsResource;
use App\Models\Appointments;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAppointments extends EditRecord
{
    protected static string $resource = AppointmentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
{
    $data = $this->getRecord();
    $id = $data->id;
    $appointment_datetime = $data->appointment_datetime;
    $time = $data->appointment_time;

    $updated_date = $appointment_datetime." ".$time;

    Appointments::where('id',$id)->update(['appointment_datetime' => $updated_date]);
}


}
