<?php

namespace app\Console\Commands;

use App\Services\BotService;
use Illuminate\Console\Command;

class TelegramBotStopAll extends Command
{
    protected $signature = 'bot:stop-all';

    protected $description = 'Stop all telegram bots';

    public function handle(BotService $botService)
    {
        $botService->stopAll();

        $this->info('All bots stopped');
    }
}
