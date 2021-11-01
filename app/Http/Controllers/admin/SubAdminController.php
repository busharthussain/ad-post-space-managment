<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\admin\SharpeepsTrait;
use App\Models\User;
use App\Models\Role;

class SubAdminController extends Controller
{
    use SharpeepsTrait;

    private $page = 'sub-admins';

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
    public function index()
    {
        $data = [
            'headers' => $this->headers(),
            'page' => $this->page
        ];

        return view('admin.subadmin.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'data' => [],
            'id' => '',
            'viewOnly' => false
        ];

        return view('admin.subadmin.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->message = _lang('There is problem in adding user');
        $id = $request->input('id');
        $unique = '';
        parse_str($request->input('data'), $this->data);
        if (!empty($id)) {
            $unique = ',' . $id;
        }
        $validations = [
            'email' => 'required|email|max:255|unique:users,email' . $unique
        ];
        $validator = \Validator::make($this->data, $validations);

        if ($validator->fails()) {
            $this->message = formatErrors($validator->errors()->toArray());
        } else {
            $this->data['parent_id'] = getCompanyIdByUser(loginId());
            $this->data['type'] = subAdminType;
            if (empty($id)) {
                $randomPassword = randomPassword(10, 1, "lower_case,upper_case,numbers");
                $this->data['password'] = bcrypt($randomPassword);
                $objUser = User::Create($this->data);
                if ($objUser) {
                    $this->logMessage = loginName() . '  created new sub admin '.$this->data['name'];
                    $this->saveChangeLog();
                    $email = $this->data['email'];
                    $this->message = 'User is created successfully';
                    $this->success = true;
                    $roleId = Role::where('name', '=', 'company-user')->first()->id;
                    $objUser->attachRole($roleId);

                    \Mail::send('email/create_company', ['name' => $this->data['name'], 'password' => $randomPassword, 'isSubAdmin' => true, 'companyName' => getCompanyNameByUser(), 'email' => $email], function ($message) use ($email) {
                        $message->to($email)
                            ->from(\Config::get('mail.from.address'), \Config::get('mail.from.name'))
                            ->subject('sharpeeps® // sub admin creation');
                    });
                }
            } else {
                $objUser = User::find($id);
                if ($objUser->update($this->data)) {
                    $this->message = 'User is updated successfully';
                    $this->success = true;
                }
            }
        }

        return response()->json(['success' => $this->success, 'message' => $this->message]);
    }

    /**
     * This is used to get sub admins list
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSubAdmins(Request $request)
    {
        $this->isCompanyRole = isCompanyRole();
        $this->isCompanyUserRole = isCompanyUserRole();
        $companyId = 0;
        if($this->isCompanyRole || $this->isCompanyUserRole) {
            $companyId = getCompanyIdByUser();
        }

        $params = [
            'perPage' => 10,
            'page' =>   $request->input('page'),
            'companyId' => $companyId,
            'search' => $request->input('search'),
            'sortColumn' => $request->input('sortColumn'),
            'sortType' => $request->input('sortType'),
        ];
        $data = User::getSubAdmins($params);

        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $viewOnly = $request->input('viewOnly');
        $obj = User::find($id);
        if ($obj) {
            $data = [
                'data' => $obj,
                'id' => $id,
                'viewOnly' => $viewOnly
            ];
        }

        return view('admin.subadmin.create', $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->input('id');
        $splitId = explode('_', $id)[1];
        $obj = User::find($splitId);
        $this->message = 'There is problem to delete company';
        if ($obj && $obj->delete()) {
            $this->message = 'User is deleted successfully';
            $this->success = true;
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'id' => $splitId]);
    }

    /**
     * This is used to return headers
     *
     * @return array
     */
    private function headers() {
        return [
            0 => ['name' => _lang('Name'), 'sorterKey' => 'name', 'isSorter' => true],
            1 => ['name' => _lang('Email'), 'sorterKey' => 'email', 'isSorter' => true],
            2 => ['name' => _lang('Status'), 'sorterKey' => 'status', 'isSorter' => true],
            3 => ['name' => _lang('ACTION'), 'isSorter' => false]
        ];
    }

}
