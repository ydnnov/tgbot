<?php

namespace app\Console\Commands;

use App\Services\BotService;
use Illuminate\Console\Command;

class TelegramBotStopOne extends Command
{
    protected $signature = 'bot:stop {--bot_id=}';

    protected $description = 'Stop telegram bot';

    public function handle(BotService $botService)
    {
        $botId = $this->option('bot_id');
        if (!$botId) {
            $this->error("Bot ID is required.");
            return;
        }

        $botService->stop($botId);

        $this->info('Bot stopped');
    }
}
