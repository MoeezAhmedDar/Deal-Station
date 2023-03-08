<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'advertisement_uniid',
        'advertisement_name',
        'advertisement_name_arabic',
        'advertisement_type',
        'advertisement_image',
        'advertisement_status',
        'advertisement_text',
        'advertisement_item_id',
        'advertisement_item'
    ];
}
