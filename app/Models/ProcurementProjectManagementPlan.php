<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementProjectManagementPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'title',
        'pmo_end_user_dept',
        'source_of_funds',
        'attachments',
        'status'
    ];
}
