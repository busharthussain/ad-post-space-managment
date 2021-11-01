<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\CommunityUser;

use App\Models\User;
use App\Models\Post;
use App\Models\Company;
use App\Models\ChangeLog;
use Carbon\Carbon;
use App\Models\CompanyHeader;
use App\Models\UserCustomValues;
use App\Http\Controllers\admin\SharpeepsTrait;
use Session;
use DB;

class UsersController extends Controller
{
    use SharpeepsTrait;
    private $page = 'userStats';
    private $sheetName = 'users';
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

    public function index()
    {
        $data = [
            'headers' => $this->headers(),
            'page' => $this->page,
            'current_page' => 1
        ];

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
        foreach ($users_ids as $key => $value) {
            foreach ($value as $k) {
                $data['count']++;
                $temp = User::where([['id', '=', $k->user_id], ['type', '=', 'app-users']])->get();
                array_push($data['users'], $temp);

            }

        }

        return view('admin.users.index', $data);
    }

    /**
     * This is used to get app users
     *
     * @return mixed
     */
    public function getAppUsers()
    {
        $this->page = 'all users';
        $data = [
            'headers' => $this->headers_all(),
            'page' => $this->page,

        ];

        return view('admin.users.allUsers', $data);
    }

    /**
     * This is used to get users of app
     *
     * @param Request $request
     * @return mixed
     */
    public function getUserOfApp(Request $request)
    {
        $params = [
            'perPage' => 10,
            'page' => $request->input('page'),
            'search' => $request->input('search'),
            'sortColumn' => $request->input('sortColumn'),
            'sortType' => $request->input('sortType'),
        ];
        $data = User::getAppUser($params);

        return response()->json($data);
    }

    /**
     * Get headers
     *
     * @return array
     */
    private function headers_all()
    {

        return [
            0 => ['name' => _lang('Image'), 'isSorter' => false],
            1 => ['name' => _lang('Name'), 'sorterKey' => 'name', 'isSorter' => true],
            2 => ['name' => _lang('Surname'), 'sorterKey' => 'sur_name', 'isSorter' => true],
            3 => ['name' => _lang('Email'), 'sorterKey' => 'email', 'isSorter' => true],
            4 => ['name' => _lang('Age'), 'sorterKey' => 'age', 'isSorter' => true],
            5 => ['name' => _lang('Mobile'), 'isSorter' => false],
            6 => ['name' => _lang('City'), 'sorterKey' => 'city', 'isSorter' => true],
            7 => ['name' => _lang('Postal'), 'sorterKey' => 'postel', 'isSorter' => true],
            8 => ['name' => _lang('Signup Date'), 'sorterKey' => 'created_at', 'isSorter' => true],
            9 => ['name' => _lang('OS'), 'sorterKey' => 'device_type', 'isSorter' => true],
        ];
    }

    /**
     * This is used to get excel sheet of users
     *
     * @param Request $request
     *
     */
    public function userExportExcel(Request $request)
    {
        $record = [];
        if ($request->search) {
            $community = Community::where('company_id', $request->input('company_id'))->get();
            $users_ids = [];
            foreach ($community as $key) {
                $temp = CommunityUser::where('community_id', $key->id)->get();
                array_push($users_ids, $temp);
            }
            foreach ($users_ids as $key => $value) {
                foreach ($value as $k) {
                    $temp = User::where([['id', '=', $k->user_id], ['type', '=', 'app-users'], ['name', 'LIKE', '%' . $request->search . '%']])->get();
                    if ($temp->isEmpty()) {
                        $temp = User::where([['id', '=', $k->user_id], ['type', '=', 'app-users'], ['sur_name', 'LIKE', '%' . $request->search . '%']])->get();
                    }
                    if ($temp->isEmpty()) {
                        $temp = User::where([['id', '=', $k->user_id], ['type', '=', 'app-users'], ['email', 'LIKE', '%' . $request->search . '%']])->get();
                    }
                    if ($temp->isEmpty()) {
                        $temp = User::where([['id', '=', $k->user_id], ['type', '=', 'app-users'], ['mobile_number', 'LIKE', '%' . $request->search . '%']])->get();
                    }
                    if ($temp->isEmpty() == false) {
                        array_push($record, $temp);
                    }

                }

            }
        } else {
            $community = Community::where('company_id', $request->input('company_id'))->get();
            //$data['company_id']=$company->parent_id;
            $users_ids = [];
            foreach ($community as $key) {
                $temp = CommunityUser::select('user_id')->where('community_id', $key->id)->get();
                array_push($users_ids, $temp);
            }
            $data['users'] = [];
            $company_id = $request->input('company_id');
            foreach ($users_ids as $key => $value) {
                foreach ($value as $k) {
                    $temp = DB::table('users')->where([['users.id', '=', $k->user_id], ['type', '=', 'app-users']])->leftJoin('user_custom_headers', function ($join) use ($company_id) {
                        $join->on('user_custom_headers.user_id', 'users.id')
                            ->where('user_custom_headers.company_id', $company_id);
                    })->get();
                    array_push($record, $temp);
                }
            }
            $headers = CompanyHeader::where('company_id', $company_id)->first();
            if ($record) {
                $record = json_decode(json_encode($record), True);
                foreach ($record as $key => $row) {
                    foreach ($row as $r) {
                        $this->data[$key][_lang('Name')] = $r['name'];
                        $this->data[$key][_lang('Surname')] = $r['sur_name'];
                        $this->data[$key][_lang('Email')] = $r['email'];
                        $this->data[$key][_lang('Phone Number')] = $r['mobile_number'];
                        $this->data[$key][_lang('Type')] = $r['type'];
                        $this->data[$key][_lang('Gender')] = $r['sex'];
                        $this->data[$key][_lang('Device Type')] = $r['device_type'];
                        $this->data[$key][_lang('City')] = $r['city'];
                        $this->data[$key][_lang('Date Of Birth')] = $r['date_of_birth'];
                        if ($headers) {
                            if ($headers->CF1) {
                                $this->data[$key][_lang($headers->CF1)] = $r['CF1'];

                            } else {
                                $this->data[$key][_lang('Custonm Field 1')] = $r['CF1'];

                            }
                            if ($headers->CF2) {
                                $this->data[$key][_lang($headers->CF2)] = $r['CF2'];

                            } else {
                                $this->data[$key][_lang('Custonm Field 2')] = $r['CF2'];

                            }
                            if ($headers->CF3) {
                                $this->data[$key][_lang($headers->CF3)] = $r['CF3'];
                            } else {
                                $this->data[$key][_lang('Custonm Field 3')] = $r['CF3'];
                            }
                        } else {
                            $this->data[$key][_lang('Custonm Field 1')] = $r['CF1'];
                            $this->data[$key][_lang('Custonm Field 2')] = $r['CF2'];
                            $this->data[$key][_lang('Custonm Field 3')] = $r['CF3'];
                        }


                    }
                }
            }
            $this->sheetName = 'Company-App-User';
            $this->downloadExcel();
        }
    }

    /*
       * This is used to download excel file of all users
       /**
       *
       *
       * @return File
       */

    public function ExportExcelAllUsers(Request $request)
    {
        $users = '';
        if ($request->search) {
            $users = User::orWhere('name', 'LIKE', '%' . $request->search . '%')->orWhere('email', 'LIKE', '%' . $request->search . '%')->orWhere('sur_name', 'LIKE', '%' . $request->search . '%')->orWhere('mobile_number', 'LIKE', '%' . $request->search . '%')->get();
        } else {
            $users = User::where('type', 'app-users')->get();

        }
        if ($users) {
            $key = 0;
            foreach ($users as $r) {
                if ($r['type'] == 'app-users') {
                    $this->data[$key][_lang('Name')] = $r['name'];
                    $this->data[$key][_lang('Surname')] = $r['sur_name'];
                    $this->data[$key][_lang('Email')] = $r['email'];
                    $this->data[$key][_lang('Age')] = Carbon::parse($r['date_of_birth'])->age;
                    $this->data[$key][_lang('Phone Number')] = $r['mobile_number'];
                    $this->data[$key][_lang('Region')] = $r['city'];
                    $this->data[$key][_lang('OS')] = $r['device_type'];
                    $this->data[$key][_lang('Signup Date')] = $r['created_at'];
                    $this->data[$key][_lang('Type')] = $r['type'];
                    $key++;
                }
            }
            $this->sheetName = _lang('All-App-User');
            $this->downloadExcel();
        } else {

        }
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
                $sheet->getStyle('1:1')->getFont()->setBold(true);
                $count = 1;
                $sheet->fromArray($data, null, 'A1', true);
//                foreach ($statsHeaders as $row) {
//                    $sheet->cell('A' . $count, function ($cell) use ($row) {
//                        $cell->setValue($row['name']);
//                        $cell->setFontSize(12);
//                    });
//                    $count++;
//                }

                for ($i = 1; $i <= $count; $i++) {
                    $sheet->setHeight($i, 15.75);
                }
            });

        })->download('xlsx');
        // end
    }

    public function companyUsers($id)
    {
        $data = [
            'headers' => $this->headers($id),
            'page' => "company",
            'current_page' => 1,
            'company_id' => $id
        ];


        $community = Community::where('company_id', $id)->get();
        $users_ids = [];
        foreach ($community as $key) {
            $temp = CommunityUser::select('user_id')->where('community_id', $key->id)->get();
            array_push($users_ids, $temp);
        }
        $data['users'] = [];
        $data['count'] = 0;
        foreach ($users_ids as $key => $value) {
            foreach ($value as $k) {
                $data['count']++;
                $temp = User::where([['id', '=', $k->user_id], ['type', '=', 'app-users']])->get();
                array_push($data['users'], $temp);

            }
        }
        //$data['paginate'] = Paginator::make($data['users'], count($data['users']), 10);
        return view('admin.users.index', $data);
    }

    /**
     * This is used to get search
     *
     * @param Request $request
     *
     */
    public function search(Request $request, $id = '')
    {

        $data = [];
        $temp_id = '';
        if ($id) {
            if ($request->search == '') {
                return redirect('/company/users/' . $id);
            }
            $data = [
                'headers' => $this->headers($id),
                'page' => $this->page,
                'company_id' => $id
            ];
            $temp_id = $id;
        } else {
            if ($request->search == '') {
                return redirect('/users');
            }
            $data = [
                'headers' => $this->headers(),
                'page' => $this->page
            ];

            $data['user_id'] = loginId();
            $company = User::select('parent_id')->where('id', $data['user_id'])->first();
            $temp_id = $company->parent_id;
            $data['company_id'] = $company->parent_id;
        }


        $community = Community::where('company_id', $temp_id)->get();
        $users_ids = [];
        foreach ($community as $key) {
            $temp = CommunityUser::where('community_id', $key->id)->get();
            array_push($users_ids, $temp);
        }
        $data['users'] = [];
        foreach ($users_ids as $key => $value) {
            foreach ($value as $k) {

                $temp = '';
                $temp = User::where([['id', '=', $k->user_id], ['type', '=', 'app-users'], ['name', 'LIKE', '%' . $request->search . '%']])->get();
                if ($temp->isEmpty()) {
                    $temp = User::where([['id', '=', $k->user_id], ['type', '=', 'app-users'], ['sur_name', 'LIKE', '%' . $request->search . '%']])->get();
                }
                if ($temp->isEmpty()) {
                    $temp = User::where([['id', '=', $k->user_id], ['type', '=', 'app-users'], ['email', 'LIKE', '%' . $request->search . '%']])->get();
                }
                if ($temp->isEmpty()) {
                    $temp = User::where([['id', '=', $k->user_id], ['type', '=', 'app-users'], ['mobile_number', 'LIKE', '%' . $request->search . '%']])->get();
                }
                if ($temp->isEmpty() == false) {
                    array_push($data['users'], $temp);
                }

            }

        }
        $data['count'] = count($data['users']);
        $data['search'] = $request->search;
        return view('admin.users.index', $data);

    }

    /**
     * This is used to get companies
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers(Request $request)
    {
        $params = [
            'perPage' => 10,
            'page' => $request->input('page'),
            'search' => $request->input('search'),
            'sortColumn' => $request->input('sortColumn'),
            'sortType' => $request->input('sortType'),
        ];
        $data = User::getUsers($params);

        return response()->json($data);
    }

    private function headers($id = '')
    {
        //get custom headers
        //id is always used for super admin view

        $CF1 = "Custom Field 1";
        $CF2 = "Custom Field 2";
        $CF3 = "Custom Field 3";
        $headers = '';
        if ($id) {
            $headers = CompanyHeader::where('company_id', $id)->first();
            if ($headers) {
                if ($headers->CF1)
                    $CF1 = $headers->CF1;
                if ($headers->CF2)
                    $CF2 = $headers->CF2;
                if ($headers->CF3)
                    $CF3 = $headers->CF3;
            }
            //end
            return [
                0 => ['name' => _lang('Image'), 'isSorter' => false],
                1 => ['name' => _lang('Name'), 'sorterKey' => 'name', 'isSorter' => true],
                2 => ['name' => _lang('Email'), 'sorterKey' => 'email', 'isSorter' => true],
                3 => ['name' => _lang('Mobile'), 'isSorter' => false],
                4 => ['name' => _lang('Signup Date'), 'sorterKey' => 'created_at', 'isSorter' => false],
                5 => ['name' => _lang($CF1), 'edit' => true, 'isSorter' => false],
                7 => ['name' => _lang($CF2), 'edit' => true, 'isSorter' => false],
                8 => ['name' => _lang($CF3), 'edit' => true, 'isSorter' => false]
            ];
        } else {
            $data['user_id'] = loginId();
            $company = User::select('parent_id')->where('id', $data['user_id'])->first();
            $headers = CompanyHeader::where('company_id', $company->parent_id)->first();
            if ($headers) {
                if ($headers->CF1)
                    $CF1 = $headers->CF1;
                if ($headers->CF2)
                    $CF2 = $headers->CF2;
                if ($headers->CF3)
                    $CF3 = $headers->CF3;
            }
            //end
            return [
                0 => ['name' => _lang('Name'), 'sorterKey' => 'name', 'isSorter' => true],
                1 => ['name' => _lang('Email'), 'sorterKey' => 'email', 'isSorter' => true],
                2 => ['name' => _lang('Mobile'), 'isSorter' => false],
                3 => ['name' => _lang('Signup Date'), 'sorterKey' => 'created_at', 'isSorter' => true],
                4 => ['name' => _lang($CF1), 'isSorter' => false],
                5 => ['name' => _lang($CF2), 'isSorter' => false],
                6 => ['name' => _lang($CF3), 'isSorter' => false]
            ];
        }

    }

    /**
     * This is used for community users
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function companyCommunityUsers(Request $request, $id)
    {
        $params = [
            'perPage' => 10,
            'page' => $request->input('page'),
            'search' => $request->input('search'),
            'sortColumn' => $request->input('sortColumn'),
            'sortType' => $request->input('sortType'),
            'company_id' => $id
        ];
        $data = User::getCompanyCommunityUsers($params);
        return response()->json($data);
    }

    /**
     * This is used to set user custom feild names header
     *
     * @param $id , number of the header which are only 1,2,3 and Request $request
     * @return back to users page
     */
    public function updateCustomFeild(Request $request, $id)
    {
        $messages = array(
            'field_name.required' => 'This Field is Required'
        );
        $rules = array(
            'field_name' => 'required'
        );
        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            if ($id > 3 || $id < 1) {
                return back();
            }
            $data['user_id'] = loginId();
            $company = User::select('parent_id')->where('id', $data['user_id'])->first();
            $headers = CompanyHeader::where('company_id', $company->parent_id)->first();
            if ($headers) {
                if ($id == 1) {
                    $headers->CF1 = $request->field_name;
                } elseif ($id == 2) {
                    $headers->CF2 = $request->field_name;
                } elseif ($id == 3) {
                    $headers->CF3 = $request->field_name;
                } else {
                    return back();
                }

                if ($headers->save()) {
                    Session::flash('header_update_true', "Custonm Field " . $id . " is updated successfully.");
                    return back();
                } else {
                    Session::flash('header_update_false', "Opps...Custonm Field " . $id . " is not updated.");
                    return back();
                }
            } else {
                //create new headers
                $obj = new CompanyHeader();
                if ($id == 1) {
                    $obj->CF1 = $request->field_name;
                } elseif ($id == 2) {
                    $obj->CF2 = $request->field_name;
                } elseif ($id == 3) {
                    $obj->CF3 = $request->field_name;
                } else {
                    return back();
                }

                $obj->company_id = $company->parent_id;
                if ($obj->save()) {
                    Session::flash('header_update_true', "Custonm Field " . $id . " is updated successfully.");
                    return back();
                } else {
                    Session::flash('header_update_false', "Opps...Custonm Field " . $id . " is not updated.");
                    return back();
                }
            }


        }

    }

    /**
     * This is used to set user custom feild values for each header
     *
     * @param $id , number of the Fileds which are only 1,2,3 and Request $request
     * @return back to users page
     */
    public function updateCustomFeildValue(Request $request, $id)
    {
        $messages = array(
            'field_values.required' => 'This Field is Required'
        );
        $rules = array(
            'field_values' => 'required',
            'user_id' => 'required'
        );
        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            echo "error";
        } else {
            if ($id > 3 || $id < 1) {
                return "Ivalid option";
            }

            $headers = UserCustomValues::where([['user_id', '=', $request->user_id], ['company_id', '=', $request->company_id]])->first();
            if ($headers) {
                if ($id == 1) {
                    $headers->CF1 = $request->field_values;
                } elseif ($id == 2) {
                    $headers->CF2 = $request->field_values;
                } elseif ($id == 3) {
                    $headers->CF3 = $request->field_values;
                } else {
                    return back();
                }

                if ($headers->save()) {
                    Session::flash('header_update_true', "Custonm Field " . $id . " is updated successfully.");
                    echo "Saved";
                } else {
                    Session::flash('header_update_false', "Opps...Custonm Field " . $id . " is not updated.");
                    echo "Error";
                }
            } else {
                //create new headers
                $obj = new UserCustomValues();
                if ($id == 1) {
                    $obj->CF1 = $request->field_values;
                } elseif ($id == 2) {
                    $obj->CF2 = $request->field_values;
                } elseif ($id == 3) {
                    $obj->CF3 = $request->field_values;
                } else {
                    return back();
                }

                $obj->user_id = $request->user_id;
                $obj->company_id = $request->company_id;
                if ($obj->save()) {
                    Session::flash('header_update_true', "Custonm Field " . $id . " is updated successfully.");
                    echo "Saved";
                } else {
                    Session::flash('header_update_false', "Opps...Custonm Field " . $id . " is not updated.");
                    echo "Error";
                }
            }

        }
    }

}