<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantDetail extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'merchant_id',
        'merchant_uniid',
        'merchant_brand',
        'merchant_brand_arabic',
        'merchant_iban',
        'merchant_gov_id',
        'merchant_website',
        'merchant_number',
        'business_owner',
        'merchant_contact_person',
        'merchant_contact_number',
        'merchant_building_address',
        'merchant_str_address',
        'merchant_com_address',
        'merchant_commercial_activity',
        'merchant_tax_number',
        'merchant_logo',
        'merchant_gov_letter',
        'merchant_tax_letter',
        'arabic_business_owner',
        'arabic_contact_person_name',
        'merchant_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }

    public function branches()
    {
        return $this->hasMany(Branch::class, 'merchant_id');
    }
}
