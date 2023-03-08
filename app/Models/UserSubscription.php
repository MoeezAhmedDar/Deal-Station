<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'transaction_id',
        'plan',
        'subscription',
        'user_id',
        'user_subscriptions_expiry',
        'user_subscriptions_payment_status'
    ];

    public function membership()
    {
        return $this->belongsTo(MembershipSubscription::class, 'subscription');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan');
    }

    public function member()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
