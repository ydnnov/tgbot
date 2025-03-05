<?php

namespace app\Console\Commands;

use App\Services\BotService;
use Illuminate\Console\Command;

class TelegramBotList extends Command
{
    protected $signature = 'bot:list';

    protected $description = 'Show telegram bot s list';

    public function handle(BotService $botService)
    {
        $statuses = $botService->getStatuses();

        $this->table(
            ['id', 'username', 'status'],
            $statuses
        );
    }
}
