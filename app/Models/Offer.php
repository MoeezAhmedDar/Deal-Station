<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'offer_uniid',
        'offer_name',
        'offer_name_arabic',
        'offer_description',
        'offer_description_arabic',
        'offer_desc_description',
        'offer_desc_description_arabic',
        'offer_image_link',
        'offer_discount',
        'offer_price',
        'offer_to',
        'offer_from',
        'offer_coupons',
        'offer_coupon_type',
        'offer_code_generation',
        'offer_campaign',
        'offer_per_user',
        'offer_usage_duration',
        'offer_type',
        'offer_status',
        'offer_comments',
        'merchant_id',
        'offer_request'
    ];

    public function merchant()
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }

    public function offerCategories()
    {
        return $this->hasMany(OfferCategory::class, 'offer');
    }

    public function offerBranches()
    {
        return $this->hasMany(OfferBranch::class, 'offer');
    }

    public function targetPlans()
    {
        return $this->hasMany(TargetedMembership::class, 'offer');
    }

    public function visiblePlans()
    {
        return $this->hasMany(VisibleMembership::class, 'offer');
    }

    /**
     * Get all of the images.
     */
    public function gallery()
    {
        return $this->morphMany(Image::class, 'image')->orderBy('image_order', 'ASC');
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class, 'offer_id');
    }

    public function redeemCoupons()
    {
        return $this->hasMany(CouponRedeem::class, 'offer_id');
    }

    public function wishList()
    {
        return $this->hasMany(OfferWishlist::class, 'offer_id');
    }

    public function offerNotification()
    {
        return $this->hasMany(OfferNotification::class, 'offer', 'offer_uniid');
    }
}
