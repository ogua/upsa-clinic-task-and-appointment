<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MedicaltaskResource\Pages;
use App\Filament\Resources\MedicaltaskResource\RelationManagers;
use App\Models\Medicaltask;
use App\Models\Patients;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MedicaltaskResource extends Resource
{
    protected static ?string $model = Medicaltask::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Medical tasks';
    protected static ?string $modelLabel = 'Medical task';

    public static function getEloquentQuery(): Builder
    {
        if (isset(auth()->user()->roles[0]->name) && auth()->user()->roles[0]->name != "Administrator") {
            return parent::getEloquentQuery()->where('patient_id', auth()->user()->id)
           ->orderBy('id','desc');
        }

        return parent::getEloquentQuery()->orderBy('id','desc');
        
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('')
                    ->description('')
                    ->schema([
                Forms\Components\TextInput::make('task_name')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\Select::make('patient_id')
                ->label('Task for')
                ->options(Patients::get()->pluck('full_name', 'id'))
                ->searchable()
                ->preload(),

                Forms\Components\Textarea::make('description')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),

                Forms\Components\DatePicker::make('deadline')
                    ->required(),

                Forms\Components\Hidden::make('status')
                    ->default("pending"),

                Forms\Components\select::make('priority')
                    ->required()
                    ->options(
                        [
                            'high' => 'high',
                            'medium' => 'medium',
                            'low' => 'low',
                        ]
                    ),

                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient.full_name')
                ->label('Patient')
                ->searchable(),
                Tables\Columns\TextColumn::make('doctor.full_name')
                ->label('Doctor')
                ->searchable(),
                Tables\Columns\TextColumn::make('task_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deadline')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'pending' => 'danger',
                    'in-progress' => 'success',
                    'completed' => 'info',
                })->searchable(),

                Tables\Columns\TextColumn::make('priority')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'high' => 'warning',
                    'medium' => 'success',
                    'low' => 'info',
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
                ->color('info')
                ->hidden(function (Medicaltask $record){
                    return $record->reminder ? false : true;
                }),
                Tables\Actions\Action::make("Update status")
                ->icon('heroicon-m-check-badge')
                ->color('success')
                ->form([
                    Forms\Components\Section::make('')
                        ->description('')
                        ->schema([
                            

                            Forms\Components\select::make('status')
                            ->options([
                                'pending' => 'pending',
                                'in-progress' => 'in-progress',
                                'completed' => 'completed',
                            ])->required()
                        ])
                ])
                ->action(function (array $data, Medicaltask $record) {
                    $record->status = $data['status'];
                    $record->save();
                }),
                Tables\Actions\Action::make("Create remainder")
                ->icon('heroicon-m-check-badge')
                ->color('info')
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
                ->hidden(function (Medicaltask $record){
                    return $record->reminder ? true : false;
                })
                ->action(function (array $data, Medicaltask $record) {
                    $record->reminder()->create($data);
                    //$record->
                    //$record->save();
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
            'index' => Pages\ListMedicaltasks::route('/'),
            'create' => Pages\CreateMedicaltask::route('/create'),
            'edit' => Pages\EditMedicaltask::route('/{record}/edit'),
        ];
    }
}
