<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class Post extends Model
{
    protected $fillable = ['title', 'description', 'zip_code', 'city', 'category_id', 'parent_category_id', 'product_condition_id', 'is_completed'];

    /**
     * This is used to get tags against post
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tags()
    {
        return $this ->hasMany('App\Models\Tag', 'post_id');
    }

    /**
     * This is used to get images against post
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images()
    {
        return $this ->hasMany('App\Models\PostImages', 'post_id');
    }

    /**
     * This is used to get categories against post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany('App\Models\Category', 'category_post', 'post_id', 'category_id');
    }

    /**
     * This is used to get companies
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companies()
    {
        return $this->belongsToMany('App\Models\Company', 'company_post', 'post_id', 'company_id');
    }

    /**
     * This is used to get communities
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function communities()
    {
        return $this->belongsToMany('App\Models\Community', 'community_post', 'post_id', 'community_id');
    }

    /**
     * This is used to get post data
     *
     * @param $params
     * @return array
     */
    public static function getPostsData($params)
    {
        $sql = \DB::table('posts as p')->select(
            'p.id','p.title', 'p.description', 'p.city', 'p.zip_code', 'p.parent_category_id', 'pc.title as parent_category_name', 'c.name as category_name', 'p.active','u.name as posted_by',
            \DB::raw("DATE_FORMAT(p.created_at, '%d-%m-%Y') as created_at"), 'p.is_completed',
            'prt.message as is_report', 'p.created_by', 'p.borrow_to', 'p.borrow_from', 'pcd.name as product_condition',
            'c.image as category_image', 'pf.favourite',
            \DB::Raw(' IFNULL( `psc`.`is_request` , 0 ) as is_request')
        )
            ->join('parent_categories as pc', 'pc.id', '=', 'p.parent_category_id')
            ->join('categories as c', 'c.id', 'p.category_id')
            ->join('users as u', 'u.id', 'p.created_by');

        if (!empty($params['is_my_posts'])) {
            $sql->where('p.created_by', '=', $params['user_id']);
        }
        if(!empty($params['parent_category_id']) && $params['parent_category_id'] > 0) {
            $sql->where('p.is_completed', '=', 0);
        }
        if (!empty($params['is_borrow_items'])) {
            $sql->where('p.is_completed', '=', 1);
            $sql->where('p.parent_category_id', '=', 2);
            $sql->where('p.receiver_id', '=', $params['user_id']);
        }

        if (!empty($params['community_id'])) {
            $sql->join('community_post as cmp', function ($join) use ($params) {
                $join->on('cmp.post_id', '=', 'p.id')
                    ->whereIn('cmp.community_id', explode(',', $params['community_id']));
            });
        }

        if (!empty($params['company_id'])) {
            $sql->join('company_post as cp', function ($join) use ($params) {
                $join->on('cp.post_id', '=', 'p.id')
                    ->where('cp.company_id', '=', $params['company_id']);
            });
        }

        if (!empty($params['user_id'])) {
            $sql->leftjoin('post_report as prt', function ($join) use ($params) {
                $join->on('prt.post_id', '=', 'p.id')
                    ->where('prt.user_id', '=', $params['user_id']);
            });

            $join = 'leftjoin';
            if (!empty($params['is_request_posts'])) {
                $join = 'join';
            }

            $sql->$join('post_start_conversation as psc', function ($join) use ($params) {
                $join->on('psc.post_id', '=', 'p.id')
                    ->where('psc.user_id', '=', $params['user_id']);
            });
        }

        if (!empty($params['favourite'])) {
            $join = 'join';
        } else {
           $join = 'leftjoin';
        }
        $sql->$join('post_favourite as pf', function ($join) use ($params) {
            $join->on('pf.post_id', '=', 'p.id')
                ->where('pf.user_id', '=', $params['user_id']);
        });

        if (!empty($params['post_id'])) {
            $sql->where('p.id', '=', $params['post_id']);
        }

        $sql->leftjoin('product_conditions as pcd', 'pcd.id', 'p.product_condition_id');
        if (!empty($params['category_id']))
            $sql->where('p.category_id', '=', $params['category_id']);
        if (!empty($params['parent_category_id']))
            $sql->where('p.parent_category_id', '=', $params['parent_category_id']);

        if (!empty($params['search'])) {
            $sql->leftjoin('tags as t', 't.post_id', 'p.id');
            $search = '%' . $params['search'] . '%';
            $sql->where(function ($query) use ($search) {
                $query->Where('p.title', 'LIKE', $search)
                    ->orWhere('pc.title', 'LIKE', $search)
                    ->orWhere('p.description', 'LIKE', $search)
                    ->orWhere('c.name', 'LIKE', $search)
                    ->orWhere('p.city', 'LIKE', $search)
                    ->orWhere('p.zip_code', 'LIKE', $search)
                    ->orWhere('t.name', 'LIKE', $search);
            });
        }

        if (!empty($params['parent_category_id']) || !empty($params['search'])) {
            $sql->where(function ($query) {
                $query->where('p.borrow_to', '>=', date('Y-m-d'))
                    ->where('p.borrow_from', '<=', date('Y-m-d'))
                    ->orwhere('p.parent_category_id', '!=', 2);
            });
        }

        if (!empty($params['favourite'])) {
            $sql->orderBy('pf.created_at', 'desc');
        } else {
            $sql->orderBy('p.created_at', 'desc');
        }

        $sql->groupBy('p.id');
//        echo $sql->toSql(); exit;
        $grid = [];
        $grid['query'] = $sql;
        $grid['perPage'] = $params['perPage'];
        $grid['page'] = $params['page'];
        $grid['gridFields'] = self::companyGridFields();
//        dd(\Grid::runSql($grid));
        return \Grid::runSql($grid);
    }

    /**
     * This is used to get user product list
     *
     * @param $params
     * @return array
     */
    public static function getUserProductList($params)
    {
        $sql = \DB::table('posts as p')->select(
            'p.id', 'p.title', 'p.description', 'p.city', 'p.zip_code', 'p.parent_category_id', 'pc.title as parent_category_name', 'c.name as category_name', 'p.active', 'u.name as posted_by',
            \DB::raw("DATE_FORMAT(p.created_at, '%d-%m-%Y') as created_at"), 'p.is_completed',
            'prt.message as is_report', 'p.created_by', 'p.borrow_to', 'p.borrow_from', 'pcd.name as product_condition',
            'c.image as category_image', 'pf.favourite', 'psc.user_id as sender_id', 'psc.receiver_id',
            \DB::Raw(' IFNULL( `psc`.`is_request` , 0 ) as is_request')
        )
            ->join('parent_categories as pc', 'pc.id', '=', 'p.parent_category_id')
            ->join('categories as c', 'c.id', 'p.category_id')
            ->join('users as u', 'u.id', 'p.created_by');

//        $sql->where('p.created_by', '=', $params['user_id']);
        if(!empty($params['parent_category_id']) && $params['parent_category_id'] > 0) {
            $sql->where('p.is_completed', '=', 0);
        }
        $sql->leftjoin('post_report as prt', function ($join) use ($params) {
            $join->on('prt.post_id', '=', 'p.id')
                ->where('prt.user_id', '=', $params['user_id']);
        });
        $join = 'join';
        $sql->$join('post_start_conversation as psc', function ($join) use ($params) {
            $join->on('psc.post_id', '=', 'p.id');
        });
        $userId = $params['user_id'];
        $sql->where(function ($query) use ($userId) {
            $query->where('psc.user_id', '=', $userId)
                ->orWhere('psc.receiver_id', '=', $userId);
        });

        $sql->leftJoin('post_favourite as pf', function ($join) use ($params) {
            $join->on('pf.post_id', '=', 'p.id')
                ->where('pf.user_id', '=', $params['user_id']);
        });

        $sql->leftjoin('product_conditions as pcd', 'pcd.id', 'p.product_condition_id');
        $sql->leftJoin('post_conversations as pco', 'pco.conversation_id', 'psc.id');

//        $sqlUnion = \DB::table('post_start_conversation as psc')->select(
//            'p.id', 'p.title', 'p.description', 'p.city', 'p.zip_code', 'p.parent_category_id', 'pc.title as parent_category_name', 'c.name as category_name', 'p.active', 'u.name as posted_by',
//            \DB::raw("DATE_FORMAT(p.created_at, '%d-%m-%Y') as created_at"),
//            'prt.message as is_report', 'p.created_by', 'p.borrow_to', 'p.borrow_from', 'pcd.name as product_condition',
//            'c.image as category_image', 'pf.favourite',
//            \DB::Raw(' IFNULL( `psc`.`is_request` , 0 ) as is_request')
//        )
//            ->leftjoin('posts as p', 'p.id', 'psc.post_id')
//            ->join('parent_categories as pc', 'pc.id', '=', 'p.parent_category_id')
//            ->join('categories as c', 'c.id', 'p.category_id')
//            ->join('users as u', 'u.id', 'p.created_by');
//
//        $sqlUnion->where('psc.user_id', '=', $params['user_id']);
//
//        $sqlUnion->leftjoin('post_report as prt', function ($join) use ($params) {
//            $join->on('prt.post_id', '=', 'p.id')
//                ->where('prt.user_id', '=', $params['user_id']);
//        });
//
//        $sqlUnion->leftJoin('post_favourite as pf', function ($join) use ($params) {
//            $join->on('pf.post_id', '=', 'p.id')
//                ->where('pf.user_id', '=', $params['user_id']);
//        });
//
//        $sqlUnion->leftjoin('product_conditions as pcd', 'pcd.id', 'p.product_condition_id');

//        $sql->latest('pco.created_at');
//        $sql->get()->unique('p.id');

        $sql->orderBy('psc.created_at', 'desc');
//        $sql->distinct();
//        $sql->groupBy('p.id');
//        $sql->latest('pco.created_at', 'desc');
        $grid = [];
        $grid['query'] = $sql;
//        $grid['perPage'] = $params['perPage'];
        $grid['perPage'] = 500000000000;
        $grid['page'] = $params['page'];
        $grid['gridFields'] = self::companyGridFields();
        $result = \Grid::runSql($grid);
        if (!empty($result['result'])) {
            $record = [];
            foreach ($result['result'] as $row) {
                $record[$row->id] = $row;
            }
            $result['result'] = $record;
        }

        return $result;


        $items = $sql->union($sqlUnion)->get();

        $perPage = $params['perPage'];

// Start displaying items from this number;
        $offSet = ($params['page'] * $perPage) - $perPage; // Start displaying items from this number

// Get only the items you need using array_slice (only get 10 items since that's what you need)
        $itemsForCurrentPage = array_slice($items->toArray(), $offSet, $perPage, true);

// Return the paginator with only 10 items but with the count of all items and set the it on the correct page
        $data = new \Illuminate\Pagination\LengthAwarePaginator($itemsForCurrentPage, count($items), $perPage, $params['page']);

        return $data;
    }

//    public static

    /**
     * This is used to get posts
     *
     * @param $params
     * @return array
     */
    public static function getPosts($params)
    {
        $sql = \DB::table('posts as p')->select(
                        'p.id','p.title', 'p.parent_category_id', 'pc.title as parent_category_name', 'c.name as category_name', 'p.active','u.name as posted_by',
                        \DB::raw("DATE_FORMAT(p.created_at, '%d-%m-%Y') as created_at"),
                        \DB::raw('(select image from post_images where post_images.post_id  =   p.id  order by id asc limit 1) as post_image'),
                        \DB::raw('(select wanted_unique_image from post_images where post_images.post_id  =   p.id  order by id asc limit 1) as wanted_unique_image')
                    )
            ->join('parent_categories as pc', 'pc.id', '=', 'p.parent_category_id')
            ->join('categories as c', 'c.id', 'p.category_id')
            ->join('users as u', 'u.id', 'p.created_by');
        if (!empty($params['companyId'])) {
            $companyId = $params['companyId'];
            $sql->join('company_post as cp', function ($join) use ($companyId) {
                $join->on('cp.post_id', '=', 'p.id')
                    ->where('cp.company_id', '=', $companyId);
            });
        }
        if (!empty($params['search'])) {
            $search = '%' . $params['search'] . '%';
            $sql->where(function ($query) use ($search) {
                $query->Where('p.title', 'LIKE', $search)
                    ->orWhere('pc.title', 'LIKE', $search)
                    ->orWhere('c.name', 'LIKE', $search)
                    ->orWhere('u.name', 'LIKE', $search);
            });
        }

        if (!empty($params['sortColumn']) && !empty($params['sortType'])) {
            $sql->orderBy($params['sortColumn'], $params['sortType']);
        }

        $grid = [];
        $grid['query'] = $sql;
        $grid['perPage'] = $params['perPage'];
        $grid['page'] = $params['page'];
        $grid['gridFields'] = self::companyGridFields();
        //dd(\Grid::runSql($grid));
        return \Grid::runSql($grid);
    }

    /**
     * This is used to get reported posts
     *
     * @param $params
     * @return array
     */
    public static function getReportedPosts($params)
    {
        $sql = \DB::table('posts as p')->select(
            'p.id','prt.id as reported_id', 'p.title', 'p.parent_category_id', 'pc.title as parent_category_name', 'p.active', 'u.name as posted_by',
            'prt.message as reported_message', 'prt.user_id as user_id',
            \DB::raw("DATE_FORMAT(p.created_at, '%a, %b %d') as created_at"),
            \DB::raw('(select image from post_images where post_images.post_id  =   p.id  order by id asc limit 1) as post_image'),
            \DB::raw('(select name from users where users.id  =   prt.user_id  ) as reported_by')
        );
        $sql->join('parent_categories as pc', 'pc.id', '=', 'p.parent_category_id');
        $sql->join('users as u', 'u.id', 'p.created_by');

        $sql->join('post_report as prt', function ($join) use ($params) {
            $join->on('prt.post_id', '=', 'p.id');
        });

        if (!isAdminRole()) {
            $sql->join('company_post as cp', function ($join) use ($params) {
                $join->on('cp.post_id', '=', 'p.id')
                    ->where('cp.company_id', '=', getCompanyIdByUser());
            });
        }

        if (!empty($params['sortColumn']) && !empty($params['sortType'])) {
            $sql->orderBy($params['sortColumn'], $params['sortType']);
        }

        $grid = [];
        $grid['query'] = $sql;
        $grid['perPage'] = $params['perPage'];
        $grid['page'] = $params['page'];
        $grid['gridFields'] = self::reportedPostsGridFields();
        return \Grid::runSql($grid);
    }

    /**
     * This is used to get post name by conversation id
     *
     * @param $id
     * @return mixed
     */
    public static function getPostName($id)
    {
        $sql = \DB::table('post_start_conversation as psc')->select('p.title', 'u.name');
        $sql->join('posts as p', 'p.id', 'psc.post_id');
        $sql->join('users as u', 'u.id', 'psc.receiver_id');
        $sql->where('psc.id', '=', $id);

        return $sql->first();
    }

    /**
     * This is used to get post stats
     *
     * @return array
     */
    public static function getStats($params)
    {
        $sql = \DB::table('posts as p')->select(
            'p.id','p.title', 'p.parent_category_id', 'pc.title as option', 'c.name as category', 'p.active as status','u.name as posted_by',
            \DB::raw("DATE_FORMAT(p.created_at, '%d-%m-%Y') as posted_date"),
            \DB::raw('(select image from post_images where post_images.post_id  =   p.id  order by id asc limit 1) as post_image')
        )
            ->join('parent_categories as pc', 'pc.id', '=', 'p.parent_category_id')
            ->join('categories as c', 'c.id', 'p.category_id')
            ->join('users as u', 'u.id', 'p.created_by');
        if (!empty($params['arrCompanies'])) {
            $companyId = $params['arrCompanies'];
            $sql->join('company_post as cp', function ($join) use ($companyId) {
                $join->on('cp.post_id', '=', 'p.id')
                    ->whereIn('cp.company_id', $companyId);
            });
        }

        if (!empty($params['arrCommunities'])) {
            $communityId = $params['arrCommunities'];
            $sql->join('community_post as cps', function ($join) use ($communityId) {
                $join->on('cps.post_id', '=', 'p.id')
                    ->whereIn('cps.community_id', $communityId);
            });
        }

        if (!empty($params['users'])) {
            $sql->where('p.created_by', $params['users']);
        }

        if (isset($params['posts']) && $params['posts'] != null) {
            if ($params['posts'] == 0) {
                $sql->where('p.active', 1);
            } else {
                $sql->where('p.is_completed', $params['posts']);
            }
        }
        if (!empty($params['option'])) {
            $sql->where('p.parent_category_id', '=', $params['option']);
        }

        if (!empty($params['borrow_to'])) {
            $sql->where('p.created_at', '<=', databaseDateFromat($params['borrow_to']));
        }

        if (!empty($params['borrow_from'])) {
            $sql->where('p.created_at', '>=', databaseDateFromat($params['borrow_from']));
        }

        if(!empty($params['search'])) {
            $search = '%' . $params['search'] . '%';
            $sql->where(function ($query) use ($search) {
                $query->Where('p.title', 'LIKE', $search)
                    ->orWhere('pc.title', 'LIKE', $search);
            });
        }

        if (!empty($params['sortColumn']) && !empty($params['sortType'])) {
            $sql->orderBy($params['sortColumn'], $params['sortType']);
        }
        $sql->groupBy('p.id');

        $grid = [];
        $grid['query'] = $sql;
        $grid['perPage'] = $params['perPage'];
        $grid['page'] = $params['page'];
        $grid['gridFields'] = self::statsGridFields();

        return \Grid::runSql($grid);
    }

    /**
     * This is used to get post data
     *
     * @param $parent_category_id
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Collection|int|static[]
     */
    public static function postData($parent_category_id, $type = 'count', $isCompleted = false)
    {
        $sql = Post::where('parent_category_id', '=', $parent_category_id);
        if (!isAdminRole()) {
            $companyId = getCompanyIdByUser();
            $sql->join('company_post as cp', function ($join) use ($companyId) {
                $join->on('cp.post_id', '=', 'posts.id')
                    ->where('cp.company_id', '=', $companyId);
            });
        }
        if (!empty($isCompleted)) {
            $sql->where('is_completed', $isCompleted);
        }
        if ($type == 'count') {
            return $sql->count();
        } else {
            return $sql->get();
        }
    }

    /**
     * This is used to render stats grid fields
     *
     * @return array
     */
    public static function statsGridFields()
    {
        $arrFields = [
            'post_image' => [
                'name' => 'post_image',
                'isDisplay' => true,
                'custom' => [
                    'width' => '7%',
                    'image' => true,
                    'imageURL' => asset(uploadPostThumbNailImage),
                    'imageURLCategory' => asset(uploadWantedImage)
                ]
            ],'title' => [
                'name' => 'title',
                'isDisplay' => true
            ],
            'posted_by' => [
                'name' => 'posted_by',
                'isDisplay' => true
            ],
            'option' => [
                'name' => 'option',
                'isDisplay' => true,
            ],
            'category' => [
                'name' => 'category',
                'isDisplay' => true
            ],
            'posted_date' => [
                'name' => 'posted_date',
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
     * This is used to return cammp grid fields
     *
     * @return array
     */
    public static function companyGridFields()
    {
        $arrFields = [
            'post_image' => [
                'name' => 'post_image',
                'isDisplay' => true,
                'custom' => [
                    'width' => '7%',
                    'image' => true,
                    'imageURL' => asset(uploadPostThumbNailImage),
                    'imageURLCategory' => asset(uploadWantedImage)
                ]
            ],
            'title' => [
                'name' => 'title',
                'isDisplay' => true,
                'custom' => [
                    'isAnchor' => true,
                    'url' => \URL::route('super.admin.edit.post')
                ]
            ],
            'parent_category_name' => [
                'name' => 'parent_category_name',
                'isDisplay' => true,
            ],
            'category_name' => [
                'name' => 'category_name',
                'isDisplay' => true
            ],
            'posted_by' => [
                'name' => 'posted_by',
                'isDisplay' => true
            ],
            'created_at' => [
                'name' => 'created_at',
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

    /**
     * This is used to return cammp grid fields
     *
     * @return array
     */
    public static function reportedPostsGridFields()
    {
        $arrFields = [
            'post_image' => [
                'name' => 'post_image',
                'isDisplay' => true,
                'custom' => [
                    'width' => '7%',
                    'image' => true,
                    'imageURL' => asset(uploadPostThumbNailImage),
                    'imageURLCategory' => asset(uploadWantedImage)
                ]
            ],
            'title' => [
                'name' => 'title',
                'isDisplay' => true,
                'custom' => [
                    'isAnchor' => true,
                    'url' => \URL::route('super.admin.edit.post')
                ]
            ],
            'parent_category_name' => [
                'name' => 'parent_category_name',
                'isDisplay' => true,
            ],
            'posted_by' => [
                'name' => 'posted_by',
                'isDisplay' => true
            ],
            'reported_by' => [
                'name' => 'reported_by',
                'isDisplay' => true
            ]
        ];

        return $arrFields;
    }

}
