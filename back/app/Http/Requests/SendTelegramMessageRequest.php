<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendTelegramMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'botId' => ['required', 'integer', 'exists:tg_bots,id'],
            'peer' => ['required', 'string'],
            'message' => ['required', 'string'],
        ];
    }
}
