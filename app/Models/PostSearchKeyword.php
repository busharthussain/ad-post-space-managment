<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostSearchKeyword extends Model
{
    protected $fillable = ['keyword', 'parent_category_id', 'count'];

    /**
     * This is used to get top stats
     *
     * @param $params
     * @return array
     */
    public static function getTopStats($params)
    {
        $sql = \DB::table('post_search_keywords as psk')->select(
            'psk.id','psk.keyword', 'psk.count', 'pc.title as option',
            \DB::raw("DATE_FORMAT(psk.created_at, '%d-%m-%Y') as posted_date")
            )
            ->join('parent_categories as pc', 'pc.id', '=', 'psk.parent_category_id');

        if (!empty($params['option'])) {
            $sql->where('psk.parent_category_id', $params['option']);
        }

        if (!empty($params['sortColumn']) && !empty($params['sortType'])) {
            $sql->orderBy($params['sortColumn'], $params['sortType']);
        }

        if (!empty($params['search'])) {
            $search = '%' . $params['search'] . '%';
            $sql->where(function ($query) use ($search) {
                $query->Where('pc.title', 'LIKE', $search)
                    ->orWhere('psk.keyword', 'LIKE', $search);
            });
        }

        $grid = [];
        $grid['query'] = $sql;
        $grid['perPage'] = $params['perPage'];
        $grid['page'] = $params['page'];
        $grid['gridFields'] = self::statsGridFields();

        return \Grid::runSql($grid);
    }

    /**
     * This is used to render stats grid fields
     *
     * @return array
     */
    public static function statsGridFields()
    {
        $arrFields = [
            'id' => [
                'name' => 'id',
                'isDisplay' => true
            ],
            'keyword' => [
                'name' => 'keyword',
                'isDisplay' => true
            ],
            'option' => [
                'name' => 'option',
                'isDisplay' => true,
            ],
            'count' => [
                'name' => 'count',
                'isDisplay' => true
            ]
        ];

        return $arrFields;
    }

}
