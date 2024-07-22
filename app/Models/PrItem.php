<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_no',
        'unit',
        'category',
        'item_description',
        'quantity',
        'unit_cost',
        'lumpsum',
        'mode_of_procurement',
        'remarks',
        'pr_id',
    ];
}
