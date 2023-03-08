<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisibleMembership extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'offer',
        'plan',
    ];
    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan');
    }
}
