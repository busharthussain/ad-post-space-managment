<?php

namespace App\Http\Controllers\api;

use App\Models\AdSpaceImage;
use App\Models\Community;
use App\Models\Company;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Controllers\admin\SharpeepsTrait;
use Intervention\Image\Facades\Image as Image;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use App\Models\Region;
use App\Models\Category;
use App\Models\AdSpace;
use App\Models\CommunityUser;
use App\Models\PostConversation;
use Illuminate\Support\Facades\Password;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Models\Tag;
use App\Models\PostImages;
use App\Models\PostSearchKeyword;
use App\Models\NotificationManagement;
use App\Models\AdSpaceClick;
use function Psy\debug;
 
class ApiController extends Controller
{
    use SharpeepsTrait;

    protected $password = '';
    protected $email = '';

    /**
     * User is created successfully
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->message = 'There is problem in creating User';
        $data = $request->all();
        if (!empty($data) && !empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        if (!empty($data['date_of_birth'])) {
            $data['date_of_birth'] = databaseDateFromat($data['date_of_birth']);
        }
        $data['type'] = AppUserType;
        $unique = '';
        $id = $request->input('id');
        $prefix = 'created';
        if (!empty($id)) {
            $unique = ',' . $id;
            $prefix = 'updated';
        }
        $validations = [
            'email' => 'required|email|max:255|unique:users,email' . $unique
        ];
        $validator = \Validator::make($data, $validations);
        if ($validator->fails()) {
            $this->message = 'This email address is already taken';
        } else {
            if(!empty($data['image'])) {
                $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/i', '', $data['image']));
                $fileName = createImageUniqueName('jpg');
                $destinationPath = public_path(uploadAppUserImage);
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $tempFile = $destinationPath .'/'. $fileName;
                file_put_contents($tempFile, $image);
                $data['image'] = $fileName;
            }
            $data['relative_path'] = uploadAppUserImage;
            $data['type'] = 'app-users';
            $data['active'] = 1;
            if (!empty($id)) {
                $obj = User::find($id);
                $obj->update($data);
            } else {
                $data['authorization_token'] = randomPassword(16, 1, "lower_case,upper_case,numbers");
                $obj = User::Create($data);
            }
            if ($obj) {
                if (!empty($data['community_id'])) {
                    $record = ['is_allow' => 1, 'is_mark' => 1];
                    $obj->joinCommunities()->detach($data['community_id']);
                    $obj->joinCommunities()->attach($data['community_id'], $record);
                }
                $objUser = User::find($obj->id);
                $community_id = (!empty($data['community_id'])) ? $data['community_id'] : 0;
                $objUser->community_id = (string) $community_id;
                $this->data = $objUser;
                $this->message = 'User is '.$prefix.' successfully';
                $this->success = true;
            }
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to login api
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->email = $request->input('email');
        $this->password = $request->input('password');
        $this->is_social = $request->input('is_social');
        $column = 'email';
        if ($this->is_social) {
            $column = 'facebook_id';
        }
        $objUser = User::where($column, '=', $this->email)->first();
        if ($objUser) {
            if (\Hash::check($this->password, $objUser->password) || $this->is_social) {
                if (empty($objUser->active)) {
                    $this->message = 'You have been deactive by admin. Please contact administrator';
                } else {
                    $communities = $objUser->joinCommunities()->select('communities.id')->where('is_allow', '=', 1)->where('is_mark', '=', 1)->get()->toArray();
                    $community_id = '';
                    if (!empty($communities)) {
                        $communities = array_column($communities, 'id');
                        $community_id = implode(',', $communities);
                    }
                    $objUser->community_id = (string) $community_id;
                    $objUser->device_token = $request->input('device_token');
                    $objUser->is_login = 1;
                    $objUser->save();
                    $this->success = true;
                    $this->message = 'User is login successfully';
                    $this->data = $objUser;
                }
            } else {
                $this->message = 'Please provide valid password';
            }
        } else {
            $this->message = 'User does not exist';
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to logout user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $obj = User::find($request->input('id'));
        $obj->is_login = 0;
        if ($obj->save()) {
            $this->success = true;
            $this->message = 'User is logout successfully';
        }
        $this->data = $obj;

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to send forget password link
     *
     * @param Request $request
     * @param PasswordBroker $passwords
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgetPassword(Request $request, PasswordBroker $passwords)
    {
        $email = $request->input('email');
        $credentials = ['email' => $email];
        $response = Password::sendResetLink($credentials, function (Message $message) {
            $message->subject($this->getEmailSubject());
        });

        if(trim($response) == 'passwords.sent'){
            $this->success = true;
            $this->message = _lang('Email is sent successfully');
        }else{
            $this->message = 'Invalid User';
        }
        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to update user image
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUserImage(Request $request)
    {
        $obj = User::find($request->input('id'));
        if ($obj) {
            if (!empty($request->input('image'))) {
                $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/i', '', $request->input('image')));
                $fileName = createImageUniqueName('jpg');
                $destinationPath = public_path(uploadAppUserImage);
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $tempFile = $destinationPath . '/' . $fileName;
                file_put_contents($tempFile, $image);
                $previousImage = $obj->image;
                $obj->image = $fileName;
                if ($obj->save()) {
                    $this->success = true;
                    @unlink(uploadAppUserImage . '/' . $previousImage);
                    $this->message = _lang('Image is uploaded successfully');
                }
            }
        } else {
            $this->message = 'User does not exist';
        }
        $this->data = $obj;

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to get all regions
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRegions($country = '')
    {
        if($country == '' || $country == 'dn')
            {
              $this->data = Region::where([['company_id','=', 0],['country','=','dn']])->get()->toArray();
            }
            else if($country == 'en')
            {
             $this->data = Region::where([['company_id','=', 0],['country','=','en']])->get()->toArray();
            }
        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to get companies data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompanies(Request $request)
    {
        $this->params = [
            'region_id' => $request->input('region_id'),
            'user_id' => $request->input('user_id')
        ];
        $this->data = Company::getCompaniesData($this->params);

        return response()->json(['success' => true, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to get company data
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function associateCompanyToUser(Request $request)
    {
        $objUser = User::find($request->input('user_id'));
        $objUser->parent_id = $request->input('company_id');
        if ($objUser->update()) {
            $this->success = true;
            $this->message = 'Company is associated to user';
        }

        return response()->json(['success' => true, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to get user data
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser(Request $request)
    {
        $this->data = User::find($request->input('id'));
        $this->message = 'ID does not exist';
        if($this->data) {
            $this->success = true;
            $this->message = 'ID exist';
        }

        return response()->json(['success' => true, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to get communities data by comapny
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommunities(Request $request)
    {
        $this->params = [
            'company_id' => $request->input('company_id'),
            'user_id' => $request->input('user_id')
        ];
        $this->data = Community::getCommunitiesByCompanyId($this->params);

        return response()->json(['success' => true, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to get categories
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCategories(Request $request)
    {
        $this->data = Category::get()->toArray();

        return response()->json(['success' => true, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to get post data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPosts(Request $request)
    {
        $this->params = [
            'perPage' => 20,
            'page' => $request->input('page'),
            'search' => $request->input('search'),
            'user_id' => $request->input('user_id'),
            'company_id' => $request->input('company_id'),
            'community_id' => $request->input('community_id'),
            'category_id' => $request->input('category_id'),
            'parent_category_id' => $request->input('parent_category_id'),
            'post_id' => $request->input('post_id'),
            'favourite' => $request->input('favourite'),
            'is_my_posts' => $request->input('is_my_posts'),
            'is_request_posts' => $request->input('is_request_posts'),
            'is_borrow_items' => $request->input('is_borrow_items')
        ];
        if (!empty($request->input('is_user_product_list'))) {
            $result = Post::getUserProductList($this->params);
        } else {
            $result = Post::getPostsData($this->params);
        }
        $swapCategory = $tags = [];
        if (!empty($result['result'])) {
            foreach ($result['result'] as $key => $row) {
                $row = json_decode(json_encode($row), True);
                $objPost = Post::find($row['id']);
                if ($objPost->parent_category_id == 1) {
                    $params = ['post_id' => $objPost->id];
                    $swapCategory = Category::getPostCategory($params);
                }
                $imagePath = uploadPostImage;
                $thumbNailPath = uploadPostThumbNailImage;
                $wantedImagePath = uploadWantedImage;
                $wantedThumbNailPath = uploadWantedImage;

                $images = $objPost->images()->orderBy('wanted_unique_image')->get();
                $tags = Tag::where('post_id', '=', $objPost->id)->get()->toArray();
                if ($images) {
                    $images = $images->toArray();
                    array_walk($images, function (&$key) use($imagePath, $thumbNailPath, $wantedImagePath, $wantedThumbNailPath) {
                        if($key['wanted_unique_image']) {
                            $key['relative_path'] = $wantedImagePath;
                            $key['relative_thumbnail_path'] = $wantedThumbNailPath;
                        } else {
                            $key['relative_path'] = $imagePath;
                            $key['relative_thumbnail_path'] = $thumbNailPath;
                        }
                    });
                }
                $isReport = 0;
                if (!empty($row['is_report'])) {
                    $isReport = 1;
                }
                $row['is_report'] = $isReport;
                $isFavourite = 0;
                if (!empty($row['favourite'])) {
                    $isFavourite = 1;
                }
                $row['favourite'] = $isFavourite;
                $ads = [];
                if (!empty($request->input('community_id'))) {
                    $ads = AdSpace::getAdsForApi($this->params)['result'];
                }
                $objUser = User::find($row['created_by'])->toArray();
                $userData = getUserImage($row['created_by']);
                $objUser['image'] = $userData['image'];
                $objUser['relative_path'] = $userData['relativePath'];
                $tempData = [];
                $tempData['user'] = $objUser;
                $tempData['post'] = $row;
                $tempData['images'] = $images;
                $tempData['swap_category'] = $swapCategory;
                $tempData['tags'] = $tags;
                $tempData['ads'] = $ads;
                if (!empty($request->input('is_user_product_list'))) {
                    $paramsCount = [
                        'post_id' => $row['id'],
                        'user_id' => $request->input('user_id'),
                        'isAll' => true
                    ];
                    $tempData['UnreadMessagesCount'] = (string)$this->getUnreadMessagesCount($paramsCount);
                }
                $this->data[] = $tempData;
            }
        }

        if ($request->input('search')) {
            $objPostSearch = PostSearchKeyword::where('keyword', '=', $request->input('search'))->first();
            $searchData = [];
            $searchData['keyword'] = $request->input('search');
            $searchData['parent_category_id'] = $request->input('parent_category_id');
            $searchData['count'] = 1;
            if ($objPostSearch) {
                $objPostSearch->count = $objPostSearch->count + 1;
                $objPostSearch->save();
            } else {
                PostSearchKeyword::Create($searchData);
            }
        }

        $is_active = 0;
        if (!empty($request->input('user_id'))) {
            $obj = User::find($request->input('user_id'));
            if ($obj) {
                $is_active = $obj->active;
            }
        }

        return response()->json(['success' => true, 'message' => $this->message, 'data' => $this->data, 'is_active' => $is_active]);
    }

    /**
     * This is used to report post
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reportPost(Request $request)
    {
        $objUser = User::find($request->input('user_id'));
        if ($objUser){
            $post=Post::find($request->input('post_id'));
            $data['postname']=$post->title;
            $data['username'] = $objUser->name;
            $data['msg'] = $request->input('message');
            $email="support@sharepeeps.dk";
            \Mail::send('email/report-email',$data, function ($message) use ($email) {
                        $message->to($email)
                            ->from(\Config::get('mail.from.address'), \Config::get('mail.from.name'))
                            ->subject('sharepeeps® - '._lang('Report Post'));
             });
            $data = ['message' => $request->input('message')];
            $objUser->reportPosts()->detach($request->input('post_id'));
            $objUser->reportPosts()->attach($request->input('post_id'), $data);
            $this->message = 'You have successfully report a post';
            $this->success = true;
          }

        return response()->json(['success' => true, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to like/dislike post
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function favouritePost(Request $request)
    {
        $objUser = User::find($request->input('user_id'));
        if ($objUser) {
            $data = ['favourite' => $request->input('favourite')];
            $objUser->favouritePosts()->detach($request->input('post_id'));
            if (!empty($request->input('favourite'))) {
                $prefix = 'like';
                $objUser->favouritePosts()->attach($request->input('post_id'), $data);
                $this->success = true;
            } else {
                $prefix = 'dislike';
                $this->success = true;
            }
            $this->message = 'You have successfully ' . $prefix . ' a post';
        }

        return response()->json(['success' => true, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to add post
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addPost(Request $request)
    {
        $this->isApi = true;
        $this->data = $request->all();
        $this->userId = $this->data['user_id'];
        $this->generateUniqueBatchId();
        if (!empty($this->data['id']))
            $this->requestData['id'] = $this->data['id'];
        $this->requestData['active'] = $this->data['active'];
        $this->requestData['batchId'] = $this->batchId;;
        $this->requestData['arrCommunities'] = explode(',', $this->data['community']);
        $this->requestData['arrCompanies'] = explode(',', $this->data['company']);
        if (!empty($this->data['child_categories']))
            $this->data['child_categories'] = explode(',', $this->data['child_categories']);

        $this->storePost();

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to upload post image
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadPostImage(Request $request)
    {
        $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/i', '', $request->input('image')));
        $fileName = createImageUniqueName($request->input('extension'));
        $destinationPath = public_path(uploadPostImage);
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        $tempFile = $destinationPath . '/' . $fileName;
        file_put_contents($tempFile, $image);
        $img = Image::make($tempFile);
        $destinationPath = public_path(uploadPostThumbNailImage);
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        $img->resize(null, 500, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath . '/' . $fileName);
        $prefix = _lang('Image');
        $destinationPath = public_path(uploadPostImage);
        $filePath = asset(uploadPostThumbNailImage . '/' . $fileName);

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $obj = new PostImages();
        $obj->image = $fileName;
        $obj->thumbnail_image = $fileName;
        $obj->batch_id = $request->input('batch_id');
        $obj->post_id = $request->input('post_id');
        $obj->width = $request->input('width');
        $obj->height = $request->input('height');
        $obj->save();
        $id = $obj->id;
        $this->message = $prefix .' '. _lang('is uploaded successfully');
        $this->success = true;
        $this->data['fileName'] = substr($fileName, 0, 8);
        $this->data['filePath'] = $filePath;
        $this->data['id'] = $id;
        $this->data['tempFile'] = $tempFile;

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to get community object
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommunity(Request $request)
    {
        $this->data = Community::find($request->input('id'));

        return response()->json(['success' => true, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to get public communities
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPublicCommunity(Request $request)
    {
        $this->data = Community::where('company_id', '=', 0)->get()->toArray();

        return response()->json(['success' => true, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to join community
     *
     * @param Request $request
     */
    public function joinCommunity(Request $request)
    {
        $communityId = $request->input('community_id');
        $type = $request->input('type');
        $objCommunity = Community::find($communityId);
        $isOpen = $request->input('is_open');
        if (empty($isOpen)) {
            if ($type == 'password') {
                if ($objCommunity->password != $request->input('password')) {
                    $this->message = 'Please provide valid password';
                }
            } else {
                $password = $request->input('password');
                if ($objCommunity->qrcode != $password) {
                    $this->message = 'Please provide valid QRCode';
                }
            }
        }
        if (empty($this->message)) {
            $objUser = User::find($request->input('user_id'));
            if ($objUser->joinCommunities()->where('community_id', '=', $communityId)->count()) {
                $this->message = 'You have already sent request';
            } else {
                $is_allow = 0;
                if ($isOpen) {
                    $is_allow = 1;
                }
                $data = ['is_allow' => $is_allow, 'is_mark' => 1];
                $objUser->joinCommunities()->attach($communityId, $data);
                $this->message = 'You have successfully sent a request to join communities';
                $this->success = true;
                $communities = $objUser->joinCommunities()->select('communities.id')->where('is_allow', '=', 1)->where('is_mark', '=', 1)->get()->toArray();
                if (!empty($communities)) {
                    $communities = array_column($communities, 'id');
                    $this->data = implode(',', $communities);
                }
            }
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to leave community
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function leaveCommunity(Request $request)
    {
        $objUser = User::find($request->input('user_id'));
        $objUser->joinCommunities()->detach($request->community_id);
        $this->success = true;
        $this->message = 'Community is leave successfully';
        $communities = $objUser->joinCommunities()->select('communities.id')->where('is_allow', '=', 1)->where('is_mark', '=', 1)->get()->toArray();
        if (!empty($communities)) {
            $communities = array_column($communities, 'id');
            $this->data = implode(',', $communities);
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to mark a community
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markCommunity(Request $request)
    {
        $obj = CommunityUser::find($request->input('id'));
        $objUser = User::find($obj->user_id);
        $this->success = true;
        $obj->is_mark = $request->input('is_mark');
        $obj->save();
        $this->message = 'Community is updated successfully';
        $communities = $objUser->joinCommunities()->select('communities.id')->where('is_mark', '=', 1)->get()->toArray();
        if (!empty($communities)) {
            $communities = array_column($communities, 'id');
            $this->data = implode(',', $communities);
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to get user joined communities id
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserJoinedCommunities(Request $request)
    {
        $objUser = User::find($request->input('user_id'));
        $communities = $objUser->joinCommunities()->select('communities.id')->where('is_allow', '=', 1)->get()->toArray();
        if (!empty($communities)) {
            $communities = array_column($communities, 'id');
            $this->data = implode(',', $communities);
        }
        $this->success = true;

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to get user search communities
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchCommunities(Request $request)
    {
        $params = [
            'search' => $request->input('search'),
            'user_id' => $request->input('user_id')
        ];
        $this->data = Community::getCommunitiesForApi($params);
        $this->success = true;

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to get user joined communities
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getJoinedCommunities(Request $request)
    {
        $params = [
            'user_id' => $request->input('user_id'),
            'is_joined' => true
        ];
        $this->data = Community::getCommunitiesForApi($params);
        $this->success = true;

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to start post conversation
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function startPostConversation(Request $request)
    {
        $data = [
          'receiver_id' => $request->input('receiver_id'),
          'message' => $request->input('message'),
          'date_from' => $request->input('date_from'),
          'date_to' => $request->input('date_to'),
          'is_request' => 1
        ];
        $obj = User::find($request->input('user_id'));
        $obj->postStartConversation()->attach($request->input('post_id'), $data);
        $this->data['conversation_id'] = $obj->postStartConversation()->where('post_id', '=', $request->input('post_id'))->first()->pivot->id;
        $this->success = true;
        $objUser = User::find($request->input('receiver_id'));

        $this->extraPayLoad['sender_id'] = (string) $request->input('user_id');
        $this->extraPayLoad['receiver_id'] = (string) $request->input('receiver_id');
        $this->extraPayLoad['conversation_id'] = (string) $this->data['conversation_id'];
        $this->extraPayLoad['created_by'] = (string) Post::find($request->input('post_id'))->created_by;
        $this->extraPayLoad['id'] = (string) $this->data['conversation_id'];
        $this->badge =  $this->getUserChatMessagesCount($request->input('receiver_id'), true);

        $this->notificationTitle = 'Message';
        $this->notificationMessage = 'you just received a new message from '.$obj->name;
        $this->deviceType = $objUser->device_type;
        $this->deviceTokens = [$objUser->device_token];
        if ($objUser->active)
            $this->sendNotification();
        $this->success = true;
        $this->message = 'Conversation is started successfully';

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to save message conversation data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postConversation(Request $request)
    {
        $obj = new PostConversation();
        $obj->conversation_id = $request->input('conversation_id');
        $obj->sender_id = $request->input('sender_id');
        $obj->receiver_id = $request->input('receiver_id');
        $obj->message = $request->input('message');
        $obj->type = $request->input('type');
        if (!empty($request->input('image'))) {
            $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/i', '', $request->input('image')));
            $fileName = createImageUniqueName($request->input('extension'));
            $destinationPath = public_path(uploadPostConversationImage);
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $tempFile = $destinationPath . '/' . $fileName;
            file_put_contents($tempFile, $image);
            $obj->image = $fileName;
        }
        if ($obj->save()) {
            $postId = $data = \DB::table('post_start_conversation')->select('post_id')->where('id', $obj->conversation_id)->first()->post_id;
            $this->extraPayLoad['sender_id'] = (string) $obj->sender_id;
            $this->extraPayLoad['receiver_id'] = (string) $obj->receiver_id;
            $this->extraPayLoad['conversation_id'] = (string) $obj->conversation_id;
            $this->extraPayLoad['created_by'] = (string) Post::find($postId)->created_by;
            $this->extraPayLoad['id'] = (string) $obj->id;
            $this->badge = $this->getUserChatMessagesCount($obj->receiver_id, true, $postId);

            $objUser = User::find($obj->receiver_id);
            $obj = User::find($obj->sender_id);
            $this->notificationTitle = 'Message';
            $this->notificationMessage = 'you just received a new message from '.$obj->name;
            $this->deviceType = $objUser->device_type;
            $this->deviceTokens = [$objUser->device_token];
            if ($objUser->active)
                $this->sendNotification();
            $this->success = true;
            $this->message = 'Message is saved successfully';
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to upload post start conversation imagess
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postStartConversationImage(Request $request)
    {
        $image = base64_decode(preg_replace('/^data:image\/\w+;base64,/i', '', $request->input('image')));
        $fileName = createImageUniqueName($request->input('extension'));
        $destinationPath = public_path(uploadPostConversationImage);
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        $tempFile = $destinationPath . '/' . $fileName;
        file_put_contents($tempFile, $image);

        $filePath = asset(uploadPostConversationImage . '/' . $fileName);
        \DB::table('post_start_conversation')->where('id', $request->input('conversation_id'))->update(['image_' . $request->input('number') => $fileName]);

        $this->message = _lang('Image is uploaded successfully');
        $this->success = true;
        $this->data['fileName'] = substr($fileName, 0, 8);
        $this->data['filePath'] = $filePath;
        $this->data['id'] = $request->input('conversation_id');
        $this->data['tempFile'] = $tempFile;

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to get post messages
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPostMessages(Request $request)
    {
        $params = [
            'perPage' => 10,
            'page' => $request->input('page'),
            'post_id' => $request->input('post_id'),
            'user_id' => $request->input('user_id'),
            'count_user_id' => $request->input('count_user_id'),
            'isApi' => true,
            'first_message_only' => true
        ];
        $this->success = true;
        $this->data = PostConversation::getPostLatestMessage($params);
        $is_active = 0;
        if (!empty($request->input('user_id'))) {
            $obj = User::find($request->input('user_id'));
            if ($obj) {
                $is_active = $obj->active;
            }
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data, 'is_active' => $is_active]);
    }

    /**
     * This is used to get post chats
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPostChats(Request $request)
    {
        $params = [
            'perPage' => 50000,
            'page' => 1,
            'conversation_id' => $request->input('conversation_id'),
            'isApiRequest' => true
        ];

        $this->success = true;
        $this->data = PostConversation::getPostMessages($params);

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to get notification messages
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotificationMessages(Request $request)
    {
        $params = [
            'perPage' => 10,
            'page' => $request->input('page'),
            'user_id' => $request->input('user_id'),
            'isApi' => true
        ];
        $this->success = true;
        $result = NotificationManagement::getNotifications($params);
        $unreadNotificationCount = NotificationManagement::getNotificationCount($params);
        if (!empty($result['result'])) {
            foreach ($result['result'] as $key => $row) {
                $sender_data = User::find($row->created_by)->toArray();
                $sender_data['logo_image'] = getLogoImage($row->created_by);
                $row->sender_data = $sender_data;
                $result['result'][$key] = $row;
            }
            $this->data = $result['result'];
        }

        $is_active = 0;
        if (!empty($request->input('user_id'))) {
            $obj = User::find($request->input('user_id'));
            if ($obj) {
                $is_active = $obj->active;
            }
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data, 'unreadNotificationCount' => $unreadNotificationCount, 'is_active' => $is_active]);
    }

    /**
     * This is used to ad space count
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addAdSpaceClick(Request $request)
    {
        $obj = AdSpace::find($request->input('ad_space_id'));
        if ($obj) {
            $obj->count = $obj->count + 1;
            if ($obj->save()) {
                $this->message = 'Ad space click count is updated successfully';
                $this->success = true;
            }
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to get ad space data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAdSpaces(Request $request)
    {
        $params = [
            'parent_category_id' => $request->input('parent_category_id'),
            'community_id' => $request->input('community_id'),
            'company_id' => $request->input('company_id'),
            'type' => 0
        ];
        $topAds = $this->prepareAdsData(AdSpace::getAdsData($params));

        $params = [
            'parent_category_id' => $request->input('parent_category_id'),
            'community_id' => $request->input('community_id'),
            'company_id' => $request->input('company_id'),
            'type' => 1
        ];
        $classifiedAds = $this->prepareAdsData(AdSpace::getAdsData($params));
        $this->data = ['topAds' => $topAds, 'classifiedAds' => $classifiedAds];
        $this->success = true;

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    private function prepareAdsData($data)
    {
        if ($data) {
            foreach ($data as $key => $row) {
                $images = AdSpaceImage::where('ad_id', '=', $row->id)->get()->toArray();
                $data[$key]->images = $images;
                $data[$key]->relative_path = uploadAdImage;
                $data[$key]->thumbnail_relative_path = uploadAdThumbNailImage;
            }
        }

        return $data;
    }

    /**
     * This is used to make post completed
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function postTradeCompleted(Request $request)
    {
        $obj = Post::find($request->input('post_id'));
        if ($obj) {
            if ($obj->is_completed) {
                $this->success = true;
                $this->message = _lang('You have already completed trade of this item');
            } else {
                $obj->is_completed = $request->input('is_completed');
                $obj->receiver_id = $request->input('receiver_id');
                if ($obj->save()) {
                    $this->success = true;
                    $this->message = _lang('Post is completed successfully');
                }
            }
        }
        $this->data = $obj;

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to delete post
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePost(Request $request)
    {
        $obj = Post::find($request->input('id'));
        if ($obj) {
            if ($obj->delete()) {
                $this->message = _lang('Post is deleted successfully');
                $this->success = true;
            }
        } else {
            $this->message = _lang('This post does not exist');
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to remove user from message or notification
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteMessageNotification(Request $request)
    {
        $id = $request->input('id');
        $userId = $request->input('user_id');
        $obj = NotificationManagement::find($id);
        if ($obj) {
            $userIds = explode(',', $obj->user_ids);
            if ($userIds) {
                $newUserIds = [];
                foreach ($userIds as $row) {
                    if ($row == $userId) {
                        continue;
                    }
                    $newUserIds[] = $row;
                }
            }
            if (count($newUserIds) > 0) {
                $obj->user_ids = implode(',',$newUserIds);
                $obj->save();
            } else {
                $obj->delete();
            }
            $this->message = 'User id is removed successfully from notification';
            $this->success = true;
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to read message of chat
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function readMessage(Request $request)
    {
        $userId = $request->input('user_id');
        $sql = PostConversation::where('conversation_id', $request->input('id'));
        $sql->Where('receiver_id', '=', $userId);
//        $sql->where(function ($query) use ($userId) {
//            $query->where('sender_id', '=', $userId)
//                ->orWhere('receiver_id', '=', $userId);
//        });
        $sql->update(['is_read' => 1]);
        \DB::table('post_start_conversation')->where('id', $request->input('id'))->update(['is_read' => 1]);
        $this->message = 'Message is read successfully';
        $this->success = true;

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to get count of user chat messages
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function userChatMessages(Request $request)
    {
        $userId = $request->input('user_id');
        $params = [
          'user_id'  => $userId
        ];
        $this->data['count'] = $this->getUserChatMessagesCount($userId);
        $this->data['unReadNotificationCount'] = NotificationManagement::getNotificationCount($params);
        $this->success = true;

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to delete chat messages
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteChatMessage(Request $request)
    {
        $id = $request->input('id');
        \DB::table('post_start_conversation')->where('id', $id)->delete();
        PostConversation::where('conversation_id', '=', $id)->delete();
        $this->success = true;
        $this->message = 'Conversation is deleted successfully';

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }

    /**
     * This is used to mark notification as read against specific user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function readNotification(Request $request)
    {
        $id = $request->input('id');
        $obj = NotificationManagement::find($id);
        $this->message = 'There is problem to mark notification as read';
        if ($obj) {
            $readUserIds = explode(',',$obj->read_user_ids);
            array_push($readUserIds, $request->input('user_id'));
            $readUserIds = implode(',',array_unique($readUserIds));
            $obj->read_user_ids = $readUserIds;
            if ($obj->save()) {
                $this->message = 'Notification is mark as read successfully';
                $this->success = true;
            }
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'data' => $this->data]);
    }
}
