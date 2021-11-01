<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'sur_name', 'sex', 'email', 'password', 'parent_id','type', 'status', 'active', 'device_token', 'device_type', 'mobile_number', 'city', 'postal_code',
        'date_of_birth', 'image', 'relative_path', 'authorization_token', 'community_id', 'is_social', 'facebook_id', 'interest_tags', 'looking_tags', 'is_login'
    ];

     public function headers()
     {
        return $this->hasOne('App\Models\UserCustomValues','user_id');
        }
    public function community()
    {
        return $this->hasMany('App\Models\CommunityUser','user_id');
    }
    /**
     * This is used to fetch report posts data
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function reportPosts()
    {
        return $this->belongsToMany('App\Models\Post', 'post_report', 'user_id', 'post_id')->withTimestamps();
    }

    public function postStartConversation()
    {
        return $this->belongsToMany('App\Models\Post', 'post_start_conversation', 'user_id', 'post_id')->withPivot('id', 'message','receiver_id', 'image_1', 'image_2', 'image_3', 'is_request')->withTimestamps();;
    }

/**
     * This is used to fetch report posts data
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favouritePosts()
    {
        return $this->belongsToMany('App\Models\Post', 'post_favourite', 'user_id', 'post_id')->withTimestamps();
    }

    /**
     * This is used to get user join communities
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function joinCommunities()
    {
        return $this->belongsToMany('App\Models\Community', 'community_users', 'user_id', 'community_id')->withTimestamps();
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * This is use to get sub admin data
     *
     * @param $params
     * @return array
     */
    public static function getSubAdmins($params)
    {
        $sql = \DB::table('users as u')->where('type', '=', subAdminType)->select('u.id', 'u.name', 'u.email', 'u.status', 'u.active');
        if (!empty($params['companyId'])) {
            $sql->where('parent_id', '=', $params['companyId']);
        }

        if (!empty($params['search'])) {
            $search = '%' . $params['search'] . '%';
            $sql->where(function ($query) use ($search) {
                $query->Where('u.name', 'LIKE', $search)
                    ->orWhere('u.email', 'LIKE', $search);
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
     * This is used to show stats grid fields
     *
     * @return array
     */
    public static function userGridFields()
    {
        $arrFields = [
            'name' => [
                'name' => 'name',
                'isDisplay' => true,
                'custom' => [
                    'isAnchor' => true,
                    'viewOnly' => false,
                    'url' => \URL::route('user.detail')
                ]
            ],
            'sur_name' => [
                'name' => 'sur_name',
                'isDisplay' => true
            ],
            'email' => [
                'name' => 'email',
                'isDisplay' => true
            ],
            'OS' => [
                'name' => 'os',
                'isDisplay' => true
            ],
            'Mobile Number' => [
                'name' => 'mobile_number',
                'isDisplay' => true
            ],
            'device_type' => [
                'name' => 'device_type',
                'isDisplay' => true
            ]
        ];

        return $arrFields;
    }
     /**
     * This is used to get App user
     *
     * @param $params
     * @return array
     */
    public static function getCommunityUser($params)
    {
        $sql = \DB::table('users as u')->where('type', '=', AppUserType)
            ->select(
                'u.id','u.image' ,'u.name', 'u.sur_name', 'u.email', 'u.status', 'u.active','u.postal_code', 'u.device_type', 'u.city', 'u.mobile_number', 'u.created_at', 'date_of_birth',
                \DB::raw("(CEIL(DATEDIFF(CURRENT_DATE, STR_TO_DATE(u.date_of_birth, '%Y-%m-%d'))/365)) as age"),
                \DB::raw("DATE_FORMAT(u.created_at, '%d-%m-%Y ') as start_date")
                )->join("community_users AS t2", "u.id","=", "t2.user_id") ->where("t2.community_id", "=",$params['community_id']);

        if(!empty($params['isExcel'])) {
            $sql;
        }
       
       if (!empty($params['device_type'])) {
            $sql->where('u.device_type', '=', $params['device_type']);
        }

        
        if (!empty($params['search'])) {
            $search = '%' . $params['search'] . '%';
            $sql->where(function ($query) use ($search) {
                $query->Where('u.name', 'LIKE', $search)
                    ->orWhere('u.email', 'LIKE', $search);
            });
        }

        if (!empty($params['sortColumn']) && !empty($params['sortType'])) {
            $sql->orderBy($params['sortColumn'], $params['sortType']);
        }

        $sql->groupBy('u.id');

        $grid = [];
        $grid['query'] = $sql;
        $grid['perPage'] = $params['perPage'];
        $grid['page'] = $params['page'];
        $grid['gridFields'] = self::appUsersGridFields();
        
        return \Grid::runSql($grid);
    }
    
    /**
     * This is used to get App user
     *
     * @param $params
     * @return array
     */
    public static function getCompanyCommunityUsers($params)
    {
        $sql = \DB::table('users as u')->where('type', '=', AppUserType)
            ->select(
                'u.id','u.image' ,'u.name', 'u.sur_name', 'u.email', 'u.status', 'u.active','u.postal_code', 'u.device_type', 'u.city', 'u.mobile_number', 'u.created_at', 'date_of_birth','t2.CF1','t2.CF2','t2.CF3',
                \DB::raw("(CEIL(DATEDIFF(CURRENT_DATE, STR_TO_DATE(u.date_of_birth, '%Y-%m-%d'))/365)) as age")
                )->leftjoin("user_custom_headers AS t2", "u.id","=", "t2.user_id")->where("t2.company_id", "=",$params['company_id'])
                ->join("community_users AS t3", "u.id","=", "t3.user_id") ;

        if(!empty($params['isExcel'])) {
            $sql;
        }
       
       if (!empty($params['search'])) {
            $search = '%' . $params['search'] . '%';
            $sql->where(function ($query) use ($search) {
                $query->Where('u.name', 'LIKE', $search)
                    ->orWhere('u.email', 'LIKE', $search);
            });
        }

        if (!empty($params['sortColumn']) && !empty($params['sortType'])) {
            $sql->orderBy($params['sortColumn'], $params['sortType']);
        }

        $sql->groupBy('u.id');

        $grid = [];
        $grid['query'] = $sql;
        $grid['perPage'] = $params['perPage'];
        $grid['page'] = $params['page'];
        $grid['gridFields'] = self::CompanyCommunityGridFields();

        return \Grid::runSql($grid);
    }
      /**
     * This is used to show stats grid fields
     *
     * @return array
     */
    public static function CompanyCommunityGridFields()
    {
        $arrFields = [
             'image' => [
                'name' => 'image',
                'isDisplay' => true,
                'custom' => [
                    'width' => '7%',
                    'image' => true,
                    'imageURL' => asset(userProfileImage)
                 ]
                ],
            'name' => [
                'name' => 'name',
                'isDisplay' => true,
                'custom' => [
                    'isAnchor' => true,
                    'viewOnly' => false,
                    'url' => \URL::route('user.stats.detail')
                ]
            ],
            'email' => [
                'name' => 'email',
                'isDisplay' => true
            ],
            'mobile_number' => [
                'name' => 'mobile_number',
                'isDisplay' => true
            ],
            'created_at' => [
                'name' => 'created_at',
                'isDisplay' => true
            ],
            'CF1' => [
                'name' => 'CF1',
                'isDisplay' => true
            ],
            'CF2' => [
                'name' => 'CF2',
                'isDisplay' => true
            ],
            'CF3' => [
                'name' => 'CF3',
                'isDisplay' => true
            ]
        ];

        return $arrFields;
    }
     /**
     * This is used to get App user
     *
     * @param $params
     * @return array
     */
    public static function getAppUser($params)
    {
        $sql = \DB::table('users as u')->where('type', '=', AppUserType)
            ->select(
                'u.id','u.image' ,'u.name', 'u.sur_name', 'u.email', 'u.status', 'u.active','u.postal_code', 'u.device_type', 'u.city', 'u.mobile_number', 'u.created_at', 'date_of_birth',
                \DB::raw("(CEIL(DATEDIFF(CURRENT_DATE, STR_TO_DATE(u.date_of_birth, '%Y-%m-%d'))/365)) as age"),
                 \DB::raw("DATE_FORMAT(u.created_at, '%d-%m-%Y ') as start_date")
           );

        if(!empty($params['isExcel'])) {
            $sql;
        }
       
       if (!empty($params['device_type'])) {
            $sql->where('u.device_type', '=', $params['device_type']);
        }

        
        if (!empty($params['search'])) {
            $search = '%' . $params['search'] . '%';
            $sql->where(function ($query) use ($search) {
                $query->Where('u.name', 'LIKE', $search)
                    ->orWhere('u.email', 'LIKE', $search);
            });
        }

        if (!empty($params['sortColumn']) && !empty($params['sortType'])) {
            $sql->orderBy($params['sortColumn'], $params['sortType']);
        }

        $sql->groupBy('u.id');

        $grid = [];
        $grid['query'] = $sql;
        $grid['perPage'] = $params['perPage'];
        $grid['page'] = $params['page'];
        $grid['gridFields'] = self::appUsersGridFields();

        return \Grid::runSql($grid);
    }
     /**
     * This is used to show stats grid fields
     *
     * @return array
     */
    public static function appUsersGridFields()
    {
        $arrFields = [
             'image' => [
                'name' => 'image',
                'isDisplay' => true,
                'custom' => [
                    'width' => '7%',
                    'image' => true,
                    'imageURL' => asset(userProfileImage)
                 ]
                ],
            'name' => [
                'name' => 'name',
                'isDisplay' => true,
                'custom' => [
                    'isAnchor' => true,
                    'viewOnly' => false,
                    'url' => \URL::route('user.stats.detail')
                ]
            ],
            'sur_name' => [
                'name' => 'sur_name',
                'isDisplay' => true
            ],
            'email' => [
                'name' => 'email',
                'isDisplay' => true
            ],
            'age' => [
                'name' => 'age',
                'isDisplay' => true
            ],
            'mobile_number' => [
                'name' => 'mobile_number',
                'isDisplay' => true
            ],
            'city' => [
                'name' => 'city',
                'isDisplay' => true
            ],
            'postal_code' => [
                'name' => 'postal_code',
                'isDisplay' => true
            ],
            'start_date' => [
                'name' => 'start_date',
                'isDisplay' => true
            ],
            'device_type' => [
                'name' => 'device_type',
                'isDisplay' => true
            ]
        ];

        return $arrFields;
    }

    /**
     * This is used to get user stats
     *
     * @param $params
     * @return array
     */
    public static function getUserStats($params)
    {
        $sql = \DB::table('users as u')->where('type', '=', AppUserType)
            ->select(
                'u.id', 'u.name', 'u.sur_name', 'u.email', 'u.status', 'u.active', 'u.device_type', 'u.city', 'u.mobile_number', 'date_of_birth',
                \DB::raw("(CEIL(DATEDIFF(CURRENT_DATE, STR_TO_DATE(u.date_of_birth, '%Y-%m-%d'))/365)) as age"),
                \DB::raw('(select count(Distinct(cp.id)) from community_users as cu inner join communities as cm on cm.id = cu.community_id inner join companies as cp on cp.id = cm.company_id   where cu.user_id  =   u.id) as total_companies'),
                \DB::raw('(select count(*) from community_users as cu where cu.user_id  =   u.id ) as total_communities'),
                \DB::raw('(select count(p.id) from posts as p where p.created_by  =   u.id ) as total_posts')
            );

        if(!empty($params['isExcel'])) {
            $sql->addSelect(
                \DB::raw('(select count(p.id) from posts as p where p.created_by  =   u.id) as total_posts'),
                \DB::raw('(select count(p.id) from posts as p where p.created_by  =   u.id and p.parent_category_id = 1) as total_swap_posts'),
                \DB::raw('(select count(p.id) from posts as p where p.created_by  =   u.id and p.parent_category_id = 2) as total_borrow_posts'),
                \DB::raw('(select count(p.id) from posts as p where p.created_by  =   u.id and p.parent_category_id = 3) as total_wanted_posts'),
                \DB::raw('(select count(p.id) from posts as p where p.created_by  =   u.id and p.parent_category_id = 4) as total_giveaway_posts'),
                \DB::raw('(select count(cp.id) from companies as cp inner join communities as c on c.company_id = cp.id inner join community_users as cu on c.id = cu.community_id  where cu.user_id  =   u.id) as total_companies')
            );
        }
        if (!empty($params['arrCommunities'])) {
            $sql->join('community_users as cu', 'cu.user_id', 'u.id');
            $sql->whereIn('cu.community_id', $params['arrCommunities']);
        }

        if (!empty($params['arrCompanies']) && empty($params['arrCommunities'])) {
            $sql->join('community_users as cu', 'cu.user_id', 'u.id');
            $sql->join('communities as c', 'c.id', 'cu.community_id');
            $sql->whereIn('c.company_id', $params['arrCompanies']);
        }

        if (!empty($params['device_type'])) {
            $sql->where('u.device_type', '=', $params['device_type']);
        }

        if (!empty($params['start_age']) && !empty($params['end_age'])) {
//            $sql->where('age', '>=', $params['start_age'])->where('age', '<=', $params['end_age']);
            $sql->whereRaw('(CEIL(DATEDIFF(CURRENT_DATE, STR_TO_DATE(u.date_of_birth, \'%Y-%m-%d\'))/365)) >= '.$params['start_age'])->whereRaw('(CEIL(DATEDIFF(CURRENT_DATE, STR_TO_DATE(u.date_of_birth, \'%Y-%m-%d\'))/365)) <= '.$params['end_age']);
        }

        if (!empty($params['search'])) {
            $search = '%' . $params['search'] . '%';
            $sql->where(function ($query) use ($search) {
                $query->Where('u.name', 'LIKE', $search)
                    ->orWhere('u.email', 'LIKE', $search);
            });
        }

        if (!empty($params['sortColumn']) && !empty($params['sortType'])) {
            $sql->orderBy($params['sortColumn'], $params['sortType']);
        }

        $sql->groupBy('u.id');

        $grid = [];
        $grid['query'] = $sql;
        $grid['perPage'] = $params['perPage'];
        $grid['page'] = $params['page'];
        $grid['gridFields'] = self::statsGridFields();

        return \Grid::runSql($grid);
    }

    /**
     * This is used to show stats grid fields
     *
     * @return array
     */
    public static function statsGridFields()
    {
        $arrFields = [
            'name' => [
                'name' => 'name',
                'isDisplay' => true,
                'custom' => [
                    'isAnchor' => true,
                    'viewOnly' => false,
                    'url' => \URL::route('user.stats.detail')
                ]
            ],
            'sur_name' => [
                'name' => 'sur_name',
                'isDisplay' => true
            ],
            'email' => [
                'name' => 'email',
                'isDisplay' => true
            ],
            'age' => [
                'name' => 'age',
                'isDisplay' => true
            ],
            'total_companies' => [
                'name' => 'total_companies',
                'isDisplay' => true
            ],
            'total_communities' => [
                'name' => 'total_communities',
                'isDisplay' => true
            ],
            'total_posts' => [
                'name' => 'total_posts',
                'isDisplay' => true
            ],
            'device_type' => [
                'name' => 'device_type',
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
            'name' => [
                'name' => 'name',
                'isDisplay' => true,
                'custom' => [
                    'isAnchor' => true,
                    'url' => \URL::route('super.admin.edit.sub')
                ]
            ],
            'email' => [
                'name' => 'email',
                'isDisplay' => true
            ],
            'status' => [
                'name' => 'status',
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
     /**
     * This is used to return User grid fields
     *
     * @return array
     */
    public static function companyGridFields()
    {
        $arrFields = [
            'name' => [
                'name' => 'name',
                'isDisplay' => true,
                'custom' => [
                    'isAnchor' => true,
                    'url' => \URL::route('super.admin.edit.company')
                ]
            ],
            'communities' => [
                'name' => 'communities',
                'isDisplay' => true,
            ],
            'users' => [
                'name' => 'users',
                'isDisplay' => true
            ]
        ];

        return $arrFields;
    }
}
