<?php

namespace app\Console\Commands;

use App\Services\BotService;
use Illuminate\Console\Command;

class TelegramBotStartOneForeground extends Command
{
    protected $signature = 'bot:start-fg {--bot_id=}';

    protected $description = 'Run telegram bot in foreground';

    public function handle(BotService $botService)
    {
        $botId = $this->option('bot_id');
        if (!$botId) {
            $this->error("Bot ID is required.");
            return;
        }

        $botService->startForeground($botId);
    }
}
