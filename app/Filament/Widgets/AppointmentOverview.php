<?php

namespace App\Filament\Widgets;

use App\Models\Appointments;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AppointmentOverview extends BaseWidget
{
    
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $data = Appointments::query();
        
        if (isset(auth()->user()->roles[0]->name) && auth()->user()->roles[0]->name != "Administrator") {
            
            $totdata = $data->where('user_id', auth()->user()->id)
           ->count();
           $schedule = $data->where('status',"scheduled")->where('user_id', auth()->user()->id)->count();
            $completed = $data->where('status',"completed")->where('user_id', auth()->user()->id)->count();
            $canceled = $data->where('status',"canceled")->where('user_id', auth()->user()->id)->count();

        }else{
            $totdata = $data->count();
            $schedule = $data->where('status',"scheduled")->count();
            $completed = $data->where('status',"completed")->count();
            $canceled = $data->where('status',"canceled")->count();
        }
        
        return [
            Stat::make('Total Appointments', $totdata)
            ->color('success')
            ->chart([$totdata, $schedule, $canceled, $completed]),
            Stat::make('Total schedule',$schedule)
            ->color('info'),
            Stat::make('Total cancelled',$canceled)
            ->color('danger'),
            Stat::make('Total completed', $completed)
            ->color('amber'),
        ];
    }
}
