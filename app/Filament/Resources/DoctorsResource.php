<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoctorsResource\Pages;
use App\Filament\Resources\DoctorsResource\RelationManagers;
use App\Models\Doctors;
use App\Models\Specialists;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DoctorsResource extends Resource
{
    protected static ?string $model = Doctors::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $slug = 'settings/doctors';
    //protected static ?string $navigationGroup = 'Settings';
    public static function getNavigationGroup(): string
    {
        return isset(auth()->user()->roles[0]->name) && auth()->user()->roles[0]->name == "Administrator" ? 'Settings' : '';
    }
    protected static ?string $navigationLabel = 'Doctor';
    protected static ?string $modelLabel = 'Doctor';
    protected static ?int $navigationSort = 4;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->orderBy('id','desc');   
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('')
                    ->description('')
                    ->schema([
                    

                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('specialty')
                    ->required()
                    ->options(Specialists::all()->pluck('name', 'id'))
                    ->searchable(),
                Forms\Components\TextInput::make('contact_number')
                    ->required()
                    ->tel(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),

                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('speciality.name')
                ->label('Speciality')
                ->badge()
                ->searchable(),
                Tables\Columns\TextColumn::make('contact_number')
                ->label('Contact number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
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
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListDoctors::route('/'),
            'create' => Pages\CreateDoctors::route('/create'),
            'edit' => Pages\EditDoctors::route('/{record}/edit'),
        ];
    }
}
