<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Tickets;
use App\Livewire\Payment;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\TicketSalesController;

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

Route::redirect('/', '/tickets');

Route::get('tickets', Tickets::class)->name('tickets');
Route::get('ticketsales/' . config('app.privateroute')  , TicketSalesController::class)->name('ticketsales');
Route::get('payment', Payment::class)->name('payment');
Route::get('confirmPayment', PaymentController::class)->name('confirmpayment');

Route::get('qrcode', QrCodeController::class);


