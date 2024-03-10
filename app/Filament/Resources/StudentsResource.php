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
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;

class StudentsResource extends Resource
{
    protected static ?string $model = Students::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $slug = 'settings/students';
    public static function getNavigationGroup(): string
    {
        return isset(auth()->user()->roles[0]->name) && auth()->user()->roles[0]->name == "Administrator" ? 'Settings' : '';
    }
    protected static ?string $navigationLabel = 'Patient';
    protected static ?string $modelLabel = 'Patient';
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
                                    Forms\Components\Section::make('')
                                        ->description('')
                                        ->schema([
                                            
                                        Forms\Components\Select::make('title')
                                            ->options([
                                                'Mr' => 'Mr',
                                                'Mrs' => 'Mrs',
                                                'Ms' => 'Ms',
                                            ])
                                            ->required(),
                                        Forms\Components\TextInput::make('first_name')
                                        ->label('First name')
                                            ->required()
                                            ->maxLength(255)
                                            ->afterStateUpdated(function ($state, Forms\Set $set){
                                                $set('patient.first_name',$state);
                                            }),
                                        Forms\Components\TextInput::make('last_name')
                                        ->label('Last name')
                                            ->required()
                                            ->maxLength(255)
                                            ->afterStateUpdated(function ($state, Forms\Set $set){
                                                $set('patient.last_name',$state);
                                            }),
                                        Forms\Components\Select::make('studentype')
                                        ->label('Session')
                                            ->options([
                                                'Regular Session' => 'Regular Session',
                                                'Evening Session' => 'Evening Session'
                                            ])
                                            ->required(),
                                        Forms\Components\TextInput::make('email')
                                            ->email()
                                            ->required()
                                            ->maxLength(255)
                                            ->afterStateUpdated(function ($state, Forms\Set $set){
                                                $set('patient.email',$state);
                                            }),
                                        Forms\Components\TextInput::make('phone')
                                            ->tel()
                                            ->required()
                                            ->maxLength(255)
                                            ->afterStateUpdated(function ($state, Forms\Set $set){
                                                $set('patient.contact_number',$state);
                                            }),
                                        Forms\Components\Select::make('gender')
                                            ->required()
                                            ->options([
                                                'Male' => 'Male',
                                                'Female' => 'Female'
                                            ])
                                            ->afterStateUpdated(function ($state, Forms\Set $set){
                                                $set('patient.gender',$state);
                                            }),
                                        Forms\Components\DatePicker::make('dateofbirth')
                                        ->label('Date of birth')
                                        ->required()
                                        ->afterStateUpdated(function ($state, Forms\Set $set){
                                            $set('patient.date_of_birth',$state);
                                        }),
                                        Forms\Components\TextInput::make('religion')
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('nationality')
                                            ->maxLength(255),
                                        Country::make('country')
                                            ->searchable(),
                                        Forms\Components\TextInput::make('state')
                                            ->maxLength(255),
                                        Forms\Components\select::make('disability')
                                            ->options([
                                                'No' => 'No',
                                                'Yes' => 'Yes'
                                            ]),
                                        Forms\Components\TextInput::make('postcode')
                                            ->maxLength(255),
                                        Forms\Components\Textarea::make('address')
                                            ->maxLength(65535)
                                            ->columnSpanFull()
                                            ->afterStateUpdated(function ($state, Forms\Set $set){
                                                $set('patient.address',$state);
                                            }),
                                        Forms\Components\Select::make('maritalstutus')
                                        ->options([
                                            'Married' => 'Married',
                                            'Unmarried' => 'Unmarried'
                                        ]),

                                        ])
                                        ->columns(2),
                                    ]),
                                    Forms\Components\Wizard\Step::make('Academic Information')
                                    ->schema([
                                        Forms\Components\Select::make('entrylevel')
                                        ->label('Level')
                                            ->options([
                                                'Level 100' => 'Level 100',
                                                'Level 200' => 'Level 200',
                                                'Level 300' => 'Level 300',
                                                'Level 400' => 'Level 400',
                                            ]),
                                        Forms\Components\Select::make('session')
                                        ->options([
                                            'Regular Session' => 'Regular Session',
                                            'Evening Session' => 'Evening Session'
                                        ])->required(),
                                        Forms\Components\TextInput::make('programme')
                                            ->required(),
                                        Forms\Components\Hidden::make('protype')
                                            ->default("null"),
                                    ]),
                                        Forms\Components\Wizard\Step::make('Medical Information')
                                        ->schema([
                                            Forms\Components\Section::make('')
                                                ->description('')
                                                ->relationship('patient')
                                                ->schema([
                                                    
                                            Forms\Components\Hidden::make('first_name'),
                                            Forms\Components\Hidden::make('last_name'),
                                            Forms\Components\Hidden::make('date_of_birth'),
                                            Forms\Components\Hidden::make('gender'),
                                            Forms\Components\Hidden::make('contact_number'),
                                            Forms\Components\Hidden::make('email'),
                                            Forms\Components\Hidden::make('address'),
                                            Forms\Components\textarea::make('allergies')->required(),
                                            Forms\Components\textarea::make('medical_condition_medication')->required(),
                                            Forms\Components\TextInput::make('bloodgroup')->required(),
                                            Forms\Components\TextInput::make('emergency_contact_name')->required(),
                                            Forms\Components\TextInput::make('emergency_contact_number')->required(),
                                            

                                            ])
                                            ->columns(2),
                                        ])
                                ]),
                ])
                ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('indexnumber')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nationality')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('state')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('disability')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('postcode')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('maritalstutus')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('entrylevel')
                    ->label('Level')
                    ->searchable(),
                Tables\Columns\TextColumn::make('session')
                    ->searchable(),
                Tables\Columns\TextColumn::make('programme')
                    ->searchable(),
                Tables\Columns\TextColumn::make('protype')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('currentlevel')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('admitted')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Actions\Action::make("Delete")
                ->icon('heroicon-m-check-badge')
                ->color('danger')
                ->visible(function(){
                    return isset(auth()->user()->roles[0]->name) && auth()->user()->roles[0]->name != "Administrator" ? false : true;
                })
                ->requiresConfirmation()
                ->action(function (Students $record) {
                    $record->patient()->delete();
                    //$record->user()->syncRoles([]);
                    $record->delete();
                    $record->user()->delete();
                }),
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
