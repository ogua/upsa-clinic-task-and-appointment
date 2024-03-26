<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\Medicaltask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;


class Webcontroller extends Controller
{
    public function home()
    {
        return view('home');
    }
    
    public function about()
    {
        return view('about');
    }
    
    public function report(Request $request)
    {
        //dd($request);
    }
    public function pdfreport($from_date,$to_date,$report_type)
    {
        if($report_type == "Appointments"){
            $reports = Appointments::whereBetween('created_at',[$from_date,$to_date])->get();
        }
        
        if($report_type == "Medical tasks"){
            $reports = Medicaltask::whereBetween('created_at',[$from_date,$to_date])->get();
        }
        
        return view('pdfreport',compact('reports','from_date','to_date','report_type'));
    }
    
    public function pdfdownload($from_date,$to_date,$report_type)
    {
        $pdf = App::make('dompdf.wrapper');
        
        if($report_type == "Appointments"){
            $reports = Appointments::whereBetween('created_at',[$from_date,$to_date])->get();
        }
        
        if($report_type == "Medical tasks"){
            $reports = Medicaltask::whereBetween('created_at',[$from_date,$to_date])->get();
        }
        
        $pdf->loadView('pdfdownload',compact('reports','from_date','to_date','report_type'));
        
        return $pdf->stream("REPORT-FROM-{$from_date}-TO-{$to_date}.pdf");
    }
}
