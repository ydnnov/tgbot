<?php

namespace App\Console\Commands;

use app\Services\TelegramService;
use Illuminate\Console\Command;

class TelegramBotStartOne extends Command
{
    protected $signature = 'bot:start {--bot_id=}';

    protected $description = 'Run telegram bot';

    public function handle(TelegramService $telegramService)
    {
        $botId = $this->option('bot_id');
        if (!$botId) {
            $this->error("Bot ID is required.");
            return;
        }

        $telegramService->startBotListener($botId);
    }
}
