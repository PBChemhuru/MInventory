<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'item_quantity',
        'item_number',
        'price',
        'user'
    ];

    public $timestamps = true;
    const UPDATED_AT = 'update_at';

}
