<?php

namespace app\Console\Commands;

use App\Models\TgBot;
use Illuminate\Console\Command;

class TelegramBotList extends Command
{
    protected $signature = 'bot:list';

    protected $description = 'Show telegram bot s list';

    public function handle()
    {
        // TODO add some ps aux command to look for bots that are really running and add this info to output
        $bots = TgBot::all();
        $this->table(
            ['id', 'username'],
            $bots->map(function ($bot) {
                return [
                    'id' => $bot->id,
                    'username' => $bot->username,
                ];
            })->toArray()
        );
    }
}
