<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChangeLog extends Model
{
    protected $fillable = ['id', 'message', 'created_by'];

    /**
     * This is used to get change logs
     *
     * @return mixed
     */
    public static function getChangeLogs()
    {
        $sql = \DB::table('change_logs as cl')
            ->select('u.name', 'cl.message', 'u.id as user_id',
                \DB::raw("DATE_FORMAT(cl.created_at, '%d-%m-%Y "._lang('at')." %h:%i %p') as created_at")
            );
        $sql->join('users as u', 'cl.created_by', 'u.id');
        if (!isAdminRole()) {
            $sql->where('cl.created_by', '=', loginId());
        }
        $sql->orderBy('cl.id', 'desc');

        return $sql->get();
    }

}
