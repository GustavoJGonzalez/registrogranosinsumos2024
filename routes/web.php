<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecepcionController;
use App\Http\Controllers\RemisionController;
use App\Http\Controllers\ChoferController;
use App\Http\Controllers\TicketController;
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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
Route::get('/create-remision/{id}', [RemisionController::class, 'createFromControlAcceso']);

Route::get('/obtenerChapaCamion', [RecepcionController::class, 'obtenerChapaCamion']);

Route::resource('chofers', ChoferController::class);


Route::get('/generate-ticket', [TicketController::class, 'generateTicket']);