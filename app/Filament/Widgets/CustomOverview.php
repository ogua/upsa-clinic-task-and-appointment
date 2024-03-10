<?php

namespace App\Filament\Widgets;

use App\Models\Doctors;
use App\Models\Patients;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CustomOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users',User::count())
            ->color('success'),
            Stat::make('Total Patients',Patients::count())
            ->color('info'),
            Stat::make('Total Doctors',Doctors::count())
            ->color('danger')
        ];
    }

    public static function canView(): bool 
    {
        return isset(auth()->user()->roles[0]->name) && auth()->user()->roles[0]->name == "Administrator" ? true : false;;
    } 
}
