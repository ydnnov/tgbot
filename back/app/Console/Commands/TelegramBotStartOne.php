<?php

namespace App\Console\Commands;

use App\Services\BotService;
use Illuminate\Console\Command;

class TelegramBotStartOne extends Command
{
    protected $signature = 'bot:start {--bot_id=}';

    protected $description = 'Run telegram bot';

    public function handle(BotService $botService)
    {
        $botId = $this->option('bot_id');
        if (!$botId) {
            $this->error("Bot ID is required.");
            return;
        }

        $botService->startBackground($botId);

        $this->info('Bot started');
    }
}
