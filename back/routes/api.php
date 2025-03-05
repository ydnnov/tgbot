<?php

use App\Http\Controllers\TelegramController;
use App\Http\Controllers\TgBotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('telegram')->group(function () {
    Route::post('/send-message', [TelegramController::class, 'sendMessage']);
    Route::get('/get-dialogs/{botId}', [TelegramController::class, 'getDialogs']);
});

Route::prefix('bots')->group(function () {
    Route::get('/', [TgBotController::class, 'getAll']);
    Route::get('/{tgBot}', [TgBotController::class, 'getOne']);
    Route::post('/', [TgBotController::class, 'store']);
    Route::delete('/{tgBot}', [TgBotController::class, 'destroy']);
});
