<?php

namespace App\Jobs;

use app\Services\TelegramService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTelegramMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $botId,
        protected string $peer,
        protected string $message
    )
    {
    }

    public function handle(TelegramService $service): void
    {
        $madeline = $service->getMadeline($this->botId);

        $madeline->messages->sendMessage(
            peer: $this->peer,
            message: $this->message,
        );
    }
}
