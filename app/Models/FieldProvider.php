<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'provider_type',
        'region',
        'district',
        'ward',
        'phone',
        'accepted_materials',
        'buyback_rates_json',
        'is_verified',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function getBuybackRatesAttribute(): array
    {
        if (empty($this->buyback_rates_json)) {
            return [];
        }

        $decoded = json_decode($this->buyback_rates_json, true);

        return is_array($decoded) ? $decoded : [];
    }
}
