<?php

namespace App\Http\Controllers\admin;

use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TempCompanyImages;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image as Image;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Models\PostImages;
use App\Models\Post;
use App\Models\Tag;
use App\Models\Category;
use App\Models\Company;
use App\Models\Community;
use App\Models\PostConversation;
use App\Http\Controllers\admin\SharpeepsTrait;
use function Psy\debug;

class PostController extends Controller
{
    use SharpeepsTrait;
    protected $page = 'post';

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
        return view('admin.post.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->isCompanyRole = isCompanyRole();
        $this->isCompanyUserRole = isCompanyUserRole();
        $this->generateUniqueBatchId();
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
        $data = [
            'page' => $this->page,
            'id' => '',
            'data' => [],
            'parentCategories' => $this->getParentCategories(),
            'categories' => ['' => _lang('Select')] + loop_lang_convert($this->getCategories()),
            'categoriesList' => $this->getCategories(),
            'productConditions' => loop_lang_convert($this->getProductConditions()),
            'batchId' => $this->batchId,
            'selectedParentCategory' => 1,
            'tags' => '',
            'categoryImages' => self::getCategoryImages(),
            'viewOnly' => false,
            'isCompanyRole' => $this->isCompanyOrUserRole,
            'arrCompanies' => $arrCompanies,
            'selectedCompanies' => $companies,
            'arrCommunities' => $arrCommunities
        ];

        return view('admin.post.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        parse_str($request->input('data'), $this->data);
        $this->requestData = $request->all();
        $this->storePost();
        if ($this->success) {
            // save log message
            $this->logMessage = loginName() . '   ' . _lang('create a new post') . '  ' . $this->data['title'];
            $this->saveChangeLog();
            // end
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
        $this->isCompanyRole = isCompanyRole();
        $this->isCompanyUserRole = isCompanyUserRole();
        $isPublicCommunity = true;
        if ($this->isCompanyRole || $this->isCompanyUserRole) {
            $this->isCompanyOrUserRole = true;
            $isPublicCommunity = false;
        }

        $obj = Post::find($id);
        if ($obj) {
            $viewOnly = false;
            if (!empty($request->input('viewOnly'))) {
                $viewOnly = true;
            }
            $images = $obj->images()->where('wanted_unique_image', '=', 0)->get()->toArray();

            $tags = $obj->tags()->get()->toArray();
            if ($tags) {
                $tags = implode(',', array_column($tags, 'name'));
            }

            $arrCompanies = Company::getArrCompanies();
            $selectedCategories = $obj->categories()->select('id')->get()->toArray();
            if ($selectedCategories) {
                $selectedCategories = array_column($selectedCategories, 'id');
            }
            $companies = $obj->companies()->get()->toArray();
            if ($companies) {
                $companies = array_column($companies, 'id');
            }
            $arrCommunities = Community::getCommunitiesByCompany($companies, $isPublicCommunity);
            $communities = $obj->communities()->get()->toArray();
            if ($communities) {
                $communities = array_column($communities, 'id');
            }
            $data = [
                'page' => $this->page,
                'id' => $id,
                'data' => $obj,
                'viewOnly' => $viewOnly,
                'isCompanyRole' => $this->isCompanyOrUserRole,
                'parentCategories' => $this->getParentCategories(),
                'categories' => ['' => 'Select'] + $this->getCategories(),
                'categoriesList' => $this->getCategories(),
                'productConditions' => $this->getProductConditions(),
                'batchId' => $obj->batch_id,
                'selectedParentCategory' => $obj->parent_category_id,
                'selectedCompanies' => $companies,
                'selectedCommunities' => $communities,
                'images' => $images,
                'tags' => $tags,
                'selectedCategories' => $selectedCategories,
                'categoryImages' => self::getCategoryImages(),
                'arrCompanies' => $arrCompanies,
                'arrCommunities' => $arrCommunities
            ];
        }

        return view('admin.post.create', $data);
    }

    /**
     * This is used to get categories data
     *
     * @return array
     */
    public static function getCategoryImages()
    {
        return json_encode(Category::pluck('image', 'id')->toArray());
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
        $obj = Post::find($splitId);
        $this->message = _lang('There is problem to delete post');
        if ($obj && $obj->delete()) {
            $this->message = _lang('Post is deleted successfully');
            $this->success = true;
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'id' => $splitId]);
    }

    /**
     * This is used to get companies data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompanies(Request $request)
    {
        $this->data = $this->getCompaniesTrait();

        return response()->json($this->data);
    }

    /**
     * This is used to get communities data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommunities(Request $request)
    {
        if (!empty($request->input('arrCompanies')))
            $this->arrCompanies = array_filter($request->input('arrCompanies'));
        else
            $this->arrCompanies = [];
        $this->data = $this->getCommunitiesTrait();

        return response()->json($this->data);
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
            $this->message = _lang('Please Provide valid File');
        } else {
            $extension = $file->guessExtension();
            $fileName = createImageUniqueName($extension);
            $img = Image::make($file);
            list($width, $height, $type, $attr) = getimagesize($file->getRealPath());
            $destinationPath = public_path(uploadPostThumbNailImage);
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $img->resize(null, 140, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $fileName);
            $prefix = _lang('Image');
            $destinationPath = public_path(uploadPostImage);
            $filePath = asset(uploadPostThumbNailImage . '/' . $fileName);

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            if (Input::file('file')->move($destinationPath, $fileName)) {
                $obj = new PostImages();
                $obj->image = $fileName;
                $obj->thumbnail_image = $fileName;
                $obj->batch_id = $batchId;
                $obj->width = $width;
                $obj->height = $height;
                $obj->save();
                $id = $obj->id;
                $this->message = $prefix . ' ' . _lang('is uploaded successfully');
                $this->success = true;
            }
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'fileName' => substr($fileName, 0, 8), 'filePath' => $filePath, 'tempImageId' => $batchId, 'id' => $id]);
    }

    /**
     * This is used to delete post image
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyPostImage(Request $request)
    {
        $id = $request->input('id');
        $splitId = explode('_', $id)[1];
        $obj = PostImages::find($splitId);
        $this->message = _lang('There is problem to delete company');
        if ($obj && $obj->delete()) {
            $this->message = _lang('Company is deleted successfully');
            $this->success = true;
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'id' => $splitId]);
    }

    /**
     * This is used to get posts data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPosts(Request $request)
    {
        $this->isCompanyRole = isCompanyRole();
        $this->isCompanyUserRole = isCompanyUserRole();
        $companyId = 0;
        if ($this->isCompanyRole || $this->isCompanyUserRole) {
            $companyId = getCompanyIdByUser();
        }
        $params = [
            'perPage' => 10,
            'page' => $request->input('page'),
            'search' => $request->input('search'),
            'companyId' => $companyId,
            'sortColumn' => $request->input('sortColumn'),
            'sortType' => $request->input('sortType'),
        ];

        $data = Post::getPosts($params);

        return response()->json($data);
    }

    /**
     * This is used to display reported post page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reportedPosts()
    {
        if (isAdminRole()) {
            $data = [
                'page' => 'reported-posts',
                'headers' => $this->reportedPostHeaders()
            ];

            return view('admin.post.reported', $data);
        }
    }

    /**
     * This is used to get reported posts
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReportedPosts(Request $request)
    {
        $params = [
            'perPage' => 10,
            'page' => $request->input('page'),
            'search' => $request->input('search'),
            'sortColumn' => $request->input('sortColumn'),
            'sortType' => $request->input('sortType'),
        ];
        $data = Post::getReportedPosts($params);

        return response()->json($data);
    }

    /**
     * This is used to delete post and reported post table
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteReportedPost(Request $request)
    {
        $id = $request->input('id');
        $splitId = explode('_', $id)[1];
        $obj = Post::find($splitId);
        $this->message = _lang('There is problem to delete Post');
        if ($obj && $obj->delete()) {
            \DB::delete('DELETE from post_report WHERE post_id = ' . $splitId);
            $this->message = _lang('Post is deleted successfully');
            $this->success = true;
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'id' => $splitId]);
    }

    /**
     * This is used to view reported post
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewReportedPost(Request $request)
    {
        $id = $request->input('id');
        $splitData = explode('_', $id);
        $reportId = $splitData[2];
        $record = \DB::table('post_report')->where('id', '=', $reportId)->first();
        $message = $record->message;
        $view = view('admin.partials._view_reported_message', compact('message'))->render();

        return response()->json(['view' => $view]);
    }

    /**
     * This is used to display report message
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reportPostMessage(Request $request)
    {
        $id = $request->input('id');
        $reportId = explode('_', $id)[1];
        $obj = \DB::table('post_report')->where('id', $reportId)->first();
        $title = Post::find($obj->post_id)->title;
        $view = view('admin.partials._report_post_message', compact('reportId', 'title'))->render();

        return response()->json(['view' => $view]);
    }

    /**
     * This is used to send report message
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendReportPostMessage(Request $request)
    {
        $obj = \DB::table('post_report')->where('id', $request->input('id'))->first();
        $userId = Post::find($obj->post_id)->created_by;
        $objUser = User::find($userId);
        $this->notificationTitle = User::find(1)->name;
        $this->notificationMessage = $request->input('message');
        $this->deviceTokens = [$objUser->device_token];
        $this->deviceType = $objUser->device_type;
        $this->sendNotification();

        return response()->json(['success' => $this->success, 'message' => $this->message]);
    }

    /**
     * This is used to send post message
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendPostMessage(Request $request)
    {
        $obj = new PostConversation();
        $obj->conversation_id = $request->input('conversation_id');
        $obj->sender_id = loginId();
        $obj->receiver_id = 0;
        $obj->message = $request->input('message');
        $obj->type = 0;
        if ($obj->save()) {
            $data = \DB::table('post_start_conversation')->select('user_id as sender_id', 'receiver_id', 'post_id')->where('id', $obj->conversation_id)->first();
            $senderId = $data->sender_id;
            $receiverId = $data->receiver_id;
            $id = loginId();
            $this->extraPayLoad['sender_id'] = (string)$id;
            $this->extraPayLoad['receiver_id'] = (string)$senderId;
            $this->extraPayLoad['conversation_id'] = (string)$obj->conversation_id;
            $this->extraPayLoad['created_by'] = (string)Post::find($data->post_id)->created_by;
            $this->extraPayLoad['id'] = (string)$obj->id;
            $this->sendPushNotification($senderId);
            $this->extraPayLoad['receiver_id'] = (string)$receiverId;
            $this->sendPushNotification($receiverId);
            $this->success = true;
            $this->message = _lang('Message is saved successfully');
            $objUser = User::find($receiverId);
            // save change logs
            $this->logMessage = loginName() . ' ' . _lang('sent a message to') . ' ' . $objUser->name;
            $this->saveChangeLog();
            // end
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to send push notifications
     *
     * @param $id
     */
    private function sendPushNotification($id)
    {
        $objUser = User::find($id);
        $this->notificationTitle = 'Besked';
        $this->notificationMessage = 'Du har modtaget en ny besked fra ' . \Auth::user()->name;
        $this->deviceType = $objUser->device_type;
        $this->deviceTokens = [$objUser->device_token];
        $this->sendNotification();
    }

    /**
     * This is used to post message
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postMessageImage(Request $request)
    {
        $file = Input::file('file');
        $filePath = '';
        $tempImageId = $request->input('tempImageId');
        $input = array('file' => $file);
        $rules = array(
            'file' => 'required | mimes:jpeg,jpg,png',
        );

        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            $this->message = _lang('Please Provide valid File');
        } else {
            $extension = $file->guessExtension();
            $fileName = createImageUniqueName($extension);

            $img = Image::make($file);
            $destinationPath = public_path(uploadConversationThumbNailImage);
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $img->resize(null, 140, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $fileName);

            $destinationPath = public_path(uploadPostConversationImage);
            $filePath = asset(uploadConversationThumbNailImage . '/' . $fileName);

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            if (Input::file('file')->move($destinationPath, $fileName)) {
                $obj = new PostConversation();
                $obj->conversation_id = $request->input('batchId');
                $obj->sender_id = loginId();
                $obj->receiver_id = 0;
                $obj->message = '';
                $obj->image = $fileName;
                $obj->type = 0;
                if ($obj->save()) {
                    $this->success = true;
                    $this->message = _lang('Message is saved successfully');
                }
            }
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'fileName' => $filePath, 'tempImageId' => $tempImageId]);
    }

    /**
     * This is used to show post chat
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function postChat(Request $request, $id)
    {
        $record = Post::getPostName($id);
        $data = [
            'id' => $id,
            'assetUrl' => \URL::to('/'),
            'relative_path' => uploadConversationThumbNailImage,
            'relativePathPost' => uploadPostConversationImage,
            'userName' => $record->name,
            'postName' => $record->title
        ];

        return view('admin.post.chat', $data);
    }

    /**
     * This is used to return post chats
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPostChats(Request $request)
    {
        $params = [
            'perPage' => 500,
            'page' => $request->input('page'),
            'conversation_id' => $request->input('conversation_id')
        ];

        $data = PostConversation::getPostMessages($params);

        return response()->json($data);
    }

    /**
     * This is used to render post message
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function postMessage(Request $request, $id)
    {
        $data = [
            'id' => $id,
            'page' => $this->page,
            'name' => Post::find($id)->title,
            'assetUrl' => \URL::to('/'),
            'relative_path' => uploadPostConversationImage
        ];

        return view('admin.post.message', $data);
    }

    /**
     * This is used to send post messages
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPostMessages(Request $request)
    {
        $params = [
            'perPage' => 10,
            'page' => $request->input('page'),
            'post_id' => $request->input('id'),
            'first_message_only' => true
        ];

        $data = PostConversation::getPostLatestMessage($params);

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
            0 => ['name' => _lang('Image'), 'sorterKey' => 'name', 'isSorter' => false, 'width' => '10%'],
            1 => ['name' => _lang('Post Title'), 'sorterKey' => 'title', 'isSorter' => true],
            2 => ['name' => _lang('Option'), 'sorterKey' => 'parent_category_name', 'isSorter' => true],
            3 => ['name' => _lang('Category'), 'sorterKey' => 'category_name', 'isSorter' => true],
            4 => ['name' => _lang('Post by'), 'sorterKey' => 'posted_by', 'isSorter' => true],
            5 => ['name' => _lang('Date'), 'sorterKey' => 'created_at', 'isSorter' => true],
            6 => ['name' => _lang('Status'), 'sorterKey' => 'active', 'isSorter' => true],
            7 => ['name' => _lang('ACTION'), 'isSorter' => false]
        ];
    }

    /**
     * This is used to get reported post headers
     *
     * @return array
     */
    private function reportedPostHeaders()
    {
        return [
            0 => ['name' => _lang('Image'), 'sorterKey' => 'name', 'isSorter' => false, 'width' => '10%'],
            1 => ['name' => _lang('Post Title'), 'sorterKey' => 'title', 'isSorter' => true],
            2 => ['name' => _lang('Option'), 'sorterKey' => 'parent_category_name', 'isSorter' => true],
            3 => ['name' => _lang('Post by'), 'sorterKey' => 'posted_by', 'isSorter' => true],
            4 => ['name' => _lang('Reported By'), 'sorterKey' => 'reported_by', 'isSorter' => true],
            5 => ['name' => _lang('ACTION'), 'isSorter' => false]
        ];
    }

}
