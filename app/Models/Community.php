<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    protected $fillable = ['company_id', 'title', 'description', 'image', 'active', 'borrow_image', 'swap_image', 'give_away_image', 'wanted_image', 'relative_path', 'is_lock', 'password', 'qrcode', 'qrcode_image', 'relative_qrcode_path', 'created_by'];

    /**
     * This is used to get communities data
     *
     * @return array
     */
    public static function getCommunities($params)
    {
        $sql = \DB::table('communities as c')->select(
                        'c.id','c.title', 'c.company_id', 'c.qrcode_image as qr_code',
            \DB::raw('(select count(DISTINCT(cu.user_id)) from community_users as cu where cu.community_id = c.id)  as total_users'),
                        \DB::raw('(select count(cp.community_id) from community_post cp where cp.community_id = c.id)  as total_posts')
                );
        $sql->where('company_id', '=', $params['companyId'])->where('active', '=',1);
        if (!empty($params['isAdminRole'])) {
            $sql->orWhere('company_id', '=', 0);
        }
        if (!empty($params['search'])) {
            $search = '%' . $params['search'] . '%';
            $sql->where(function ($query) use ($search) {
                $query->Where('c.title', 'LIKE', $search);
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
     * This is used to get stats data
     *
     * @param $params
     * @return array
     */
    public static function getStats($params)
    {
        $sql = \DB::table('communities as c')->select(
            'c.id','c.title', 'c.company_id',
            'cp.name as company_name',
            \DB::raw('(select count(cp.community_id) from community_post cp where cp.community_id = c.id)  as total_posts'),
            \DB::raw('(select count(cu.community_id) from community_users cu where cu.community_id = c.id)  as total_users')
        );

        $sql->leftjoin('companies as cp', 'cp.id', 'c.company_id');
        if (!empty($params['isAdminRole'])) {
            $sql->orWhere('company_id', '=', 0);
        }
        if (!empty($params['arrCompanies'])) {
            $sql->whereIn('c.company_id', $params['arrCompanies']);
        }
        if (!empty($params['search'])) {
            $search = '%' . $params['search'] . '%';
            $sql->where(function ($query) use ($search) {
                $query->Where('c.title', 'LIKE', $search);
            });
        }

        if (!empty($params['sortColumn']) && !empty($params['sortType'])) {
            $sql->orderBy($params['sortColumn'], $params['sortType']);
        }

        $grid = [];
        $grid['query'] = $sql;
        $grid['perPage'] = $params['perPage'];
        $grid['page'] = $params['page'];
        $grid['gridFields'] = self::statsGridFields();

        return \Grid::runSql($grid);
    }

    /**
     * This is used to get communities for api against user
     *
     * @param $params
     * @return array
     */
    public static function getCommunitiesForApi($params)
    {
        $sql = \DB::table('communities as c')->select(
            'c.*','c.title', 'cu.id as community_user_id', 'cu.is_mark',
            \DB::Raw(' IFNULL( `cu`.`is_allow` , 2 ) as is_allow'),
            \DB::Raw(' IFNULL( `cp`.`name` , "Public" ) as company_name')
        );
        $join = 'leftJoin';
        if (!empty($params['is_joined'])) {
            $join = 'join';
        }
        $sql->$join('community_users as cu', function ($join) use ($params) {
            $join->on('cu.community_id', '=', 'c.id')
                ->where('cu.user_id', '=', $params['user_id']);
            if (!empty($params['is_joined'])) {
                $join->where('cu.is_allow', '=', 1);
            }
        });
        $sql->leftJoin('companies as cp', 'cp.id', 'c.company_id');
        if (!empty($params['search'])) {
            $search = '%' . $params['search'] . '%';
            $sql->where(function ($query) use ($search) {
                $query->Where('c.title', 'LIKE', $search)
                    ->orWhere('c.description', 'LIKE', $search)
                    ->orWhere('cp.name', 'LIKE', $search);
            });
        }
        $sql->orderBy('cu.created_at', 'desc');

        return $sql->get()->toArray();
    }

    /**
     * This is used to get companies by regions
     *
     * @param $params
     * @return \Illuminate\Support\Collection
     */
    public static function getCommunitiesByCompany($companies, $isPublicCommunity = true)
    {
        if (!empty($isPublicCommunity))
            $companies = array_merge([0], $companies);

        return Community::whereIn('company_id', $companies)->orderBy('created_at', 'desc')->pluck('title', 'id')->toArray();
    }

    /**
     * This is used to get communities by company id
     *
     * @param $params
     * @return array
     */
    public static function getCommunitiesByCompanyId($params)
    {
        $sql = \DB::table('communities as c')->select(
            'c.*',
            \DB::Raw(' IFNULL( `cu`.`is_allow` , 2 ) as is_allow')
        );
        $sql->leftjoin('community_users as cu', function ($join) use ($params) {
            $join->on('cu.community_id', '=', 'c.id')
                ->where('cu.user_id', $params['user_id']);
        });
        $sql->where('company_id', '=', $params['company_id'])
            ->orWhere('company_id', '=', 0)
            ->orderBy('created_at', 'desc');
        return $sql->get();
    }

    /**
     * This is used to get join communities
     *
     * @param $params
     * @return array
     */
    public static function getJoinCommunities($params)
    {
        $sql = \DB::table('communities as c')->select(
            'c.title as community_name', 'u.name as user_name', 'u.email', 'cu.id',
            \DB::raw("DATE_FORMAT(cu.created_at, '%a, %b %d') as created_at")
        );
        $sql->join('community_users as cu', function ($join) use ($params) {
            $join->on('cu.community_id', '=', 'c.id')
            ->where('cu.is_allow', '=', 0);
        });
        $sql->join('users as u', 'u.id', 'cu.user_id');
        $sql->where('c.company_id', '=', getCompanyIdByUser());
        if (!empty($params['search'])) {
            $search = '%' . $params['search'] . '%';
            $sql->where(function ($query) use ($search) {
                $query->Where('c.title', 'LIKE', $search);
            });
        }

        if (!empty($params['sortColumn']) && !empty($params['sortType'])) {
            $sql->orderBy($params['sortColumn'], $params['sortType']);
        }

        $grid = [];
        $grid['query'] = $sql;
        $grid['perPage'] = $params['perPage'];
        $grid['page'] = $params['page'];
        $grid['gridFields'] = self::gridFieldsJoinCommunities();

        return \Grid::runSql($grid);
    }

    /**
     * This is used to get join communities grid fields
     *
     * @return array
     */
    public static function gridFieldsJoinCommunities()
    {
        $arrFields = [
            'created_at' => [
                'name' => 'created_at',
                'isDisplay' => true
            ],
            'user_name' => [
                'name' => 'user_name',
                'isDisplay' => true
            ],
            'email' => [
                'name' => 'email',
                'isDisplay' => true,
            ],
            'community_name' => [
                'name' => 'community_name',
                'isDisplay' => true
            ]
        ];

        return $arrFields;
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
                'isDisplay' => true,
                'custom' => [
                    'isAnchor' => true,
                    'url' => \URL::route('super.admin.edit.community')
                    ]
            ],
            'total_posts' => [
                'name' => 'total_posts',
                'isDisplay' => true
            ],
            'total_users' => [
                'name' => 'total_users',
                'isDisplay' => true,
            ],
            'qr_code' => [
                'name' => 'qr_code',
                'isDisplay' => true,
                'custom' => [
                    'isDownloadLink' => true
                ]
            ]
        ];

        return $arrFields;
    }

    /**
     * This is used to get stats
     *
     * @return array
     */
    public static function statsGridFields()
    {
        $arrFields = [
            'title' => [
                'name' => 'title',
                'isDisplay' => true
            ],
            'company_name' => [
                'name' => 'company_name',
                'isDisplay' => true
            ],
            'total_users' => [
                'name' => 'total_users',
                'isDisplay' => true,
            ],
            'total_posts' => [
                'name' => 'total_posts',
                'isDisplay' => true,
            ]
        ];

        return $arrFields;
    }

}
