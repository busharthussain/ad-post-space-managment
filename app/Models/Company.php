<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['name', 'city', 'zip_code', 'company_number', 'company_address', 'user_id', 'parent_id', 'contact_person', 'region_id', 'is_stat', 'is_notification', 'is_users', 'communities', 'image', 'privacy_document', 'relative_path', 'relative_document_path'];


    public function company()
     {
        return $this->hasOne('App\Models\CompanyHeader','company_id');
        }
    /**
     * This is used to get companies
     *
     * @param $params
     * @return array
     */
    public static function getCompanies($params)
    {
        $sql = Company::select(
            'companies.id','companies.name', 'contact_person',
                    'is_stat', 'image', 'privacy_document',
                    \DB::raw('(select count(c.id) from communities c where c.company_id = companies.id)  as communities'),
                    \DB::raw('(select count(DISTINCT(cu.user_id)) from communities c  join community_users as cu on cu.community_id = c.id  where c.company_id = companies.id)  as users')
                    );
        if (!empty($params['search'])) {
            $search = '%' . $params['search'] . '%';
            $sql->where(function ($query) use ($search) {
                $query->Where('companies.name', 'LIKE', $search)
                    ->orWhere('contact_person', 'LIKE', $search);
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
        $sql = \DB::table('companies as c')->select(
            'c.id','c.name', 'c.user_id', 'c.contact_person', 'c.communities',
            'c.is_stat', 'c.image', 'c.privacy_document', 'u.name as owner',
            \DB::raw('(select count(cu.id) from communities cu where cu.company_id = c.id)  as total_communities'),
            \DB::raw('(select count(Distinct(cus.id)) from community_users cus inner join communities as cmu on cus.community_id = cmu.id  where cmu.company_id = c.id)  as total_users'),
            \DB::raw('(select count(*) from company_post cp where cp.company_id = c.id)  as total_posts')
        );
        $sql->join('users as u', 'u.parent_id', 'c.id');
        if (!isAdminRole()) {
            $sql->where('c.id', '=', getCompanyIdByUser());
        }
        if (!empty($params['search'])) {
            $search = '%' . $params['search'] . '%';
            $sql->where(function ($query) use ($search) {
                $query->Where('c.name', 'LIKE', $search)
                    ->orWhere('c.contact_person', 'LIKE', $search);
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
     * This is used to get companies by regions
     *
     * @param $params
     * @return \Illuminate\Support\Collection
     */
    public static function getArrCompanies()
    {
        return Company::pluck('name', 'id')->toArray();
    }

    /**
     * This is used to get companies data by id
     *
     * @param $id
     * @return array
     */
    public static function getCompaniesData($params)
    {
        return Company::get()->toArray();
    }

    /**
     * This is used to return cammp grid fields
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

    /**
     * This is used to get stats grid fields
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
                    'viewOnly' => false,
                    'isAnchor' => true,
                    'url' => \URL::route('company.stats.detail')
                ]
            ],
            'owner' => [
                'name' => 'owner',
                'isDisplay' => true,
            ],
            'total_communities' => [
                'name' => 'total_communities',
                'isDisplay' => true
            ],
            'total_users' => [
                'name' => 'total_users',
                'isDisplay' => true
            ],
            'total_posts' => [
                'name' => 'total_posts',
                'isDisplay' => true
            ]
        ];

        return $arrFields;
    }
}
