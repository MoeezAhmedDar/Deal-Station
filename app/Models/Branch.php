<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'merchant_id',
        'branch_uniid',
        'branch_name',
        'branch_name_arabic',
        'branch_phone',
        'branch_city',
        'branch_building_address',
        'branch_str_address',
        'branch_com_address',
        'branch_status',
        'branch_image',
        'branch_latitude',
        'branch_longitude',
        'branch_cashier',
    ];

    public function merchant()
    {
        return $this->hasOne(MerchantDetail::class, 'merchant_id');
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'branch_cashier');
    }
}
