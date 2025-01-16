<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transferrecord extends Model
{
    use HasFactory;

    public $fillable = [
        'transdetail',
        'clinic_from',
        'sender',
        'clinic_to',
        'receiver',
        'status',
        'p_o_d'
    ];
}
