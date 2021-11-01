<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityUser extends Model
{
    protected $fillable = ['community_id', 'user_id', 'is_allow', 'is_mark'];

    /**
     * This is used to get community joined users
     *
     * @param $params
     * @return mixed
     */
    public function users()
    {
     return $this->belongsTo('App\Models\User','user_id','id');
    }
    public static function getJoinedCommunityUsers($params)
    {
        $sql = \DB::table('community_users as cu')->select(
            'u.email', 'u.device_token', 'u.device_type', 'u.id'
        );
        $sql->join('users as u', 'u.id', 'cu.user_id');
        $sql->where('cu.is_allow', '=', 1);
        $sql->whereIn('cu.community_id', $params['ids']);
        $sql->where('u.active', '=', 1);
        $sql->groupBy('u.email');

        return $sql->get()->toArray();
    }
}
