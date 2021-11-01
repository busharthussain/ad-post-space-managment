<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCustomValues extends Model
{
    protected $fillable = ['user_id','company_id' ,'CF1', 'CF', 'CF2'];

     protected $table = 'user_custom_headers';
     protected $primaryKey = 'id';

     public function User()
     {
     	return $this->belongsTo('App\Models\User','user_id','id');
    	
    	}
}
