<?php

namespace App\Jobs;

use App\Models\TgMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessTelegramMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected int $id,
        protected int $chatId,
        protected int $senderId,
        protected string $message,
    )
    {
    }

    public function handle(): void
    {
        $msg = new TgMessage([
            'tg_id' => $this->id,
            'chat_id' => $this->chatId,
            'sender_id' => $this->senderId,
            'message' => $this->message,
        ]);

        $msg->save();
    }
}
