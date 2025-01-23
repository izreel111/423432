<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'item_type',
        'item_subtype',
        'quantity',
        'length',
        'width',
        'height',
        'cost',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}