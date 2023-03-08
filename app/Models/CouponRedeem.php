<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponRedeem extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'offer_id',
        'coupons_id',
        'user_id',
        'discount',
        'price',
        'city_id',
        'discount_price'
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer_id');
    }
}
