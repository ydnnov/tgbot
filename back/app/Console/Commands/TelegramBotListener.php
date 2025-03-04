<?php

namespace App\Console\Commands;

use App\Classes\BotEventHandler;
use danog\MadelineProto\Settings;
use danog\MadelineProto\Settings\AppInfo;
use Illuminate\Console\Command;

class TelegramBotListener extends Command
{
    protected $signature = 'bot:run';

    protected $description = 'Run telegram bot';

    public function handle()
    {
        $settings = new Settings();
        $appInfo = new AppInfo();
        $appInfo->setApiId(config('services.telegram.api_id'));
        $appInfo->setApiHash(config('services.telegram.api_hash'));
        $settings->setAppInfo($appInfo);

        BotEventHandler::startAndLoopBot(
            'bot.madeline',
            config('services.telegram.bot_token'),
            $settings
        );
    }
}
