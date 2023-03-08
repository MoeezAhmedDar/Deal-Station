<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferNotification extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message',
        'offer',
        'plan',
        'status'
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer', 'offer_uniid');
    }
}
