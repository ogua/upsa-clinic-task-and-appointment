<?php

namespace App\Filament\Resources\StudentsResource\Pages;

use App\Filament\Resources\StudentsResource;
use App\Models\Patients;
use App\Models\Students;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateStudents extends CreateRecord
{
    protected static string $resource = StudentsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['admitted'] = date("y-m-d");
        $data['status'] = true;
        $data['enrol_id'] = uniqid();
        $data['indexnumber'] = "101".rand(0,9999);
    
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function getCreatedNotificationTitle(): ?string
    {
        return 'User registered';
    }


    protected function afterCreate(): void
    {
        $data = $this->getRecord();

        $index = $data->indexnumber;
        $name = $data->last_name." ".$data->first_name;
        $email = $data->email;

        $user = User::create([
            'name' => $name,
            'email' => $index,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        $user->assignRole('Student');

        $student = Students::where('id',$data->id)->first();
        $student->user_id = $user->id;
        $student->currentlevel = $student->entrylevel;
        $student->save();

        $updatepatient = Patients::where('email',$email)->first();
        $updatepatient->user_id = $user->id;
        $updatepatient->save();

    }


}
