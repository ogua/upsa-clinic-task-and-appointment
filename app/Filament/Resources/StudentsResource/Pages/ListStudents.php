<?php

namespace App\Filament\Resources\StudentsResource\Pages;

use App\Filament\Resources\StudentsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListStudents extends ListRecords
{
    protected static string $resource = StudentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
{
    return [
        'all' => Tab::make('All'),
        'Level 100' => Tab::make('Level 100')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('entrylevel', "Level 100")),
            'Level 200' => Tab::make('Level 200')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('entrylevel', "Level 200")),
            'Level 300' => Tab::make('Level 300')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('entrylevel', "Level 300")),
            'Level 400' => Tab::make('Level 400')
            ->modifyQueryUsing(fn (Builder $query) => $query->where('entrylevel', "Level 400")),
    ];
}
}
