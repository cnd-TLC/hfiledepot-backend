<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'pr_no',
        'department',
        'section',
        'requested_by',
        'cash_availability',
        'fpp',
        'fund',
        'status',
        'purpose',
        'approved_by_cbo_name',
        'approved_by_cbo',
        'approved_by_cto_name',
        'approved_by_cto',
        'approved_by_cmo_name',
        'approved_by_cmo',
        'approved_by_bac_name',
        'approved_by_bac',
        'approved_by_cgso_name',
        'approved_by_cgso',
        'approved_by_cao_name',
        'approved_by_cao',
        'attachments'
    ];
}