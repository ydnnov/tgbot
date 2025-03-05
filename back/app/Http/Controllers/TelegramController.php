<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendTelegramMessageRequest;
use App\Jobs\SendTelegramMessage;
use app\Services\TelegramService;
use Illuminate\Support\Facades\Queue;

class TelegramController extends Controller
{
    public function __construct(
        protected TelegramService $telegramService
    )
    {
    }

    public function sendMessage(SendTelegramMessageRequest $request)
    {
        $botId = $request->input('botId');
        $peer = $request->input('peer');
        $message = $request->input('message');

        if (config('bot.actions_through_queue')) {
            Queue::push(new SendTelegramMessage(
                $botId,
                $peer,
                $message
            ));
        } else {
            $madeline = $this->telegramService->getMadeline($botId);
            $madeline->messages->sendMessage(
                peer: $peer,
                message: $message,
            );
        }

        return response()->json(['status' => 'Message sent!']);
    }

    public function getDialogs(int $botId)
    {
        $madeline = $this->telegramService->getMadeline($botId);

        $ids = $madeline->getDialogIds();

        $result = [];
        foreach ($ids as $id) {
            $info = $madeline->getFullInfo(id: $id);
            $result[] = $info;
        }

        return $result;
    }
}
