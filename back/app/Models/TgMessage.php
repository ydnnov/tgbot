<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TgMessage extends Model
{
    protected $fillable = [
        'tg_id',
        'chat_id',
        'sender_id',
        'message',
    ];
}
