<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpmpItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'category',
        'general_desc',
        'unit',
        'quantity',
        'lumpsum',
        'mode_of_procurement',
        'estimated_budget',
        'jan',
        'feb',
        'mar',
        'apr',
        'may',
        'jun',
        'jul',
        'aug',
        'sept',
        'oct',
        'nov',
        'dec',
        'ppmp_id',
    ];
}
