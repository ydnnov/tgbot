<?php

namespace app\Services;

use App\Classes\BotEventHandler;
use App\Models\TgBot;
use danog\MadelineProto\API;
use danog\MadelineProto\Settings;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    public function getMadeline($botId)
    {
        [$settings, $bot, $sessionPath] = $this->makeMadelineConfig($botId);

        if (file_exists($sessionPath)) {
            Log::info("Using existing session (bot_id={$botId})");
            $madeline = new API($sessionPath);
            $madeline->start();
        } else {
            Log::info("Creating new session (bot_id={$botId})");
            $madeline = new API($sessionPath, $settings);
            $madeline->botLogin($bot->token);
        }

        return $madeline;
    }

    public function startBotListener($botId)
    {
        [$settings, $bot, $sessionPath] = $this->makeMadelineConfig($botId);

        BotEventHandler::startAndLoopBot(
            $sessionPath,
            $bot->token,
            $settings,
        );
    }

    protected function makeMadelineConfig($botId)
    {
        $bot = TgBot::findOrFail($botId);

        $settings = new Settings();
        $appInfo = new Settings\AppInfo();
        $appInfo->setApiId(config('services.telegram.api_id'));
        $appInfo->setApiHash(config('services.telegram.api_hash'));
        $settings->setAppInfo($appInfo);

        $sessionPath = storage_path("telegram.sessions/{$bot->username}");

        return [$settings, $bot, $sessionPath];
    }
}
