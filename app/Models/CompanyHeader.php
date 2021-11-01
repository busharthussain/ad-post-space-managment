<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyHeader extends Model
{

    protected $fillable = ['company_id', 'CF1', 'CF', 'CF2'];

     protected $table = 'company_user_headers';
     protected $primaryKey = 'id';

     public function company()
     {
     	return $this->belongsTo('App\Models\Company','company_id','id');
    	}

}
