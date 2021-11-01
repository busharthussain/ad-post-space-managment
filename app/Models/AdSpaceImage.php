<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdSpaceImage extends Model
{
    protected $fillable = ['ad_id', 'batch_id', 'image', 'thumbnail_image', 'width', 'height'];
}
