<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentsResource\Pages;
use App\Filament\Resources\StudentsResource\RelationManagers;
use App\Models\Students;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentsResource extends Resource
{
    protected static ?string $model = Students::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $slug = 'settings/students';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'Student';
    protected static ?string $modelLabel = 'Student';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Forms\Components\Section::make('')
                        ->description('')
                        ->schema([ 
                            Forms\Components\Wizard::make([
                                Forms\Components\Wizard\Step::make('Personal Information')
                                    ->schema([
                                        
                                    ]),
                                    Forms\Components\Wizard\Step::make('Delivery')
                                    ->schema([
                                        
                                    ]),
                                    Forms\Components\Wizard\Step::make('Billing')
                                    ->schema([
                                        
                                    ]),
                                ]),




                    Forms\Components\TextInput::make('user_id')
                        ->numeric(),
                    Forms\Components\TextInput::make('enrol_id')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('indexnumber')
                        ->maxLength(255),
                    Forms\Components\Select::make('title')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('first_name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('last_name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('studentype')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Select::make('gender')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\DatePicker::make('dateofbirth'),
                    Forms\Components\TextInput::make('religion')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('nationality')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('state')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('disability')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('postcode')
                        ->maxLength(255),
                    Forms\Components\Textarea::make('address')
                        ->maxLength(65535)
                        ->columnSpanFull(),
                    Forms\Components\Select::make('maritalstutus')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('entrylevel')
                        ->maxLength(255),
                    Forms\Components\Select::make('session')
                        ->maxLength(255),
                    Forms\Components\Select::make('programme')
                        ->maxLength(255),
                    Forms\Components\Select::make('protype')
                        ->maxLength(255),
                    Forms\Components\Select::make('currentlevel')
                        ->maxLength(255),
                    Forms\Components\DatePicker::make('admitted'),
                    Forms\Components\Toggle::make('status')
                        ->required(),
                ])
                ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('indexnumber')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('first_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('studentype')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('gender')
                    ->searchable(),
                Tables\Columns\TextColumn::make('dateofbirth')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('religion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nationality')
                    ->searchable(),
                Tables\Columns\TextColumn::make('state')
                    ->searchable(),
                Tables\Columns\TextColumn::make('disability')
                    ->searchable(),
                Tables\Columns\TextColumn::make('postcode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('maritalstutus')
                    ->searchable(),
                Tables\Columns\TextColumn::make('entrylevel')
                    ->searchable(),
                Tables\Columns\TextColumn::make('session')
                    ->searchable(),
                Tables\Columns\TextColumn::make('programme')
                    ->searchable(),
                Tables\Columns\TextColumn::make('protype')
                    ->searchable(),
                Tables\Columns\TextColumn::make('currentlevel')
                    ->searchable(),
                Tables\Columns\TextColumn::make('admitted')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('status')
                    ->boolean(),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudents::route('/create'),
            'edit' => Pages\EditStudents::route('/{record}/edit'),
        ];
    }
}