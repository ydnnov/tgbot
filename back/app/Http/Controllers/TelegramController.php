<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendTelegramMessageRequest;
use App\Jobs\SendTelegramMessage;
use Illuminate\Support\Facades\Queue;

class TelegramController extends Controller
{
    public function __construct()
    {
    }

    public function sendMessage(SendTelegramMessageRequest $request)
    {
        Queue::push(new SendTelegramMessage(
            $request->input('peer'),
            $request->input('message')
        ));

        return response()->json(['status' => 'Message sent!']);
    }
}
