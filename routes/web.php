<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webcontroller;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', [Webcontroller::class, 'home'])->name('home');

Route::get('/', function(){
    return redirect()->to('/admin');
});

Route::get('/report', [Webcontroller::class, 'report'])->name('report');

Route::get('/about', [Webcontroller::class, 'about'])->name('about');

Route::get('/contact', [Webcontroller::class, 'contact'])->name('contact');

Route::get('/report-detials/{from_date}/{to_date}/{report_type}', [Webcontroller::class, 'pdfreport']);