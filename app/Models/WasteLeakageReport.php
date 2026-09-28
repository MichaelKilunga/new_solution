<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteLeakageReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_code',
        'sender_phone',
        'district',
        'location_details',
        'description',
        'status',
        'assigned_to',
    ];
}
