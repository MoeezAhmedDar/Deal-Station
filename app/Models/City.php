<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'city_uniid',
        'city_name',
        'city_name_arabic',
        'city_zip',
        'city_latitude',
        'city_longitude',
        'city_country',
        'city_status',
    ];
}
