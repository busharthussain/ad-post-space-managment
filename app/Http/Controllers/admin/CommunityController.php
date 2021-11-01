<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\Company;
use Intervention\Image\Facades\Image as Image;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Models\CommunityUser;
use App\Models\CommunityInvitation;
use App\Models\User;
use Session;
use Carbon\Carbon;

class CommunityController extends Controller
{
    use SharpeepsTrait;

    protected $page = 'company';

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
    public function index($id)
    {
        $this->isAdminRole = isAdminRole();
        $obj = Company::find($id);
        if ($obj) {
            $companyName = $obj->name;
            $data = [
                'headers' => $this->headers(),
                'companyId' => $id,
                'page' => $this->page,
                'companyName' => $companyName,
                'allowCommunities' => $obj->communities,
                'totalCommunities' => Community::where('company_id', '=', $id)->count(),
                'isAdminRole' => $this->isAdminRole
            ];

            return view('admin.community.index', $data);
        } else {
            return redirect()->route('super.admin.company');
        }
    }

    /**
     * Display a listing of the all public communties.
     *
     * @return \Illuminate\Http\Response
     */
    public function publicCommunity($country = '')
    {

        $data = [
            'headers' => $this->headers(),
            'page' => 'public communities',
        ];
        if ($country == '' || $country == 'dn') {
            $data['country'] = 'dn';
            $data['count'] = Community::where([['company_id', '=', 0], ['country', '=', 'dn']])->count();
            $data['communities'] = Community::where([['company_id', '=', 0], ['country', '=', 'dn']])->get();
            return view('admin.community.publicCommunities', $data);
        } else if ($country == 'en') {
            $data['country'] = 'en';
            $data['count'] = Community::where([['company_id', '=', 0], ['country', '=', 'dn']])->count();
            $data['communities'] = Community::where([['company_id', '=', 0], ['country', '=', 'en']])->get();
            return view('admin.community.publicCommunities', $data);
        } else {
            return abort(404);
        }
    }

    /**
     * Display a listing of a public communties users.
     *
     * @return \Illuminate\Http\Response
     */
    public function publicCommunityUsers($id)
    {
        $this->page = 'public communities';
        $data = [
            'headers' => $this->headers_all(),
            'page' => $this->page,
            'community_id' => $id
        ];

        return view('admin.community.communityUsers', $data);
    }

    public function getUserOfCommunity(Request $request)
    {
        $params = [
            'perPage' => 10,
            'page' => $request->input('page'),
            'search' => $request->input('search'),
            'sortColumn' => $request->input('sortColumn'),
            'sortType' => $request->input('sortType'),
            'community_id' => $_GET['id'],
        ];
        $data = User::getCommunityUser($params);

        return response()->json($data);
    }

    private function headers_all()
    {
        return [
            0 => ['name' => _lang('Image'), 'isSorter' => false],
            1 => ['name' => _lang('Name'), 'sorterKey' => 'name', 'isSorter' => true],
            2 => ['name' => _lang('Surname'), 'sorterKey' => 'sur_name', 'isSorter' => true],
            3 => ['name' => _lang('Email'), 'sorterKey' => 'email', 'isSorter' => true],
            4 => ['name' => _lang('Age'), 'sorterKey' => 'age', 'isSorter' => true],
            5 => ['name' => _lang('Mobile'), 'isSorter' => false],
            6 => ['name' => _lang('Region'), 'sorterKey' => 'city', 'isSorter' => true],
            7 => ['name' => _lang('Postel'), 'sorterKey' => 'postel', 'isSorter' => true],
            8 => ['name' => _lang('Signup Date'), 'sorterKey' => 'created_at', 'isSorter' => true],
            9 => ['name' => _lang('OS'), 'sorterKey' => 'device_type', 'isSorter' => true],
        ];
    }

    public function ExportExcelAllUsers(Request $request)
    {
        if ($request->search) {
            $users = User::orWhere('name', 'LIKE', '%' . $request->search . '%')->orWhere('email', 'LIKE', '%' . $request->search . '%')->orWhere('sur_name', 'LIKE', '%' . $request->search . '%')->orWhere('mobile_number', 'LIKE', '%' . $request->search . '%')->join('community_users', 'community_users.community_id', 'users.id')->where('community_users.community_id', $request->community_id)->get();
        } else {
            $users = User::join('community_users', 'community_users.user_id', 'users.id')->where('community_users.community_id', $request->community_id)->get();
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
                for ($i = 1; $i <= $count; $i++) {
                    $sheet->setHeight($i, 15.75);
                }
            });

        })->download('xlsx');
        // end
    }

    /**
     * Display a listing of the all public communties search.
     *
     * @return \Illuminate\Http\Response
     */
    public function searchCommunities()
    {

        $data = [
            'headers' => $this->headers(),
            'page' => 'public communities',
        ];
        if ($_GET) {
            $data['search'] = $_GET['search'];
            $data['country'] = '';
            $data['count'] = Community::where([['company_id', '=', 0], ['title', 'LIKE', '%' . $_GET['search'] . '%']])->count();
            $data['communities'] = Community::where([['company_id', '=', 0], ['title', 'LIKE', '%' . $_GET['search'] . '%']])->get();
            return view('admin.community.publicCommunities', $data);
        } else {
            return redirect('/public-community/');
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        if (isCompanyRole() || isCompanyUserRole()) {
            $this->isCompanyOrUserRole = true;
        }
        $obj = Company::find($id);
        if ($obj) {
            $data = [
                'data' => [],
                'id' => '',
                'companyId' => $id,
                'page' => $this->page,
                'isEdit' => false,
                'viewOnly' => false,
                'isCompanyOrUserRole' => $this->isCompanyOrUserRole
            ];

            return view('admin.community.create', $data);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = $request->input('id');
        $data = array();
        parse_str($request->input('data'), $data);
        unset($data['_token']);
        $data['active'] = 1;
        $data['created_by'] = loginId();
        $data['company_id'] = $request->input('company_id');
        $this->message = _lang('There is problem in creating company');
        $this->success = true;
        $obj = Community::find($id);
        $existingPassword = $obj->password;
        if ($obj->update($data)) {
            $company = Company::find($data['company_id']);
            if ($company) {
                $companyName = Company::find($data['company_id'])->name;
                if (!empty($data['is_lock']) && $existingPassword != $data['password']) {
                    $randomStr = randomPassword(16, 1, 'lower_case');
                    $destinationPath = public_path(QRCodePath);
                    $fileName = createImageUniqueName('png');
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }
                    $qrCode = base64_encode($randomStr) . '_sharepeeps.org';
                    \QrCode::format('png');
                    \QrCode::format('png')->size(500)->generate($qrCode, $destinationPath . '/' . $fileName);
                    $obj->qrcode = $qrCode;
                    $obj->qrcode_image = $fileName;
                    $obj->relative_qrcode_path = QRCodePath;
                    $obj->save();
                }

            }
            if ($company) {
                // save log message
                $this->logMessage = loginName() . '  ' . _lang('created new community') . '  ' . $data['title'] . _lang('in') . ' ' . $companyName;
            } else {
                $this->logMessage = loginName() . '  ' . _lang('created new community') . '  ' . $data['title'] . _lang('in');
            }
            $this->saveChangeLog();
            // end

            $this->message = _lang('Community is added successfully');
        }
        return response()->json(['success' => $this->success, 'message' => $this->message]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (isCompanyRole() || isCompanyUserRole()) {
            $this->isCompanyOrUserRole = true;
        }
        $viewOnly = $request->input('viewOnly');
        $objCommunity = Community::find($id);
        if ($objCommunity) {
            $data = [
                'data' => $objCommunity,
                'id' => $id,
                'companyId' => $objCommunity->company_id,
                'page' => $this->page,
                'isEdit' => true,
                'viewOnly' => $viewOnly,
                'isCompanyOrUserRole' => $this->isCompanyOrUserRole
            ];

            return view('admin.community.create', $data);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->input('id');
        $splitId = explode('_', $id)[1];
        $obj = Community::find($splitId);
        $this->message = _lang('There is problem to delete company');
        if ($obj && $obj->delete()) {
            $this->message = _lang('Company is deleted successfully');
            $this->success = true;
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'id' => $splitId]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
     public function destroy_admin(Request $request)
     {
         $id = $request->input('id');
         $obj = Community::find($id);
         $this->message = _lang('There is problem to delete company');
         if ($obj && $obj->delete()) {
             $this->message = _lang('Company is deleted successfully');
             $this->success = true;
             Session::flash('del_msg', _lang('Company is deleted successfully'));
         }

        return back();
     }
    /**
     * This is used to upload images
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function uploadFile(Request $request)
    {
        $file = Input::file('file');
        $filePath = '';
        $id = $request->input('id');
        $clickedId = $request->input('clickedId');
        $input = array('file' => $file);
        $rules = array(
            'file' => 'required | mimes:jpeg,jpg,png',
        );

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $errors = $validator->getMessageBag()->toArray();
            $this->message = _lang('Please Provide valid Image jpeg,jpg,png');
        } else {
            $extension = $file->guessExtension();
            $fileName = createImageUniqueName($extension);

            $img = Image::make($file);
            $destinationPath = public_path(uploadCommunityThumbNail);
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $img->resize(null, 140, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $fileName);
            $prefix = _lang('Image');
            $destinationPath = public_path(uploadCommunityImage);
            $filePath = asset(uploadCommunityThumbNail . '/' . $fileName);

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            if (Input::file('file')->move($destinationPath, $fileName)) {
                if (empty($id)) {
                    $obj = new Community();
                } else {
                    $obj = Community::find($id);
                }
                if ($clickedId == 'giveaway') {
                    $type = 'give_away_image';
                } else if ($clickedId == 'image') {
                    $type = $clickedId;
                } else {
                    $type = $clickedId . '_' . 'image';
                }
                $obj->$type = $fileName;
                $obj->relative_path = uploadCommunityThumbNail;
                $obj->created_by = loginId();
                $obj->save();
                $id = $obj->id;
                $this->message = $prefix . ' ' . _lang('is uploaded successfully');
                $this->success = true;
            }
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'fileName' => $filePath, 'clickedId' => $clickedId, 'id' => $id]);
    }

    /**
     * This is used to get companies
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommunities(Request $request)
    {
        $params = [
            'perPage' => 10,
            'page' => $request->input('page'),
            'companyId' => $request->input('companyId'),
            'search' => $request->input('search'),
            'sortColumn' => $request->input('sortColumn'),
            'sortType' => $request->input('sortType'),
            'isAdminRole' => isAdminRole()
        ];
        $data = Community::getCommunities($params);

        return response()->json($data);
    }

    /**
     * This is used to download file/ force to open file in browser
     *
     * @param $fileName
     * @param $fileUrl
     * @param string $attachment
     *      1) attachment:force download file
     *      2) inline: force open in browser
     */
    public function downloadImage($id)
    {
        $baseUrl = \URL::to('/');
        $objComunity = Community::find($id);
        $attachment = _lang('attachment');
        if ($objComunity) {
            $fileName = $objComunity->qrcode_image;
            if (!empty($fileName)) {
                $fileUrl = $baseUrl . QRCodePath . '/' . $fileName;
            }
            downloadFile($fileName, $fileUrl, $attachment);
        }
    }

    /**
     * This is used to show join communities
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function joinCommunities(Request $request)
    {
        $this->page = 'joinCommunities';
        $data = [
            'headers' => $this->joinCommunityHeaders(),
            'page' => $this->page
        ];

        return view('admin.community.join', $data);
    }

    /**
     * This is used to get join communities
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJoinCommunities(Request $request)
    {
        $params = [
            'perPage' => 10,
            'page' => $request->input('page'),
            'search' => $request->input('search'),
            'sortColumn' => $request->input('sortColumn'),
            'sortType' => $request->input('sortType'),
        ];
        $data = Community::getJoinCommunities($params);

        return response()->json($data);
    }

    /**
     * This is used to join/reject community
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function joinCommunityAction(Request $request)
    {
        $data = explode('_', $request->input('id'));
        $id = $data[1];
        $action = $data[2];
        $objCommunity = CommunityUser::find($id);
        $objUser = User::find($objCommunity->user_id);
        $obj = Community::find($objCommunity->community_id);
        if ($action) {
            $prefix = 'accepted';
            $objCommunity->is_allow = 1;
            if ($objCommunity->save()) {
                $this->success = true;
                $this->message = _lang('Join community request is accepted successfully');
            }
        } else {
            $prefix = _lang('rejected');
            $objCommunity->delete();
            $this->success = true;
            $this->message = _lang('You have successfully decline a join community request');
        }

        if (!empty($this->success)) {
            $this->notificationTitle = _lang('Congratulation');
            $this->notificationMessage = _lang('Admin have been') . ' ' . $prefix . ' ' . _lang('your join community request of') . ' ' . $obj->title;
            $this->deviceType = $objUser->device_type;
            $this->deviceTokens = [$objUser->device_token];
            $this->sendNotification();
        }

        return response()->json(['success' => $this->success, 'message' => $this->message]);
    }

    /**
     * This is used to show community invitation page
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function communityInvitation(Request $request, $id)
    {
        $data = [
            'page' => $this->page,
            'communityId' => $id
        ];

        return view('admin.community.invitation', $data);
    }

    /**
     * This is used to send invitation of community
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendInvitationCommunity(Request $request)
    {
        parse_str($request->input('data'), $this->data);
        $emails = explode(',', $this->data['emails']);
        $obj = new CommunityInvitation();
        $obj->description = $request->input('description');
        $obj->ids = $this->data['emails'];
        if ($this->data['type'] == 'user') {
            $emails = User::whereIn('id', $this->data['ids'])->get()->toArray();
            $emails = array_values(array_filter(array_column($emails, 'email')));
        }

        $obj->save();
        $toEmails = [];
        foreach ($emails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $toEmails[] = $email;
            }
        }

        if (!\App::isLocal()) { // no need to send email in case of saving draft only
            \Mail::send('email.notification_management', ['subject' => 'Community Invitation', 'description' => $request->input('description')], function ($message) use ($toEmails) {
                $message->to($toEmails)
                    ->from(\Config::get('mail.from.address'), \Config::get('mail.from.name'))
                    ->subject('Community Invitation');
            });
        }

        $this->success = true;
        $this->message = 'Invitation is sent successfully';

        return response()->json(['success' => $this->success, 'message' => $this->message]);
    }

    /**
     * This is used to return headers
     *
     * @return array
     */
    private function headers()
    {
        return [
            0 => ['name' => _lang('Community Name'), 'isSorter' => false],
            1 => ['name' => _lang('Total Post'), 'sorterKey' => 'region_name', 'isSorter' => false],
            2 => ['name' => _lang('Total Users'), 'isSorter' => false],
            4 => ['name' => _lang('ACTION'), 'isSorter' => false]
        ];
    }

    /**
     * This is used to return headers
     *
     * @return array
     */
    private function joinCommunityHeaders()
    {
        return [
            0 => ['name' => _lang('Date'), 'sorterKey' => 'created_at', 'isSorter' => true],
            1 => ['name' => _lang('User Name'), 'sorterKey' => 'user_name', 'isSorter' => true],
            2 => ['name' => _lang('Email'), 'sorterKey' => 'email', 'isSorter' => true],
            3 => ['name' => _lang('Community Name'), 'sorterKey' => 'community_name', 'isSorter' => true],
            4 => ['name' => _lang('ACTION'), 'isSorter' => false]
        ];
    }

}
