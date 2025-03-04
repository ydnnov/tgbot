<?php

namespace App\Providers;

use danog\MadelineProto\API;
use danog\MadelineProto\Settings;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class TelegramProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(API::class, function () {
            $settings = new Settings();
            $appInfo = new Settings\AppInfo();
            $appInfo->setApiId(config('services.telegram.api_id'));
            $appInfo->setApiHash(config('services.telegram.api_hash'));
            $settings->setAppInfo($appInfo);

            $sessionPath = storage_path('telegram.session');

            if (file_exists($sessionPath)) {
                Log::info('Using existing session');
                $madeline = new API($sessionPath);
                $madeline->start();
            } else {
                Log::info('Creating new session');
                $madeline = new API($sessionPath, $settings);
                $madeline->botLogin(config('services.telegram.bot_token'));
            }

            return $madeline;
        });
    }

    public function boot(): void
    {
    }
}
