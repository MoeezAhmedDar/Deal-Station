<?php

namespace App\Models;

use App\Http\Controllers\Api\MembershipController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subscription_uniid',
        'subscription_name',
        'subscription_name_arabic',
        'subscription_duration',
        'subscription_status',
        'subscription_description',
    ];

    public function membership()
    {
        return $this->hasMany(MembershipSubscription::class, 'subscription_id');
    }
}
