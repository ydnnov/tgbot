<?php

namespace App\Jobs;

use danog\MadelineProto\API;
use danog\MadelineProto\Settings;
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

    public function handle(): void
    {
        $settings = new Settings();
        $appInfo = new Settings\AppInfo();
        $appInfo->setApiId(config('services.telegram.api_id'));
        $appInfo->setApiHash(config('services.telegram.api_hash'));
        $settings->setAppInfo($appInfo);

        $sessionPath = storage_path('telegram.session');

        if (file_exists($sessionPath)) {
            echo "Using existing session\n";
            $madeline = new API($sessionPath);
            $madeline->start();
        } else {
            echo "Creating new session\n";
            $madeline = new API($sessionPath, $settings);
            $madeline->botLogin(config('services.telegram.bot_token'));
        }

        $madeline->messages->sendMessage(
            peer: $this->peer,
            message: $this->message,
        );
    }
}
