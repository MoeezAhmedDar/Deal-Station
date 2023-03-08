<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'campaign_uniid',
        'campaign_name',
        'campaign_name_arabic',
        'campaign_from',
        'campaign_to',
        'campaign_banner',
        'campaign_status'
    ];

    public function offers()
    {
        return $this->hasMany(Offer::class, 'offer_campaign');
    }
}
