<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'total'];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function setTotalAttribute($value)
    {
        return $this->orderDetails->sum('total');
    }
}
