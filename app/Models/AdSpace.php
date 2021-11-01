<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdSpace extends Model
{

    protected $fillable = ['title', 'link', 'start_time', 'end_time', 'created_by', 'active', 'type', 'parent_category_id'];

    /**
     * This is used to get images against post
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this ->hasMany('App\Models\AdSpaceImage', 'ad_id');
    }

    /**
     * This is used to get companies
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companies()
    {
        return $this->belongsToMany('App\Models\Company', 'ad_company', 'ad_id', 'company_id');
    }

    /**
     * This is used to get communities
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function communities()
    {
        return $this->belongsToMany('App\Models\Community', 'ad_community', 'ad_id', 'community_id');
    }

    /**
     * This is used to get ads
     *
     * @return array
     */
    public static function getAds($params)
    {
        $sql = \DB::table('ad_spaces as a')->select(
            'a.id','a.title','a.active', 'a.count as total_clicks', 'a.type', 'pc.title as option',
            \DB::raw("DATE_FORMAT(a.start_time, '%d-%m-%y %H:%i') as start_time"),
            \DB::raw("DATE_FORMAT(a.end_time, '%d-%m-%y %H:%i') as end_time"),
            \DB::raw('(select image from ad_space_images as asi where asi.ad_id  = a.id  order by id asc limit 1) as post_image')
        );
        $sql->leftJoin('parent_categories as pc', 'pc.id', 'a.parent_category_id');
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
     * This is used to get ads data against community
     *
     * @param $params
     * @return array
     */
    public static function getAdsForApi($params)
    {
        $sql = \DB::table('ad_spaces as a')->select(
            'a.id','a.title','a.active', 'a.count as total_clicks', 'a.type', 'pc.title as option',
            'a.start_time', 'a.end_time'
        );
        $sql->leftJoin('parent_categories as pc', 'pc.id', 'a.parent_category_id');
        $sql->join('ad_community as adc', function ($join) use ($params) {
            $join->on('adc.ad_id', '=', 'a.id')
                ->where('adc.community_id', '=', $params['community_id']);
        });

        $grid = [];
        $grid['query'] = $sql;
        $grid['perPage'] = 2;
        $grid['page'] = $params['page'];

        return \Grid::runSql($grid);
    }

    /**
     * This is used to get ad space data
     */
    public static function getAdsData($params)
    {
        $sql = \DB::table('ad_spaces as a')->select(
            'a.id', 'a.title', 'a.active', 'a.link', 'a.count as total_clicks', 'a.type', 'pc.title as option',
            'a.start_time', 'a.end_time'
        );
        $sql->leftJoin('parent_categories as pc', 'pc.id', 'a.parent_category_id');
        $sql->where('a.type', '=', $params['type']);
        $sql->where('a.active', '=', 1);
        if (isset($params['parent_category_id'])) {
            $sql->whereIn('a.parent_category_id', explode(',', $params['parent_category_id']));
            $sql->orWhere('a.parent_category_id', '=', 0);
        }

        if (!empty($params['company_id'])) {
            $company_id = $params['company_id'];
            $sql->join('ad_company as acp', function ($join) use ($company_id) {
                $join->on('acp.ad_id', '=', 'a.id')
                    ->whereIn('acp.company_id', explode(',', $company_id));
            });
        }

        if (!empty($params['community_id'])) {
            $community_id = $params['community_id'];
            $sql->join('ad_community as ac', function ($join) use ($community_id) {
                $join->on('ac.ad_id', '=', 'a.id')
                    ->whereIn('ac.community_id', explode(',', $community_id));
            });
        }
        $currentDate = date('Y-m-d h:i');

//        $sql->where(function ($query) use($currentDate) {
//            $query->where('a.start_time', '<=', $currentDate)
//                ->where('a.end_time', '>=', $currentDate);
//        });

        $sql->orderBy('a.created_at', 'desc');
        $sql->groupBy('a.id');

        return $sql->get()->toArray();
    }

    /**
     * This is used to return cammp grid fields
     *
     * @return array
     */
    public static function gridFields()
    {
        $arrFields = [
            'post_image' => [
                'name' => 'post_image',
                'isDisplay' => true,
                'custom' => [
                    'width' => '7%',
                    'image' => true,
                    'imageURL' => asset(uploadAdThumbNailImage),
                ]
            ],
            'title' => [
                'name' => 'title',
                'isDisplay' => true,
                'custom' => [
                    'isAnchor' => true,
                    'url' => \URL::route('super.admin.edit.ad')
                ]
            ],
            'start_time' => [
                'name' => 'start_time',
                'isDisplay' => true
            ],
            'end_time' => [
                'name' => 'end_time',
                'isDisplay' => true
            ],
            'total_clicks' => [
                'name' => 'total_clicks',
                'isDisplay' => true
            ],
            'type' => [
                'name' => 'type',
                'isDisplay' => true,
                'custom' => [
                    'status' => true,
                    'emptyTitle' => _lang('Top'),
                    'value' => _lang('Classified')
                ]
            ],
            'option' => [
                'name' => 'option',
                'isDisplay' => true
            ],
            'active' => [
                'name' => 'active',
                'isDisplay' => true,
                'custom' => [
                    'status' => true,
                    'emptyTitle' => _lang('Deactive'),
                    'value' => _lang('Active')
                ]
            ]
        ];

        return $arrFields;
    }

}
