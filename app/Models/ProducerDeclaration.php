<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProducerDeclaration extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'tin_number',
        'packaging_type',
        'material_category',
        'quarterly_tonnage',
        'calculated_eco_fee',
        'pro_membership_id',
        'status',
    ];

    protected $casts = [
        'quarterly_tonnage' => 'decimal:2',
        'calculated_eco_fee' => 'decimal:2',
    ];
}
