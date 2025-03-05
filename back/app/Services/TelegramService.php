<?php

namespace app\Services;

use App\Models\TgBot;
use danog\MadelineProto\API;
use danog\MadelineProto\Settings;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    public function getMadeline($botId)
    {
        $bot = TgBot::findOrFail($botId);

        $settings = new Settings();
        $appInfo = new Settings\AppInfo();
        $appInfo->setApiId(config('services.telegram.api_id'));
        $appInfo->setApiHash(config('services.telegram.api_hash'));
        $settings->setAppInfo($appInfo);

        $sessionPath = storage_path("telegram.sessions/{$bot->username}");

        if (file_exists($sessionPath)) {
            Log::info('Using existing session');
            $madeline = new API($sessionPath);
            $madeline->start();
        } else {
            Log::info('Creating new session');
            $madeline = new API($sessionPath, $settings);
            $madeline->botLogin($bot->token);
        }

        return $madeline;
    }
}
