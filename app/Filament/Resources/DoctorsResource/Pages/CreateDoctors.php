<?php

namespace App\Filament\Resources\DoctorsResource\Pages;

use App\Filament\Resources\DoctorsResource;
use App\Models\Doctors;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateDoctors extends CreateRecord
{
    protected static string $resource = DoctorsResource::class;
    
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

        $email = $data->email;
        $name = $data->last_name." ".$data->first_name;

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        $user->assignRole('Doctor');

        $doctor = Doctors::where('id',$data->id)->first();
        $doctor->user_id = $user->id;
        $doctor->save();

    }
}
