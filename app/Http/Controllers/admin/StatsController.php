<?php

namespace App\Http\Controllers\admin;

use App\Models\NotificationManagement;
use App\Models\ParentCategory;
use App\Models\PostSearchKeyword;
use App\Models\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Community;
use App\Models\Post;
use Charts;
use App\Http\Controllers\admin\SharpeepsTrait;

class StatsController extends Controller
{
    use SharpeepsTrait;
    private $page = 'userStats';
    private $sheetName = 'Users';
    private $statsHeaders = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('loginType');
    }

    /**
     * This is used to show user stats
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */ 
    public function userStats(Request $request)
    {
        $this->page = 'userStats';
        $this->isCompanyRole = isCompanyRole();
        $this->isCompanyUserRole = isCompanyUserRole();

        $companies = $arrCompanies = [];
        if ($this->isCompanyRole || $this->isCompanyUserRole) {
            $objCompany = Company::find(getCompanyIdByUser());
            $companies = [$objCompany->id];
            $arrCommunities = Community::getCommunitiesByCompany($companies, false);
            $arrCompanies = Company::getArrCompanies();
            $this->isCompanyOrUserRole = true;
        } else {
            $arrCompanies = Company::getArrCompanies();
            $arrCommunities = Community::getCommunitiesByCompany([]);
        }
        $selectedCompanies = $companies;

        $data = [
            'headers' => $this->userStatsHeaders(),
            'page' => $this->page,
            'isCompanyRole' => $this->isCompanyOrUserRole,
            'arrCompanies' => $arrCompanies,
            'arrCommunities' => $arrCommunities,
            'selectedCompanies' => $selectedCompanies
        ];

        return view('admin.stats.user', $data);
    }

    /**
     * This is used to show user details
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userStatsDetail($id)
    {
        $this->page = 'userStats';
        $user = User::find($id);
        if ($user) {
            $joinedCompanies = $joinedCommunities = '';
            $userCommunities = $user->joinCommunities()->select('company_id', 'title', 'companies.name as company_name')->leftJoin('companies', 'companies.id', '=', 'company_id')->get()->toArray();
            if($userCommunities) {
                $joinedCompanies = implode(', ', array_filter(array_column($userCommunities, 'company_name')));
                $joinedCommunities = implode(', ', array_filter(array_column($userCommunities, 'title')));
            }
            $data = [
                'id' => $id,
                'user' => $user,
                'joinedCompanies' => $joinedCompanies,
                'joinedCommunities' => $joinedCommunities,
                'totalPosts' => Post::where('created_by', '=', $id)->count(),
                'swapPosts' => Post::where('created_by', '=', $id)->where('parent_category_id', '=', 1)->count(),
                'borrowPosts' => Post::where('created_by', '=', $id)->where('parent_category_id', '=', 2)->count(),
                'wantedPosts' => Post::where('created_by', '=', $id)->where('parent_category_id', '=', 3)->count(),
                'giveAwayPosts' => Post::where('created_by', '=', $id)->where('parent_category_id', '=', 4)->count(),
            ];

            return view('admin.stats.user-detail', $data);
        }
    }

    /**
     * This is used to make user active/deActive
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userActivate(Request $request)
    {
        $obj = User::find($request->input('id'));
        if ($obj) {
            $obj->active = $request->input('active');
            if ($obj->save()) {
                $this->success = true;
                $sufix = _lang('Deactive');
                if ($request->input('active')) {
                    $sufix = _lang('Active');
                }
                $this->message = _lang('User is').' ' . $sufix .' '. _lang('successfully');
            }
        }

        return response()->json(['success' => $this->success, 'message' => $this->message]);
    }

    /**
     * This is used to get user stats
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserStats(Request $request)
    {
        $params = [
            'perPage' => 10,
            'page' =>   $request->input('page'),
            'search' => $request->input('search'),
            'sortColumn' => $request->input('sortColumn'),
            'sortType' => $request->input('sortType'),
            'arrCommunities' => $request->input('arrCommunities'),
            'arrCompanies' => $request->input('arrCompanies'),
            'start_age' => $request->input('start_age'),
            'end_age' => $request->input('end_age'),
            'device_type' => $request->input('device_type')
        ];
        $data = User::getUserStats($params);

        return response()->json($data);
    }

    /**
     * This is used to get company stats view
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function companyStats(Request $request)
    {
        $this->page = 'companyStats';
        $data = [
            'headers' => $this->companyStatsHeaders(),
            'page' => $this->page
        ];

        return view('admin.stats.company', $data);
    }

    /**
     * This is used to show company detail stats
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function companyStatsDetail($id)
    {
        $this->page = 'companyStats';
        $company = Company::find($id);
        if($company) {
            $params = [
                'perPage' => 1000,
                'page' =>   1,
                'sortColumn' => 'c.created_at',
                'sortType' => 'desc',
                'arrCompanies' => [$id]
            ];
            $result = Community::getStats($params)['result'];

            $data = [
                'totalUsers' => \DB::table('community_users as cu')->selectRaw('count(distinct(cu.id)) as count')->join('communities as c', 'cu.community_id', 'c.id')->where('c.company_id', '=', $id)->first()->count,
                'totalCommunities' => Community::where('company_id', $id)->count(),
                'totalPosts' => \DB::table('communities as c')->selectRaw('count(cp.community_id) as count')->join('community_post as cp', 'cp.community_id', 'c.id')->where('c.company_id', '=', $id)->first()->count,
                'company' => $company,
                'communitiesData' => $result,
                'totalNotifications' => NotificationManagement::getCount('notification', $id),
                'totalEmails' => NotificationManagement::getCount('email', $id),
                'totalMessages' => NotificationManagement::getCount('message', $id)
            ];

            return view('admin.stats.company-detail', $data);
        }
    }

    /**
     * This is used to get company stats data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompanyStats(Request $request)
    {
        $params = [
            'perPage' => 10,
            'page' =>   $request->input('page'),
            'search' => $request->input('search'),
            'sortColumn' => $request->input('sortColumn'),
            'sortType' => $request->input('sortType'),
        ];
        $data = Company::getStats($params);
//        dd($data);
        return response()->json($data);
    }

    /**
     * This is used to get communities stats
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function communitiesStats(Request $request)
    {
        $this->page = 'communityStats';
        $this->isCompanyRole = isCompanyRole();
        $this->isCompanyUserRole = isCompanyUserRole();
        $companies = [];
        if ($this->isCompanyRole || $this->isCompanyUserRole) {
            $objCompany = Company::find(getCompanyIdByUser());
            $companies = [$objCompany->id];
            $this->isCompanyOrUserRole = true;
        }
        $selectedCompanies = $companies;

        $arrCompanies = Company::getArrCompanies();
        $data = [
            'headers' => $this->communitiesStatsHeaders(),
            'page' => $this->page,
            'isCompanyRole' => $this->isCompanyOrUserRole,
            'arrCompanies' => $arrCompanies,
            'selectedCompanies' => $selectedCompanies
        ];

        return view('admin.stats.communities', $data);
    }

    /**
     * This is used to get communities
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommunitiesStats(Request $request)
    {
        $params = [
            'perPage' => 10,
            'page' =>   $request->input('page'),
            'search' => $request->input('search'),
            'sortColumn' => $request->input('sortColumn'),
            'sortType' => $request->input('sortType'),
            'arrCompanies' => $request->input('arrCompanies')
        ];
        $data = Community::getStats($params);

        return response()->json($data);
    }

    /**
     * This is used to show post stats
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function postStats(Request $request)
    {
        $this->page = 'postStats';
        $this->isCompanyRole = isCompanyRole();
        $this->isCompanyUserRole = isCompanyUserRole();

        $companies = $arrCompanies = [];
        if ($this->isCompanyRole || $this->isCompanyUserRole) {
            $objCompany = Company::find(getCompanyIdByUser());
            $companies = [$objCompany->id];
            $arrCommunities = Community::getCommunitiesByCompany($companies, false);
            $arrCompanies = Company::getArrCompanies();
            $this->isCompanyOrUserRole = true;
        } else {
            $arrCompanies = Company::getArrCompanies();
            $arrCommunities = Community::getCommunitiesByCompany([]);
        }
        $selectedCompanies = $companies;
        $arrOptions = ['' => _lang('All options')] + loop_lang_convert(ParentCategory::pluck('title', 'id')->toArray());
        $arrUsers = ['' => _lang('All Users')] + User::where('type', '=', AppUserType)->groupBy('id')->pluck('name', 'id')->toArray();
        $data = [
            'headers' => $this->postStatsHeaders(),
            'page' => $this->page,
            'arrCompanies' => $arrCompanies,
            'arrCommunities' => $arrCommunities,
            'arrUsers' => $arrUsers,
            'isCompanyRole' => $this->isCompanyOrUserRole,
            'arrOptions' => $arrOptions,
            'selectedCompanies' => $selectedCompanies
        ];

        return view('admin.stats.post', $data);
    }

    /**
     * This is used to get posts stats
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPostStats(Request $request)
    {
        $params = [
            'perPage' => 10,
            'page' =>   $request->input('page'),
            'search' => $request->input('search'),
            'sortColumn' => $request->input('sortColumn'),
            'sortType' => $request->input('sortType'),
            'arrCompanies' => $request->input('arrCompanies'),
            'arrCommunities' => $request->input('arrCommunities'),
            'users' => $request->input('users'),
            'posts' => $request->input('posts'),
            'option' => $request->input('option'),
            'borrow_to' => $request->input('borrow_to'),
            'borrow_from' => $request->input('borrow_from')
        ];
        $data = Post::getStats($params);

        return response()->json($data);
    }

    /**
     * This is used to show top post stats
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function topSearchPostStats(Request $request)
    {
        $this->page = 'topSearchPostStats';
        $arrOptions = ['' => _lang('All options')] + loop_lang_convert(ParentCategory::pluck('title', 'id')->toArray());
        $data = [
            'headers' => $this->topPostStatsHeaders(),
            'page' => $this->page,
            'arrOptions' => $arrOptions
        ];

        return view('admin.stats.top-post-search', $data);
    }

    /**
     * This is used to get top post stats
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTopSearchPostStats(Request $request)
    {
        $params = [
            'perPage' => 10,
            'page' =>   $request->input('page'),
            'search' => $request->input('search'),
            'sortColumn' => $request->input('sortColumn'),
            'sortType' => $request->input('sortType'),
            'option' => $request->input('option')
        ];
        $data = PostSearchKeyword::getTopStats($params);

        return response()->json($data);
    }

    /**
     * This is used export user stats
     *
     * @param Request $request
     */
    public function exportUserStatExcel(Request $request)
    {
        $params = [
            'perPage' => 1000,
            'page' =>   1,
            'search' => $request->input('search'),
            'sortColumn' => $request->input('sortColumn'),
            'sortType' => $request->input('sortType'),
            'arrCommunities' => $request->input('communities'),
            'arrCompanies' => $request->input('companies'),
            'start_age' => $request->input('start_age'),
            'end_age' => $request->input('end_age'),
            'device_type' => $request->input('device_type'),
            'isExcel' => true
        ];

        $record = User::getUserStats($params);
        if($record['result']) {
            $record = json_decode(json_encode($record['result']), True);
            foreach ($record as $key => $row) {
                $this->data[$key]['id'] = $row['id'];
                $this->data[$key][_lang('Name')] = $row['name'];
                $this->data[$key][_lang('Surname')] = $row['sur_name'];
                $this->data[$key][_lang('Email')] = $row['email'];
                $this->data[$key][_lang('Phone Number')] = $row['mobile_number'];
                $this->data[$key][_lang('City')] = $row['city'];
                $this->data[$key][_lang('Postal Code')] = $row['name'];
                $this->data[$key][_lang('Age')] = $row['age'];
                $this->data[$key][_lang('Date of Birth')] = $row['date_of_birth'];
                $this->data[$key][_lang('Device Type')] = $row['device_type'];
                $this->data[$key][_lang('Total Companies')] = $row['total_companies'];
                $this->data[$key][_lang('Total Communities')] = $row['total_communities'];
                $this->data[$key][_lang('Total Posts')] = $row['total_posts'];
                $this->data[$key][_lang('Swap Posts')] = $row['total_swap_posts'];
                $this->data[$key][_lang('Borrow Posts')] = $row['total_borrow_posts'];
                $this->data[$key][_lang('Wanted Posts')] = $row['total_wanted_posts'];
                $this->data[$key][_lang('Give away Posts')] = $row['total_giveaway_posts'];
            }
        }
        $this->sheetName = _lang('Users');
        $this->downloadExcel();
    }

    /**
     * This is used to export company stats
     *
     * @param Request $request
     */
    public function exportCompanyStatExcel(Request $request)
    {
        $params = [
            'perPage' => 1000,
            'page' =>   1,
        ];
        $record = Company::getStats($params);
        if ($record['result']) {
            $record = json_decode(json_encode($record['result']), True);
            foreach ($record as $key => $row) {
                $this->data[$key]['id'] = $row['id'];
                $this->data[$key][_lang('Name')] = $row['name'];
                $this->data[$key][_lang('Owner')] = $row['owner'];
                $this->data[$key][_lang('Total Communuities')] = $row['total_communities'];
                $this->data[$key][_lang('Total Users')] = $row['total_users'];
                $this->data[$key][_lang('Total Users')] = $row['total_users'];
                $this->data[$key][_lang('Total Posts')] = $row['total_posts'];
                $this->data[$key][_lang('Communities Limit')] = $row['communities'];
                $this->data[$key][_lang('Total Emails')] = NotificationManagement::getCount('email', $row['id']);
                $this->data[$key][_lang('Total Messages')] = NotificationManagement::getCount('message', $row['id']);
                $this->data[$key][_lang('Total Notifications')] = NotificationManagement::getCount('notification', $row['id']);
            }
        }
        $this->sheetName = _lang('Company');
        $this->downloadExcel();
    }

    /**
     * This is used to export community excel file
     *
     * @param Request $request
     */
    public function exportCommunityStatExcel(Request $request)
    {
        $params = [
            'perPage' => 1000,
            'page' =>   1,
            'search' => $request->input('search'),
            'sortColumn' => $request->input('sortColumn'),
            'sortType' => $request->input('sortType'),
            'arrCompanies' => $request->input('companies')
        ];
        $record = Community::getStats($params);

        if ($record['result']) {
            $record = json_decode(json_encode($record['result']), True);
            foreach ($record as $key => $row) {
                $this->data[$key]['id'] = $row['id'];
                $this->data[$key][_lang('Name')] = $row['title'];
                $this->data[$key][_lang('Company Name')] = $row['company_name'];
                $this->data[$key][_lang('Total Users')] = $row['total_users'];
                $this->data[$key][_lang('Total Posts')] = $row['total_posts'];
            }
        }
        $this->sheetName = _lang('Community');
        $this->downloadExcel();
    }

    /**
     * This is used to export excel
     *
     * @param Request $request
     */
    public function exportPostStatExcel(Request $request)
    {
        $params = [
            'perPage' => 1000,
            'page' =>   1,
            'search' => $request->input('search'),
            'sortColumn' => $request->input('sortColumn'),
            'sortType' => $request->input('sortType'),
            'arrCompanies' => $request->input('companies'),
            'arrCommunities' => $request->input('communities'),
            'users' => $request->input('users'),
            'posts' => $request->input('posts'),
            'option' => $request->input('option'),
            'borrow_to' => $request->input('borrow_to'),
            'borrow_from' => $request->input('borrow_from')
        ];
        $record = Post::getStats($params);

        if ($record['result']) {
            $record = json_decode(json_encode($record['result']), True);
            foreach ($record as $key => $row) {
                $this->data[$key]['id'] = $row['id'];
                $this->data[$key][_lang('Title')] = $row['title'];
                $this->data[$key][_lang('Post By')] = $row['posted_by'];
                $this->data[$key][_lang('Option')] = $row['option'];
                $this->data[$key][_lang('Category')] = $row['category'];
                $this->data[$key][_lang('Posted Date')] = $row['posted_date'];
                $this->data[$key][_lang('Status')] = (!empty($row['status'])) ? _lang('Active') : _lang('Deactive');
            }
        }
        $this->sheetName = _lang('Post');
        $this->downloadExcel();
    }

    /**
     * This is used to export top search export file
     *
     * @param Request $request
     */
    public function exportTopPostSearchStatExcel(Request $request)
    {
        $params = [
            'perPage' => 1000,
            'page' => 1,
            'search' => $request->input('search'),
            'sortColumn' => $request->input('sortColumn'),
            'sortType' => $request->input('sortType'),
            'option' => $request->input('option')
        ];
        $record = PostSearchKeyword::getTopStats($params);
        if ($record['result']) {
            $record = json_decode(json_encode($record['result']), True);
            foreach ($record as $key => $row) {
                $this->data[$key]['id'] = $row['id'];
                $this->data[$key][_lang('Option')] = $row['option'];
                $this->data[$key][_lang('Total Count')] = $row['count'];
            }
        }
        $this->sheetName = _lang('top-post-search');
        $this->downloadExcel();
    }

    /*
     * This is used to download excel file
     */
    public function downloadExcel()
    {
        $savedFilePath = public_path('data/excel/' . date('Y-m-d'));
        if (!file_exists($savedFilePath)) {
            mkdir($savedFilePath, 0777, true);
        }
        $data = $this->data;
        \Excel::create($this->sheetName, function ($excel) use ($data) {
            // Set the spreadsheet title, creator, and description
            $excel->setTitle('envelope');
            $excel->setCreator('bash')->setCompany('Sharepeeps');
            $excel->setDescription('excel file');
            $excel->sheet('Sheetname', function ($sheet) use ($data) {
                $sheet->setAutoSize(false);
                $sheet->setWidth('A', 12.75);
                $sheet->setWidth('B', 12.75);
                $sheet->setWidth('C', 12.75);
                $sheet->setWidth('E', 12.75);
                $sheet->setWidth('F', 12.75);
                $sheet->setWidth('G', 12.75);
                $sheet->setWidth('H', 12.75);
                $sheet->setWidth('I', 12.75);
                $sheet->setWidth('J', 12.75);
                $sheet->setWidth('K', 12.75);
                $sheet->setWidth('L', 12.75);
                $sheet->setWidth('M', 12.75);
                $sheet->setWidth('N', 12.75);
                $sheet->setWidth('N', 12.75);
                $sheet->setWidth('O', 12.75);
                $sheet->setWidth('P', 12.75);
                $sheet->setWidth('Q', 12.75);
                $sheet->getStyle('1:1')->getFont()->setBold(true);
                $count = 1;
                $sheet->fromArray($data, null, 'A1', true);
                for ($i = 1; $i <= $count; $i++) {
                    $sheet->setHeight($i, 15.75);
                }
            });

        })->download('xlsx');
        // end
    }

    /**
     * This is used to return headers
     *
     * @return array
     */
    private function userStatsHeaders()
    {
        $count = 0;
        return [
            $count => ['name' => _lang('Name'), 'sorterKey' => 'name', 'isSorter' => true],
            ++ $count => ['name' => _lang('Surname'), 'sorterKey' => 'sur_name', 'isSorter' => true],
            ++ $count => ['name' => _lang('Email'), 'sorterKey' => 'email', 'isSorter' => true],
            ++ $count => ['name' => _lang('Age'), 'sorterKey' => 'age', 'isSorter' => true],
            ++ $count => ['name' => _lang('Total Companies'), 'sorterKey' => 'total_companies', 'isSorter' => true],
            ++ $count => ['name' => _lang('Total Communities'), 'sorterKey' => 'total_communities', 'isSorter' => true],
            ++ $count => ['name' => _lang('Total Posts'), 'sorterKey' => 'total_posts', 'isSorter' => true],
            ++ $count => ['name' => _lang('OS'), 'sorterKey' => 'device_type', 'isSorter' => true],
        ];
    }

    /**
     * This is used to get company stats headers
     *
     * @return array
     */
    private function companyStatsHeaders()
    {
        return [
            0 => ['name' => _lang('Company'), 'sorterKey' => 'name', 'isSorter' => true],
            1 => ['name' => _lang('Owner'), 'sorterKey' => 'owner', 'isSorter' => true],
            2 => ['name' => _lang('Total Communities'), 'sorterKey' => 'total_communities', 'isSorter' => true],
            3 => ['name' => _lang('Total Users'), 'sorterKey' => 'total_users', 'isSorter' => true],
            4 => ['name' => _lang('Total Posts'), 'sorterKey' => 'total_posts', 'isSorter' => true]
        ];
    }

    /**
     * This is used to get communities stats header
     *
     * @return array
     */
    private function communitiesStatsHeaders()
    {
        return [
            0 => ['name' => _lang('Community Name'), 'sorterKey' => 'name', 'isSorter' => true],
            1 => ['name' => _lang('Company'), 'sorterKey' => 'company_name', 'isSorter' => true],
            2 => ['name' => _lang('Total Users'), 'sorterKey' => 'total_users', 'isSorter' => true],
            3 => ['name' => _lang('Total Posts'), 'sorterKey' => 'total_posts', 'isSorter' => true]
        ];
    }

    /**
     * This is used to get post stats headers
     *
     * @return array
     */
    private function postStatsHeaders()
    {
        return [
            0 => ['name' => _lang('Image'), 'sorterKey' => 'image', 'isSorter' => true],
            1 => ['name' => _lang('Post Title'), 'sorterKey' => 'title', 'isSorter' => true],
            2 => ['name' => _lang('Posted By'), 'sorterKey' => 'posted_by', 'isSorter' => true],
            3 => ['name' => _lang('Option'), 'sorterKey' => 'option', 'isSorter' => true],
            4 => ['name' => _lang('Category'), 'sorterKey' => 'category', 'isSorter' => true],
            5 => ['name' => _lang('Posted Date'), 'sorterKey' => 'posted_date', 'isSorter' => true],
            6 => ['name' => _lang('Status'), 'sorterKey' => 'status', 'isSorter' => true],
        ];
    }

    /**
     * This is used to get top post stats headers
     *
     * @return array
     */
    private function topPostStatsHeaders()
    {
        return [
            0 => ['name' => _lang('No'), 'sorterKey' => 'id', 'isSorter' => true],
            1 => ['name' => _lang('Keyword'), 'sorterKey' => 'keyword', 'isSorter' => true],
            2 => ['name' => _lang('Option'), 'sorterKey' => 'option', 'isSorter' => true],
            3 => ['name' => _lang('Search Count'), 'sorterKey' => 'count', 'isSorter' => true],
        ];
    }

}
