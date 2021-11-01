<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostImages extends Model
{
    protected $fillable = ['image', 'thumbnail_image', 'post_id', 'batch_id'];
}
