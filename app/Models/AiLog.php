<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_number',
        'channel',
        'query',
        'response',
        'prompt_tokens',
        'completion_tokens',
        'total_tokens',
        'model',
    ];
}
