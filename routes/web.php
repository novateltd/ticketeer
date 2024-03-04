<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Tickets;
use App\Livewire\Payment;
use App\Http\Controllers\PaymentController;

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

Route::view('/', 'welcome');

Route::get('tickets', Tickets::class)->name('tickets');
Route::get('payment', Payment::class)->name('payment');
Route::get('confirmPayment', PaymentController::class)->name('confirmpayment');

