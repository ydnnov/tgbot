<?php

namespace app\Console\Commands;

use App\Services\BotService;
use Illuminate\Console\Command;

class TelegramBotStartAll extends Command
{
    protected $signature = 'bot:start-all';

    protected $description = 'Run all telegram bots';

    public function handle(BotService $botService)
    {
        $botService->startAllBackground();

        $this->info("All bots started");
    }
}
