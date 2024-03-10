<?php

namespace App\Services;

use App\Models\Appointments;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ReservationService{

    public function getAvailableTimesForDate(string $date, int $doctorid): array
    {
        $date                  = Carbon::parse($date);
        $startPeriod           = $date->copy()->hour(8);
        $endPeriod             = $date->copy()->hour(16);
        $times                 = CarbonPeriod::create($startPeriod, '1 hour', $endPeriod);
        $availableReservations = [];

        $appointments = Appointments::where('doctor_id',$doctorid)
        ->whereBetween('appointment_datetime', [$startPeriod, $endPeriod])
        ->pluck('appointment_datetime')
        ->toArray();
    
        $availableTimes = $times->copy()->filter(function ($time) use ($appointments) {
            return ! in_array($time, $appointments);
        })->toArray();

        foreach ($availableTimes as $time) {
            $key                         = $time->format('H:i');
            $availableReservations[$key] = $time->format('H:i');
        }

        return $availableReservations;
    }

}