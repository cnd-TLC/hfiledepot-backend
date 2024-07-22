<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpmpItemsCatalog extends Model
{
    use HasFactory;

    protected $fillable = [
        'general_desc',
        'unit',
        // 'mode_of_procurement',
        'year',
        'department'
    ];
}
