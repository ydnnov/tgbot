<?php

use App\Http\Controllers\TelegramController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/bot/send-message', [TelegramController::class, 'sendMessage']);

Route::prefix('bots')->group(function () {
    Route::get('/', [TgBotController::class, 'getAll']);
    Route::get('/{tgBot}', [TgBotController::class, 'getOne']);
    Route::post('/', [TgBotController::class, 'store']);
    Route::delete('/{tgBot}', [TgBotController::class, 'destroy']);
});
