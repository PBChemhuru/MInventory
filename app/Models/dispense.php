<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dispense extends Model
{
    use HasFactory;
    public $fillable =
    [
        'UIN',
        'drug',
        'drug_number',
        'damount',
        'recipient',
        'dispenser',
        'dispense_time',
        'clinic'


    ];

    public $timestamps = false;
}
