<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\TrafficViolationController;
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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/translate', [TranslationController::class, 'showTranslatePage']);


// Route::get('/tra-cuu', [TrafficViolationController::class, 'showCheckViolationPage']);
// Route::post('/check-violation', [TrafficViolationController::class, 'checkViolation']);

Route::get('/tra-cuu', [TrafficViolationController::class, 'showCheckViolationPage']);
// Route::post('/check-violation', [TrafficViolationController::class, 'checkViolation'])->name('traffic-violation.check');
