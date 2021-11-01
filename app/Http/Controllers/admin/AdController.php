<?php

namespace App\Http\Controllers\admin;

use App\Models\AdSpace;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdSpaceImage;
use App\Models\Company;
use App\Models\Community;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image as Image;
use App\Models\AdSpaceClick;
use App\Models\ParentCategory;
use App\Http\Controllers\admin\SharpeepsTrait;


class AdController extends Controller
{
    protected $page = 'ad';
    use SharpeepsTrait;

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

        return view('admin.ad.index', $data);
    }

    /**
     * This is used to get posts data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAds(Request $request)
    {
        $params = [
            'perPage' => 10,
            'page' =>   $request->input('page'),
            'search' => $request->input('search'),
            'sortColumn' => $request->input('sortColumn'),
            'sortType' => $request->input('sortType'),
        ];
        $data = AdSpace::getAds($params);

        return response()->json($data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->generateUniqueBatchId();
        $arrCompanies = Company::getArrCompanies();
        $arrCommunities = Community::getCommunitiesByCompany([]);
        $arrOptions = loop_lang_convert(ParentCategory::pluck('title', 'id')->toArray());
        $companies = [];
        $data = [
            'page' => $this->page,
            'id' => '',
            'data' => [],
            'parentCategories' => $this->getParentCategories(),
            'categories' => ['' => 'Select'] + $this->getCategories(),
            'categoriesList' => $this->getCategories(),
            'arrCompanies' => $arrCompanies,
            'selectedCompanies' => $companies,
            'arrCommunities' => $arrCommunities,
            'batchId' => $this->batchId,
            'viewOnly' => $this->viewOnly,
            'arrOptions' => $arrOptions
        ];

        return view('admin.ad.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        parse_str($request->input('data'), $this->data);
        $this->requestData = $request->all();
        $this->message = _lang('Post is not added due to errors');
        if (!empty($this->requestData['id'])) {
            $adSpace = AdSpace::find($this->requestData['id']);
            $this->prefixMessage = _lang('updated');
        } else {
            $adSpace = new AdSpace();
            $this->prefixMessage = _lang('created');
        }
        $adSpace->title = $this->data['title'];
        $adSpace->link = $this->data['link'];
        $adSpace->type = $this->data['type'];
        $adSpace->parent_category_id = $this->data['parent_category_id'];
        $adSpace->created_by = loginId();
        $adSpace->active = $this->requestData['active'];
        $adSpace->batch_id = $this->requestData['batchId'];
        $adSpace->start_time = databaseDateTimeFromat($this->data['start_time']);
        $adSpace->end_time = databaseDateTimeFromat($this->data['end_time']);

        if ($adSpace->save()) {
            $this->logMessage = loginName() . ' create a new ad.';
            $this->saveChangeLog();
            AdSpaceImage::where('batch_id', '=', $this->requestData['batchId'])->update(['ad_id' => $adSpace->id]);
            // store many to many relationships data
            if (!empty($this->requestData['arrCompanies']))
                $adSpace->companies()->sync($this->requestData['arrCompanies'], true);
            $adSpace->communities()->sync($this->requestData['arrCommunities'], true);
            $this->success = true;
            $this->message = _lang('Ad is').' '.$this->prefixMessage.' '._lang('successfully');
        }

        return response()->json(['success' => $this->success, 'message' => $this->message]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $obj = AdSpace::find($id);
        if ($obj) {
            if (!empty($request->input('viewOnly'))) {
                $this->viewOnly = true;
            }
            $images = $obj->images()->get()->toArray();
            $arrCompanies = Company::getArrCompanies();
            $companies = $obj->companies()->get()->toArray();
            if ($companies) {
                $companies = array_column($companies, 'id');
            }
            $arrCommunities = Community::getCommunitiesByCompany($companies);
            $communities = $obj->communities()->get()->toArray();
            if ($communities) {
                $communities = array_column($communities, 'id');
            }
            $arrOptions = ['' => _lang('All options')] + loop_lang_convert(ParentCategory::pluck('title', 'id')->toArray());
            $data = [
                'page' => $this->page,
                'data' => $obj,
                'id' => $id,
                'batchId' => $obj->batch_id,
                'arrCompanies' => $arrCompanies,
                'arrCommunities' => $arrCommunities,
                'selectedCompanies' => $companies,
                'selectedCommunities' => $communities,
                'images' => $images,
                'viewOnly' => $this->viewOnly,
                'arrOptions' => $arrOptions
            ];
        }

        return view('admin.ad.create', $data);
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
        $obj = AdSpace::find($splitId);
        $this->message = _lang('There is problem to delete post');
        if ($obj && $obj->delete()) {
            $this->message = _lang('Post is deleted successfully');
            $this->success = true;
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'id' => $splitId]);
    }

    /**
     * This is used to delete post image
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyImage(Request $request)
    {
        $id = $request->input('id');
        $splitId = explode('_', $id)[1];
        $obj = AdSpaceImage::find($splitId);
        $this->message = _lang('There is problem to delete company');
        if ($obj && $obj->delete()) {
            $this->message = _lang('Image is deleted successfully');
            $this->success = true;
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'id' => $splitId]);
    }

    /**
     * This is used to upload post image
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadFile(Request $request)
    {
        $file = Input::file('file');
        $filePath = $id = '';
        $batchId = $request->input('batchId');
        $input = array('file' => $file);
        $rules = array(
            'file' => 'required | mimes:jpeg,jpg,png',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $errors = $validator->getMessageBag()->toArray();
            $this->message = 'Please Provide valid File';
        } else {
            $extension = $file->guessExtension();
            $fileName = createImageUniqueName($extension);
            $img = Image::make($file);
            list($width, $height, $type, $attr) = getimagesize($file->getRealPath());
            $destinationPath = public_path(uploadAdThumbNailImage);
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $img->resize(null, 140, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $fileName);
            $prefix = _lang('Image');
            $destinationPath = public_path(uploadAdImage);
            $filePath = asset(uploadAdThumbNailImage . '/' . $fileName);

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            if (Input::file('file')->move($destinationPath, $fileName)) {
                $obj = new AdSpaceImage();
                $obj->image = $fileName;
                $obj->thumbnail_image = $fileName;
                $obj->batch_id = $batchId;
                $obj->width = $width;
                $obj->height = $height;
                $obj->save();
                $id = $obj->id;
                $this->message = $prefix .' '. _lang('is uploaded successfully');
                $this->success = true;
            }
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'fileName' => substr($fileName, 0, 8), 'filePath' => $filePath, 'tempImageId' => $batchId, 'id' => $id]);
    }

    /**
     * This is used to show ad space clicks
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function adSpaceClicks(Request $request)
    {
        $data = [
          'page' => 'adSpaceClicks',
          'headers' => $this->adSpaceClicksHeaders()
        ];

        return view('admin.ad.clicks', $data);
    }

    /**
     * This is used to get ad space clicks data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAdSpaceClicks(Request $request)
    {
        $params = [
            'perPage' => 10,
            'page' =>   $request->input('page'),
            'search' => $request->input('search'),
            'sortColumn' => $request->input('sortColumn'),
            'sortType' => $request->input('sortType'),
        ];
        $data = AdSpaceClick::getAdSpaceClicks($params);

        return response()->json($data);
    }

    /**
     * This is used to return headers
     *
     * @return array
     */
    private function headers() {
        return [
            0 => ['name' => _lang('Image'), 'sorterKey' => 'name', 'isSorter' => false, 'width' => '10%'],
            1 => ['name' => _lang('Name'), 'sorterKey' => 'title', 'isSorter' => true],
            2 => ['name' => _lang('Active Date'), 'sorterKey' => 'start_date', 'isSorter' => true],
            3 => ['name' => _lang('End Date'), 'sorterKey' => 'end_date', 'isSorter' => true],
            4 => ['name' => _lang('Total Clicks'), 'sorterKey' => 'created_at', 'isSorter' => true],
            5 => ['name' => _lang('Type'), 'sorterKey' => 'type', 'isSorter' => true],
            6 => ['name' => _lang('Option'), 'sorterKey' => 'option', 'isSorter' => true],
            7 => ['name' => _lang('Status'), 'sorterKey' => 'active', 'isSorter' => true],
            8 => ['name' => _lang('ACTION'), 'isSorter' => false]
        ];
    }

    /**
     * This is used to show ad space clicks headers
     *
     * @return array
     */
    private function adSpaceClicksHeaders()
    {
        return [
            0 => ['name' => _lang('Name'), 'sorterKey' => 'name', 'isSorter' => false],
            1 => ['name' => _lang('Count'), 'sorterKey' => 'count', 'isSorter' => true]];
    }

    /**
     * This is used to return unique batch id
     */
    private function generateUniqueBatchId()
    {
        $isExist = 1;
        while ($isExist > 0) {
            $this->batchId = uniqid() . time();
            $isExist = AdSpaceImage::where('batch_id', '=', $this->batchId)->count();
        }
    }

}
