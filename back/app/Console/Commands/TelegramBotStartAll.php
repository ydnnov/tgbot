<?php

namespace app\Console\Commands;

use App\Classes\BotEventHandler;
use App\Models\TgBot;
use danog\MadelineProto\Settings;
use danog\MadelineProto\Settings\AppInfo;
use Illuminate\Console\Command;

class TelegramBotStartAll extends Command
{
    protected $signature = 'bot:start-all';

    protected $description = 'Run all telegram bots';

    public function handle()
    {
        $bots = TgBot::all();
        foreach ($bots as $bot) {
            $logFile = storage_path("logs/bot_{$bot->username}.log");
            $cmd = "php artisan bot:start --bot_id={$bot->id} >> {$logFile} 2>&1 &";
            exec($cmd);
        }
        $this->info("All bots started in background.");
    }
}
