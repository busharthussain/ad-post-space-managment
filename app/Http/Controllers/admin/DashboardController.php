<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\User;
use App\Models\Post;
use App\Models\Company;
use App\Models\ChangeLog;
use App\Models\CommunityUser;

class DashboardController extends Controller
{

    protected $page = 'dashboard';

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $month = 0)
    {
        $isAdmin = false;
        $isShowAllStats = false;
        if (isAdminRole()) {
            $isAdmin = true;
            $isShowAllStats = true;
        }
        $objPost = Post::where('is_completed', 1);
        $objUser = User::where('type', '=', AppUserType);

        if (!$isAdmin) {
            $companyId = getCompanyIdByUser();
            $totalCommunities = Community::where('company_id', '=', $companyId)->where('active', '=', 1)->count();
            $totalPosts = Post::join('company_post as cp', function ($join) use ($companyId) {
                $join->on('cp.post_id', '=', 'posts.id')
                    ->where('cp.company_id', '=', $companyId);
            })
                ->count();
            $totalCompanies = 1;
            if (Company::find($companyId)->is_stat) {
                $isShowAllStats = true;
            }
        } else {
            $totalCommunities = Community::count();
            $totalPosts = Post::count();
            $totalCompanies = Company::count();
        }

        $completedTrade = $objPost->count();
        if ($isAdmin) {
            $totalUsers = $objUser->count();
        } else {
            $totalUsers = Community::select('cu.user_id')->where('company_id', '=', $companyId)
                ->join('community_users as cu', 'cu.community_id', 'communities.id')
                ->groupBy('cu.user_id')->get()->toArray();
            $totalUsers = count($totalUsers);
        }
        $swapPosts = Post::postData(1);
        $borrowPosts = Post::postData(2);
        $wantedPosts = Post::postData(3);
        $giveAwayPosts = Post::postData(4);

        $swapPostsEnd = Post::postData(1, 'count', 1);
        $borrowPostsEnd = Post::postData(2, 'count', 1);
        $wantedPostsEnd = Post::postData(3, 'count', 1);
        $giveAwayPostsEnd = Post::postData(4, 'count', 1);
        if ($isAdmin) {
            $usersData = User::where(\DB::raw("(DATE_FORMAT(created_at,'%Y'))"), date('Y'))
                ->get();
        } else {
            $data['user_id'] = loginId();
            $company = User::select('parent_id')->where('id', $data['user_id'])->first();
            $community = Community::where('company_id', $company->parent_id)->get();
            $data['company_id'] = $company->parent_id;
            $users_ids = [];
            foreach ($community as $key) {
                $temp = CommunityUser::select('user_id')->where('community_id', $key->id)->get();
                array_push($users_ids, $temp);
            }
            $data['users'] = [];
            $data['count'] = 0;
            $temp = '';
            foreach ($users_ids as $key => $value) {
                foreach ($value as $k) {

                    //$temp=User::where([['id','=',$k->user_id],['type','=','app-users']])->get();
                    if ($data['count'] == 0) {
                        $temp = User::where([['id', '=', $k->user_id], ['type', '=', 'app-users']])->get();
                    } else {
                        $temp_a = User::where([['id', '=', $k->user_id], ['type', '=', 'app-users']])->get();
                        $temp = collect($temp)->merge($temp_a);
                    }

                    $data['count']++;

                }

            }
            $usersData = $temp;
        }

        if (empty($month)) {
            $userChart = \Charts::database($usersData, 'bar', 'highcharts')
                ->title(_lang('Monthly New Registered Users'))
                ->dimensions(500, 500)
                ->elementLabel(_lang('Users'))
                ->responsive(false)
                ->groupByMonth(date('Y'), true);
        } else {
            $str = (strlen($month) < 2) ? 0 . $month : $month;
            $userChart = \Charts::database($usersData, 'bar', 'highcharts')
                ->title(_lang('Monthly New Registered Users'))
                ->dimensions(500, 500)
                ->elementLabel(_lang('Users'))
                ->responsive(false)
                ->groupByDay($str, date('Y'), false);
        }

        $total = $swapPosts + $borrowPosts + $wantedPosts + $giveAwayPosts;
        $swapPercentage = ($total) ? round(($swapPosts / $total) * 100, 2) : 0;
        $borrowPercentage = ($total) ? round(($borrowPosts / $total) * 100, 2) : 0;
        $wantedPercentage = ($total) ? round(($wantedPosts / $total) * 100, 2) : 0;
        $giveAwayPercentage = ($total) ? round(($giveAwayPosts / $total) * 100, 2) : 0;

        $changeLogs = ChangeLog::getChangeLogs();
        $months = array();
        for ($i = 0; $i < 12; $i++) {
            $timestamp = mktime(0, 0, 0, date('n') - $i, 1);
            $months[date('n', $timestamp)] = _lang(date('F', $timestamp));
        }

        $data = [
            'page' => $this->page,
            'totalCompanies' => $totalCompanies,
            'totalCommunities' => $totalCommunities,
            'totalPosts' => $totalPosts,
            'completedTrade' => $completedTrade,
            'totalUsers' => $totalUsers,
            'userChart' => $userChart,
            'swapPosts' => $swapPosts,
            'borrowPosts' => $borrowPosts,
            'wantedPosts' => $wantedPosts,
            'giveAwayPosts' => $giveAwayPosts,
            'swapPostsEnd' => $swapPostsEnd,
            'borrowPostsEnd' => $borrowPostsEnd,
            'wantedPostsEnd' => $wantedPostsEnd,
            'giveAwayPostsEnd' => $giveAwayPostsEnd,
            'swapPercentage' => $swapPercentage,
            'borrowPercentage' => $borrowPercentage,
            'wantedPercentage' => $wantedPercentage,
            'giveAwayPercentage' => $giveAwayPercentage,
            'changeLogs' => $changeLogs,
            'isShowAllStats' => $isShowAllStats,
            'months' => [_lang('Select Month')] + $months,
            'month' => $month
        ];

        return view('admin.dashboard.index', $data);
    }
}
