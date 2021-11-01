<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationManagement extends Model
{
    protected $fillable = ['option', 'type', 'device_type', 'subject', 'description', 'ids', 'created_by'];

    /**
     * This is used to get notifications
     *
     * @param $params
     * @return array
     */
    public static function getNotifications($params)
    {
        $sql = \DB::table('notification_managements as nm')
            ->select(
                'nm.id', 'nm.subject', 'nm.description', 'nm.option', 'nm.created_by', 'nm.created_at as unFormat_created_at',
                \DB::raw("DATE_FORMAT(nm.created_at, '%d-%m-%Y') as created_at")
            );
        if (empty($params['isApi'])&& !isAdminRole()) {
            $sql->where('created_by', '=', loginId());
        }
        if (!empty($params['isApi'])) {
            $sql->where('nm.option', '!=', 'email');
            $sql->whereRaw('FIND_IN_SET(?,user_ids)', [$params['user_id']]);
        }
        if (!empty($params['search'])) {
            $search = '%' . $params['search'] . '%';
            $sql->where(function ($query) use ($search) {
                $query->Where('nm.subject', 'LIKE', $search);
            });
        }

        if (!empty($params['sortColumn']) && !empty($params['sortType'])) {
            $sql->orderBy($params['sortColumn'], $params['sortType']);
        }
        if (!empty($params['isApi'])) {
            $sql->orderBy('nm.created_at', 'desc');
        }

        $grid = [];
        $grid['query'] = $sql;
        $grid['perPage'] = $params['perPage'];
        $grid['page'] = $params['page'];
        $grid['gridFields'] = self::gridFields();

        return \Grid::runSql($grid);
    }

    /**
     * This is used to get notification count
     *
     * @param $params
     * @return mixed
     */
    public static function getNotificationCount($params)
    {
        $userId = $params['user_id'];
        $sql = \DB::table('notification_managements as nm')
            ->select(
                'nm.id'
            );
        $sql->where('nm.option', '=', 'notification');
        $sql->whereRaw('FIND_IN_SET(?,user_ids)', [$params['user_id']]);
//        $sql->whereRaw("FIND_IN_SET($userId,read_user_ids) = 0");
        $sql->whereRaw('NOT FIND_IN_SET(?,read_user_ids)', [$params['user_id']]);

        return $sql->count();
    }

    /**
     * This is used to get notifications
     *
     * @param $option
     * @return int
     */
    public static function getCount($option, $companyId = '')
    {
        $sql = \DB::table('notification_managements as nm')->where('nm.option', '=', $option);
        if (!isAdminRole()) {
            $sql->where('nm.created_by', '=', loginId());
        }
        if (!empty($companyId)) {
            $sql->join('users as u', 'u.id', '=', 'nm.created_by');
            $sql->join('companies as c', 'c.id', 'u.parent_id');
            $sql->where('c.id', '=', $companyId);
        }

        return $sql->count();
    }

    /**
     * This is used to return cammp grid fields
     *
     * @return array
     */
    public static function gridFields()
    {
        $arrFields = [
            'subject' => [
                'name' => 'subject',
                'isDisplay' => true,
                'custom' => [
                    'isAnchor' => true,
                    'url' => \URL::route('super.admin.edit.notification')
                ]
            ],
            'option' => [
                'name' => 'option',
                'isDisplay' => true
            ],
            'created_at' => [
                'name' => 'created_at',
                'isDisplay' => true
            ]
        ];

        return $arrFields;
    }
}
