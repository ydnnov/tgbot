<?php declare(strict_types=1);

namespace App\Classes;

use App\Jobs\ProcessTelegramMessage;
use danog\MadelineProto\EventHandler\Attributes\Handler;
use danog\MadelineProto\EventHandler\Message;
use danog\MadelineProto\EventHandler\SimpleFilter\Incoming;
use danog\MadelineProto\SimpleEventHandler;
use Illuminate\Support\Facades\Queue;

class BotEventHandler extends SimpleEventHandler
{
    #[Handler]
    public function handleMessage(Incoming&Message $message): void
    {
        Queue::push(new ProcessTelegramMessage(
            $message->id,
            $message->chatId,
            $message->senderId,
            $message->message,
        ));
    }
}
