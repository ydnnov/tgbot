<?php

namespace App\Jobs;

use danog\MadelineProto\API;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendTelegramMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $peer,
        protected string $message
    )
    {
    }

    public function handle(API $madeline): void
    {
        $madeline->messages->sendMessage(
            peer: $this->peer,
            message: $this->message,
        );
    }
}
