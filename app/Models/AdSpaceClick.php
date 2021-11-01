<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdSpaceClick extends Model
{
    protected $fillable = ['ad_space_id', 'clicked_by', 'count'];

    public static function getAdSpaceClicks($params)
    {
        $sql = \DB::table('ad_spaces as a')->select(
            'a.id','a.title', 'count'
        );
        $sql->join('ad_space_clicks as asc', 'asc.ad_space_id', 'a.id');
        if (!empty($params['search'])) {
            $search = '%' . $params['search'] . '%';
            $sql->where(function ($query) use ($search) {
                $query->Where('a.title', 'LIKE', $search);
            });
        }

        if (!empty($params['sortColumn']) && !empty($params['sortType'])) {
            $sql->orderBy($params['sortColumn'], $params['sortType']);
        }

        $grid = [];
        $grid['query'] = $sql;
        $grid['perPage'] = $params['perPage'];
        $grid['page'] = $params['page'];
        $grid['gridFields'] = self::gridFields();

        return \Grid::runSql($grid);
    }

    /**
     * This is used to return cammp grid fields
     *
     * @return array
     */
    public static function gridFields()
    {
        $arrFields = [
            'title' => [
                'name' => 'title',
                'isDisplay' => true
            ],
            'count' => [
                'name' => 'count',
                'isDisplay' => true
            ]
        ];

        return $arrFields;
    }


}
