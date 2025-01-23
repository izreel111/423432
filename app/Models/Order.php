<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total_cost',
        'distance_cost',
        'insurance',
        'client_name',
        'client_phone',
        'client_email',
        'pick_up_address',
        'zip_code',
        'delivery_state',
        'delivery_address',
    ];

    // Связь с пользователем (владелец)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Связь с order_items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}