<?php

namespace App\Filament\Reports;

use App\Models\Appointments;
use EightyNine\Reports\Report;
use EightyNine\Reports\Components\Body;
use EightyNine\Reports\Components\Footer;
use EightyNine\Reports\Components\Header;
use EightyNine\Reports\Components\Text;
use EightyNine\Reports\Components\Image;
use EightyNine\Reports\Components\VerticalSpace;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class AppointmentReport extends Report
{
    public ?string $heading = "Report";
    
    // public ?string $subHeading = "A great report";
    
    // public static function shouldRegisterNavigation(): bool
    // {
        //     return false;
        // }
        
        
        public function header(Header $header): Header
        {
            return $header
            ->schema([
                Header\Layout\HeaderRow::make()
                ->schema([
                    Header\Layout\HeaderColumn::make()
                    ->alignCenter()
                    ->schema([
                        Text::make("University Of Professional Studies Clinic")
                        ->primary(),
                        Text::make("Appointment Report")
                        ->subtitle(),
                    ]), 
                    ])
                ]);
            }
            
            
            public function body(Body $body): Body
            {
                return $body
                ->schema([
                    Body\Layout\BodyColumn::make()
                    ->schema([
                        Body\Table::make()
                        ->columns([
                            Body\TextColumn::make("user_name")
                            ->label("Patient"),
                            Body\TextColumn::make("doctor_name")
                            ->label("Doctor"),
                            Body\TextColumn::make("appointment_datetime")
                            ->label("Appointment date"),
                            Body\TextColumn::make("status")
                            ->label("Status"),
                            Body\TextColumn::make("astatus")
                            ->label("Arrival Status"),
                            Body\TextColumn::make("created_at")
                            ->label("Created_at date")
                            ->dateTime(),
                            ])
                            ->data(
                                function (?array $filters) {
                                    [$from, $to] = [$filters['from_date'] ?? null, $filters['to_date'] ?? null];
                                    return Appointments::with(['user', 'doctor'])
                                    ->when($from, function ($query, $date) {
                                        logger($date);
                                        return $query->whereDate('appointments.created_at', '>=', $date);
                                    })
                                    ->when($to, function ($query, $date) {
                                        logger($date);
                                        return $query->whereDate('appointments.created_at', '<=', $date);
                                    })
                                    ->select('users.name as user_name',DB::raw("CONCAT(doctors.first_name, ' ', doctors.last_name) as doctor_name"),
                                    'appointments.appointment_datetime as appointment_datetime', 'appointments.status as status', 'appointments.arrival_status as astatus','appointments.created_at as created_at')
                                    ->leftJoin('users', 'users.id', '=', 'appointments.user_id')
                                    ->leftJoin('doctors', 'doctors.id', '=', 'appointments.doctor_id')
                                    //->take(10)
                                    ->get();
                                }
                                )
                            ]),
                        ]);
                    }
                    
                    function appointmentdata(?array $filters)
                    {
                        logger($filters);
                    }
                    
                    
                    
                    public function footer(Footer $footer): Footer
                    {
                        return $footer
                        ->schema([
                            Footer\Layout\FooterRow::make()
                            ->schema([
                                Footer\Layout\FooterColumn::make()
                                ->schema([
                                    Text::make("Generated on: " . now()->format('Y-m-d H:i:s'))
                                    ->subtitle(),
                                    ])
                                    ->alignCenter(),
                                ]),
                            ]);
                        }
                        
                        public function filterForm(Form $form): Form
                        {
                            return $form
                            ->schema([
                                DatePicker::make('from_date')
                                ->required(),
                                
                                DatePicker::make('to_date')
                                ->required(),
                            ]);
                        }
                    }
                    