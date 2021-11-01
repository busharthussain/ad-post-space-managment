<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
//use BaconQrCode\Encoder\QrCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\TempCompanyImages;
use Intervention\Image\Facades\Image as Image;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\admin\SharpeepsTrait;
use App\Models\Role;
use App\Models\Community;

//use Endroid\QrCode\QrCode;

class CompanyController extends Controller
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
    public function index()
    {
        $data = [
            'headers' => $this->headers(),
            'page' => $this->page
        ];

        return view('admin.company.index', $data);
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
            'email' => null,
            'page' => $this->page,
            'viewOnly' => false
        ];

        return view('admin.company.create', $data);
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
        $unique = '';
        $data = array();
        parse_str($request->input('data'), $data);
        if (!empty($id)) {
            $unique = ',' . getUserIdByCompanyId($id);
        }
        $validations = [
            'email' => 'required|email|max:255|unique:users,email' . $unique
        ];
        $validator = \Validator::make($data, $validations);

        if ($validator->fails()) {
            $this->message = formatErrors($validator->errors()->toArray());
        } else {
            $this->message = _lang('There is problem in creating company');
            unset($data['_token']);
            $tempImageId = $request->input('tempImageId');
            if (!empty($tempImageId)) {
                $obj = TempCompanyImages::find($tempImageId);
                if ($obj) {
                    if (!empty($obj->image))
                        $data['image'] = $obj->image;
                    if (!empty($obj->privacy_document))
                        $data['privacy_document'] = $obj->privacy_document;

                    @unlink($obj->image);
                    @unlink($obj->privacy_document);
                    $obj->delete();
                }
            }
            $data['user_id'] = loginId();
            $email = $data['email'];
            $data['relative_path'] = uploadCompanyThumbNailImage;
            $data['relative_document_path'] = uploadCompanyDocument;
            unset($data['email']);
            if (empty($id)) {
                $randomPassword = randomPassword(10, 1, "lower_case,upper_case,numbers");
                $password = bcrypt($randomPassword);
                $prefix = _lang('created');
                $status = Company::Create($data);
                if ($status) {
                    // save log message
                    $this->logMessage = loginName() . _lang('  created new company ') . $data['name'];
                    $this->saveChangeLog();
                    // end
                    $objUser = new User();
                    $objUser->name = $data['name'];
                    $objUser->email = $email;
                    $objUser->password = $password;
                    $objUser->parent_id = $status->id;
                    $objUser->type = companyType;
                    $objUser->save();
                    $objAdmin = Role::where('name', '=', 'company')->first();
                    $objUser->attachRole($objAdmin->id);
                    \Mail::send('email/create_company', ['password' => $randomPassword, 'name' => $status->name], function ($message) use ($email) {
                        $message->to($email)
                            ->from(\Config::get('mail.from.address'), \Config::get('mail.from.name'))
                            ->subject('sharepeeps® - ' . _lang('company creation'));
                    });
                }
            } else {
                $prefix = _lang('updated');
                $obj = Company::find($id);
                $status = $obj->update($data);
                // save log message
                $this->logMessage = loginName() . _lang('  update company ') . $data['name'] . _lang(' details');
                $this->saveChangeLog();
                // end
            }
            if (!empty($status)) {
                $this->success = true;
                $this->message = _lang('Company is') . ' ' . $prefix . ' ' . _lang('successfully');
            }
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
        $viewOnly = $request->input('viewOnly');
        $objCompany = Company::find($id);
        if ($objCompany) {
            $data = [
                'data' => $objCompany,
                'id' => $id,
                'email' => User::where('parent_id', '=', $objCompany->id)->first()->email,
                'page' => $this->page,
                'viewOnly' => $viewOnly
            ];

            return view('admin.company.create', $data);
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
        $obj = Company::find($splitId);
        $this->message = _lang('There is problem to delete company');
        $objUser = User::where('parent_id', '=', $obj->id)->first();
        if ($obj && $obj->delete()) {
            if ($objUser) { // delete user associate to company
                $objUser->delete();
            }
            $this->message = _lang('Company is deleted successfully');
            $this->success = true;
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'id' => $splitId]);
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
        $tempImageId = $request->input('tempImageId');
        $isPdf = $request->input('isPdf');
        $input = array('file' => $file);
        if (empty($isPdf)) {
            $rules = array(
                'file' => 'required | mimes:jpeg,jpg,png',
            );
        } else {
            $rules = array(
                'file' => 'required | mimes:pdf',
            );
        }

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $this->message = _lang('Please Provide valid File');
        } else {
            $extension = $file->guessExtension();
            $fileName = createImageUniqueName($extension);

            if (empty($isPdf)) {
                $img = Image::make($file);
                $destinationPath = public_path(uploadCompanyThumbNailImage);
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $img->resize(null, 140, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $fileName);
                $prefix = _lang('Image');
                $destinationPath = public_path(uploadCompanyImage);
                $filePath = asset(uploadCompanyThumbNailImage . '/' . $fileName);
            } else {
                $destinationPath = public_path(uploadCompanyDocument);
                $filePath = $fileName;
                $prefix = _lang('Pdf');
            }
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            if (Input::file('file')->move($destinationPath, $fileName)) {
                if (empty($tempImageId)) {
                    $obj = new TempCompanyImages();
                } else {
                    $obj = TempCompanyImages::find($tempImageId);
                }
                if (empty($isPdf)) {
                    $obj->image = $fileName;
                } else {
                    $obj->privacy_document = $fileName;
                }
                $obj->save();
                $tempImageId = $obj->id;
                $this->message = $prefix . ' ' . _lang('is uploaded successfully');
                $this->success = true;
            }
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'fileName' => $filePath, 'tempImageId' => $tempImageId]);
    }

    /**
     * This is used to get companies
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompanies(Request $request)
    {
        $params = [
            'perPage' => 10,
            'page' => $request->input('page'),
            'search' => $request->input('search'),
            'sortColumn' => $request->input('sortColumn'),
            'sortType' => $request->input('sortType'),
        ];
        $data = Company::getCompanies($params);

        return response()->json($data);
    }

    /**
     * This is used to return headers
     *
     * @return array
     */
    private function headers()
    {
        return [
            0 => ['name' => _lang('Name'), 'sorterKey' => 'name', 'isSorter' => true],
            1 => ['name' => _lang('Communities'), 'sorterKey' => 'communities', 'isSorter' => true],
            2 => ['name' => _lang('Total Users'), 'isSorter' => false],
            3 => ['name' => _lang('ACTION'), 'isSorter' => false]
        ];
    }
}
