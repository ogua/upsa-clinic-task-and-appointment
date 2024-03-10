<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpecialistsResource\Pages;
use App\Filament\Resources\SpecialistsResource\RelationManagers;
use App\Models\Specialists;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SpecialistsResource extends Resource
{
    protected static ?string $model = Specialists::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $slug = 'settings/specialists';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Specialist';
    protected static ?string $modelLabel = 'Specialist';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSpecialists::route('/'),
           // 'create' => Pages\CreateSpecialists::route('/create'),
           // 'edit' => Pages\EditSpecialists::route('/{record}/edit'),
        ];
    }
}
