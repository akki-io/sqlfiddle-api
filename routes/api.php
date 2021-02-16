<?php

use App\Http\Controllers\FiddleController;
use App\Http\Controllers\HealthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', [HealthController::class, 'index'])->name('health');
Route::post('/fiddle', [FiddleController::class, 'index'])->name('fiddle');
