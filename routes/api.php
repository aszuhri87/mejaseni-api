<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Transaction\PaymentController;
use App\Http\Controllers\Admin\Master\TheoryVideoController;


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

Route::post('/notifications/payments', [PaymentController::class, 'notification']);
Route::put('/video-converter/{id}/hook',[TheoryVideoController::class, 'video_converter_hook']);
