<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mainstock_journal extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_name',
        'item_quantity',
        'item_number',
        'price',
        'expiry_date',
        'clinics',
        'procurer',
        'batch_number',
        'p_o_d',
        'p_o_r',
        'details',
        'total_items'
    ];
}
