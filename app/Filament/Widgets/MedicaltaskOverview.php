<?php

namespace App\Filament\Widgets;

use App\Models\Medicaltask;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MedicaltaskOverview extends BaseWidget
{
    
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $data = Medicaltask::query();
        
        if (isset(auth()->user()->roles[0]->name) && auth()->user()->roles[0]->name != "Administrator") {
            
            $totdata = $data->where('user_id', auth()->user()->id)
           ->count();
           $Pending = $data->where('status',"pending")->where('user_id', auth()->user()->id)->count();
            $inprogress = $data->where('status',"in-progress")->where('user_id', auth()->user()->id)->count();
            $completed = $data->where('status',"completed")->where('user_id', auth()->user()->id)->count();

        }else{
            $totdata = $data->count();
            $Pending = $data->where('status',"pending")->count();
            $inprogress = $data->where('status',"in-progress")->count();
            $completed = $data->where('status',"completed")->count();
        }
        
        return [
            Stat::make('Total Tasks', $totdata)
            ->color('success')
            ->chart([$totdata, $Pending, $completed, $inprogress]),
            Stat::make('Total Pending',$Pending)
            ->color('info'),
            Stat::make('Total In-progress',$inprogress)
            ->color('danger'),
            Stat::make('Total completed', $completed)
            ->color('amber'),
        ];
    }
}
