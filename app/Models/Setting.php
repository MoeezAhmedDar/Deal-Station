<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_name',
        'app_name_arabic',
        'app_phone',
        'app_email',
        'app_building_address',
        'app_str_address',
        'app_com_address',

        'app_facebook',
        'app_insta',
        'app_twitter',
        'app_pinterest',

        'app_logo_ltr',
        'app_logo_rtl',

        'app_privacy',
        'app_privacy_arabic',
        'app_about',
        'app_about_arabic',

        'default_trial_days',
    ];
}
