<?php

use App\Http\Controllers\TelegramController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/bot/send-message', [TelegramController::class, 'sendMessage']);
Route::get('/bot/get-messages', [TelegramController::class, 'getMessages']);
