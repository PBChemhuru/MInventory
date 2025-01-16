<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class pending_stocks extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'item_name',
        'item_quantity',
        'item_number',
        'procurer',
        'status',
        'clinics',
        'reciever',
        'Received_at',
        'p_o_r',
        'details',
        'total_items'

    ];
}
