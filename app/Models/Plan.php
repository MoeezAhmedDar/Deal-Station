<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_uniid',
        'plan_name',
        'plan_name_arabic',
        'plan_icon',
        'plan_city',
        'plan_terms',
        'plan_status',
        'plan_description',
    ];

    public function targetedMembership()
    {
        return $this->hasMany(TargetedMembership::class, 'plan');
    }

    public function visibleMembership()
    {
        return $this->hasMany(VisibleMembership::class, 'plan');
    }

    public function planSubscriptions()
    {
        return $this->hasMany(MembershipSubscription::class, 'plan_id');
    }

    public function userSubscriptions()
    {
        return $this->hasMany(UserSubscription::class, 'plan');
    }
}
