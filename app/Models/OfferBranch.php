<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferBranch extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'offer',
        'branch',
        'coupons',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch');
    }
}
