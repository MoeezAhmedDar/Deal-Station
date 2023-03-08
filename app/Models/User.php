<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;
// use Devinweb\LaravelHyperpay\Traits\ManageUserTransactions;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;
    // use ManageUserTransactions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        "phone",
        "dob",
        "gender",
        "city",
        "is_verified",
        "otp",
        "otp_expires",
        "profile_image",
        'device_token',
        'is_completed'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function merchant()
    {
        return $this->hasOne(MerchantDetail::class, 'merchant_id');
    }

    public function memberSubscription()
    {
        return $this->hasOne(MerchantDetail::class, 'merchant_id');
    }

    public function latestMemberSubscription()
    {
        return $this->hasOne(UserSubscription::class, 'user_id')->where('user_subscriptions_payment_status', 'Paid')->latestOfMany();
    }

    public function cashierBranch()
    {
        return $this->hasOne(Branch::class, 'branch_cashier');
    }

    public function merchantOffer()
    {
        return $this->hasMany(Offer::class, 'merchant_id', 'id');
    }
}
