<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UserOverview extends BaseWidget
{
    
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                ->label('Role')
                ->badge()
                ->searchable()
            ]);
    }

    public static function canView(): bool 
    {
        return isset(auth()->user()->roles[0]->name) && auth()->user()->roles[0]->name == "Administrator" ? true : false;;
    }

}
