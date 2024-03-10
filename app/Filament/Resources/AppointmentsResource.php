<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentsResource\Pages;
use App\Filament\Resources\AppointmentsResource\RelationManagers;
use App\Models\Appointments;
use App\Models\Doctors;
use App\Models\Specialists;
use App\Services\ReservationService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AppointmentsResource extends Resource
{
    protected static ?string $model = Appointments::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getEloquentQuery(): Builder
    {
        if (isset(auth()->user()->roles[0]->name) && auth()->user()->roles[0]->name != "Administrator") {
            return parent::getEloquentQuery()->where('user_id', auth()->user()->id)
           ->orderBy('id','desc');
        }

        return parent::getEloquentQuery()->orderBy('id','desc');
        
    }

    public static function form(Form $form): Form
    {
        
        $dateFormat = 'Y-m-d';
        
        return $form
            ->schema([
                Forms\Components\Section::make('')
                    ->description('')
                    ->schema([

                        Forms\Components\Select::make('doctor_id')
                            ->label('Doctor')
                            ->options(Doctors::get()->pluck('full_name', 'id'))
                            ->searchable()
                            ->live()
                            ->preload()
                            ->required(),

                            Forms\Components\DatePicker::make('appointment_datetime')
                            ->label('Appointment date')
                            ->minDate(now()->format($dateFormat))
                            ->maxDate(now()->addWeeks(2)->format($dateFormat))
                            ->format($dateFormat)
                            ->live()
                            ->required(),

                            Forms\Components\Radio::make('appointment_time')
                            ->options(fn (Get $get) => (new ReservationService())->getAvailableTimesForDate($get('appointment_datetime'), $get('doctor_id')))
                            ->hidden(fn (Get $get) => ! $get('appointment_datetime'))
                            ->required()
                            ->inline()
                            ->inlineLabel(false)
                            ->columnSpanFull(),

                            Forms\Components\Textarea::make('notes')
                            ->required()
                            ->maxLength(65535)
                            ->columnSpanFull(),

                            Forms\Components\Select::make('specialists')
                            ->relationship()
                            ->label('Specialists')
                            ->options(Specialists::all()->pluck('name', 'id'))
                            ->searchable()
                            ->multiple()
                            ->preload(),

                            // Forms\Components\Select::make('status')
                            //  ->options([
                            //     'scheduled' => 'scheduled',
                            //     'completed' => 'completed',
                            //     'scheduled' => 'canceled'
                            //  ])
                            // ->required(),

                            Forms\Components\Hidden::make('status')
                            ->default("scheduled"),
                            
                            // Forms\Components\TextInput::make('arrival_status')
                            //     ->maxLength(255),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label("Patient")
                    ->sortable(),

                Tables\Columns\TextColumn::make('doctor.full_name')
                    ->label("Doctor")
                    ->sortable(),

                Tables\Columns\TextColumn::make('specialists.name')
                    ->label("Specialities")
                    ->badge()
                    ->sortable(),

                Tables\Columns\TextColumn::make('appointment_datetime')
                    ->label('Appointment date')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('appointment_time')
                    ->label('time')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('status')
                     ->badge()
                     ->color(fn (string $state): string => match ($state) {
                        'scheduled' => 'warning',
                        'completed' => 'success',
                        'canceled' => 'info'
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('arrival_status')
                ->badge()
                ->default("processing..")
                ->color(fn (string $state): string => match ($state) {
                    'processing..' => 'warning',
                    'on-time' => 'success',
                    'late' => 'info',
                    'on-show' => 'success',
                })
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
                Tables\Actions\Action::make("Remainder created")
                ->icon('heroicon-m-check-badge')
                ->color('success')
                ->hidden(function (Appointments $record){
                    return $record->reminder ? false : true;
                }),
                Tables\Actions\Action::make("Create remainder")
                ->icon('heroicon-m-check-badge')
                ->color('success')
                ->form([
                    Forms\Components\Section::make('')
                        ->description('')
                        ->schema([

                            Forms\Components\DateTimePicker::make('reminder_datetime')
                            ->required(),

                            Forms\Components\Textarea::make('reminder_message')
                            ->required()
                            ->maxLength(65535)
                            ->columnSpanFull(),

                             Forms\Components\Toggle::make('reminder_sms')
                            ->required(),

                            Forms\Components\Toggle::make('reminder_email')
                            ->required(),

                            Forms\Components\Hidden::make('status')
                            ->default('active')
                        ])
                ])
                ->hidden(function (Appointments $record){
                    return $record->reminder ? true : false;
                })
                ->action(function (array $data, Appointments $record) {
                    $record->reminder()->create($data);
                }),
                Tables\Actions\Action::make("Arrival status")
                ->icon('heroicon-m-check-badge')
                ->color('info')
                ->visible(function(){
                    return isset(auth()->user()->roles[0]->name) && auth()->user()->roles[0]->name == "Student" ? false : true;
                })
                ->form([
                    Forms\Components\Section::make('')
                        ->description('')
                        ->schema([
                            Forms\Components\Select::make('arrival_status')
                             ->options([
                                'on-time' => 'on-time',
                                'late' => 'late',
                                'no-show' => 'no-show'
                             ])
                            ->required(),
                        ])
                ])
                ->action(function (array $data, Appointments $record) {
                    $record->arrival_status = $data['arrival_status'];
                    $record->save();
                }),
                Tables\Actions\Action::make("Cancel")
                ->icon('heroicon-m-check-badge')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function (Appointments $record) {
                    $record->status = "canceled";
                    $record->save();
                }),
                Tables\Actions\Action::make("Completed")
                ->icon('heroicon-m-check-badge')
                ->visible(function(){
                    return isset(auth()->user()->roles[0]->name) && auth()->user()->roles[0]->name == "Student" ? false : true;
                })
                ->color('success')
                ->requiresConfirmation()
                ->action(function (Appointments $record) {
                    $record->status = "completed";
                    $record->save();
                }),
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
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointments::route('/create'),
            'edit' => Pages\EditAppointments::route('/{record}/edit'),
        ];
    }
}
