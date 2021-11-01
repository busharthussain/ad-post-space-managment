<?php
namespace App\Http\Controllers\admin;

use App\Models\Community;
use App\Models\Company;
use App\Models\PostConversation;
use Illuminate\Http\Request;
use App\Models\ParentCategory;
use App\Models\Category;
use App\Models\ProductCondition;
use App\Models\PostImages;
use App\Models\Post;
use App\Models\Tag;
use Edujugon\PushNotification\PushNotification;
use App\Models\ChangeLog;

trait SharpeepsTrait
{
    protected $success = false;
    protected $message = '';
    protected $arrCompanies = [];
    protected $arrCommunities = [];
    protected $data = [];
    protected $params = [];
    protected $requestData = [];
    protected $batchId = '';
    protected $prefixMessage = '';
    protected $viewOnly = false;
    protected $isCompanyRole = false;
    protected $isCompanyUserRole = false;
    protected $isCompanyOrUserRole = false;
    protected $isApi = false;
    private $userId = 0;
    private $isAdminRole = false;
    protected $deviceTokens = [];
    protected $deviceType = '';
    protected $notificationTitle = '';
    protected $notificationMessage = '';
    protected $extraPayLoad = [];
    protected $logMessage = '';
    protected $badge = 0;

    /**
     * This is used to return parent categories
     *
     * @return array
     */
    public function getParentCategories()
    {
        return ParentCategory::get();
    }

    /**
     * This is used to get categories
     *
     * @return array
     */
    public function getCategories()
    {
        return  Category::pluck('name', 'id')->all();
    }

    /**
     * This is used to get product conditions
     *
     * @return array
     */
    public function getProductConditions()
    {
        return ProductCondition::pluck('name', 'id')->all();
    }

    /**
     * This is used to get companies
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getCompaniesTrait()
    {
        return Company::select('id', 'name')->get()->toArray();
    }

    /**
     * This is used to get communities data
     *
     * @return array
     */
    public function getCommunitiesTrait()
    {
        $this->arrCompanies = array_merge([0], $this->arrCompanies);

        return Community::select('id', 'title')->whereIn('company_id', $this->arrCompanies)->get()->toArray();
    }

    /**
     * This is used to get communities
     *
     * @return array
     */
    public function getCommunities()
    {
        return Community::select('id', 'title')->get()->toArray();
    }

    /**
     * This is used to get all communities for dropdown
     *
     * @return array
     */
    public function getCommunitiesDropDown()
    {
       return Community::pluck('title', 'id')->all();
    }

    /**
     * This is used to store post data
     */
    public function storePost()
    {
        $this->setUserId();
        $this->message = _lang('Post is not added due to errors');
        if (!empty($this->requestData['id'])) {
            $post = Post::find($this->requestData['id']);
            $this->prefixMessage = _lang('updated');
        } else {
            $post = new Post();
            $this->prefixMessage = _lang('created');
        }
        $post->title = $this->data['title'];
        $post->description = $this->data['description'];
        $post->zip_code = $this->data['zip_code'];
        $post->city = $this->data['city'];
        $post->category_id = $this->data['category_id'];
        $post->parent_category_id = $this->data['parent_category_id'];
        if (!empty($this->data['product_condition_id']))
            $post->product_condition_id = $this->data['product_condition_id'];
        else
            $post->product_condition_id = 0;
        if (empty($this->requestData['id'])) {
            $post->created_by = $this->userId;
        }
        $post->active = $this->requestData['active'];
        $post->batch_id = $this->requestData['batchId'];
        if ($this->data['parent_category_id'] == 2) {
            $post->borrow_to = databaseDateFromat($this->data['borrow_to']);
            $post->borrow_from = databaseDateFromat($this->data['borrow_from']);
        }
        if ($post->save()) {
            $tags = explode(',', $this->data['tags']);
            if (empty($this->isApi)) {
                PostImages::where('batch_id', '=', $this->requestData['batchId'])->update(['post_id' => $post->id]);
            }
            if ($this->data['parent_category_id'] == 3) {
                if (empty($this->isApi)) {
                }
                $categoryImage = Category::find($this->data['category_id'])->image;
                list($width, $height, $type, $attr) = getimagesize(asset(uploadWantedImage . '/' . $categoryImage));
                $obj = PostImages::where('batch_id', '=', $this->requestData['batchId'])->where('wanted_unique_image', '=', 1)->first();
                if ($obj) {
                    $objPostImage = $obj;
                } else {
                    $objPostImage = new PostImages();
                }

                $objPostImage->image = $categoryImage;
                $objPostImage->thumbnail_image = $categoryImage;
                $objPostImage->post_id = $post->id;
                $objPostImage->batch_id = $this->requestData['batchId'];
                $objPostImage->wanted_unique_image = 1;
                $objPostImage->width = $width;
                $objPostImage->height = $height;
                $objPostImage->save();
            }
            if ($tags) {
                Tag::where('post_id', '=', $post->id)->delete();
                foreach ($tags as $row) {
                    $objTag = new Tag();
                    $objTag->name = $row;
                    $objTag->post_id = $post->id;
                    $objTag->save();
                }
            }
            if ($this->data['parent_category_id'] == 1 && !empty($this->data['child_categories'])) {
                $childCategories = $this->data['child_categories'];
                $post->categories()->sync($childCategories, true);
            }
            // store many to many relationships data
            if (!empty($this->requestData['arrCompanies'])) {
                $post->companies()->sync($this->requestData['arrCompanies'], true);
            }
            $post->communities()->sync($this->requestData['arrCommunities'], true);

            $this->success = true;
            $this->message = _lang('Post is').' '.$this->prefixMessage.' '._lang('successfully');

            $this->data['id'] = $post->id;
            $this->data['batch_id'] = $post->batch_id;
        }
    }

    /**
     * This is used to return unique batch id
     */
    private function generateUniqueBatchId()
    {
        $isExist = 1;
        while ($isExist > 0) {
            $this->batchId = uniqid() . time();
            $isExist = PostImages::where('batch_id', '=', $this->batchId)->count();
        }
    }

    /**
     * This is used to set user id
     */
    public function setUserId()
    {
        if (empty($this->userId)) {
            $this->userId = loginId();
        }
    }

    /**
     * This is used to send push notification message
     */
    public function sendNotification()
    {
        if (!\App::isLocal()) {
            if (empty($this->extraPayLoad)) {
                $this->extraPayLoad['sender_id'] = loginId();
            }
            if (!empty(array_filter($this->deviceTokens))) {
                if ($this->deviceType == 'ios') {
                    $push = new PushNotification('apn');
                    $response = $push->setMessage([
                        'aps' => [
                            'alert' => [
                                'title' => $this->notificationTitle,
                                'body' => $this->notificationMessage
                            ],
                            'sound' => 'default',
                            'badge' => (int) $this->badge
                        ],
                        'data' => $this->extraPayLoad
                    ])
                        ->setDevicesToken($this->deviceTokens)
                        ->send();
                } else {
                    $push = new PushNotification('fcm');
                    $response = $push->setMessage([
                        'notification' => [
                            'title' => $this->notificationTitle,
                            'body' => $this->notificationMessage,
                            'sound' => 'default'
                        ],
                        'data' => $this->extraPayLoad
                    ])
                        ->setApiKey('AIzaSyCcSORki2DRQsVowD-njM6exnquik0R9Ag')
                        ->setConfig(['dry_run' => false])
                        ->setDevicesToken($this->deviceTokens)
                        ->send();
                }
                if (!empty($response->getFeedback()->success)) {
                    $this->success = true;
                    $this->message = _lang('Push notification is sent successfully');
                } else {
                    if (empty($this->isApi))
                        $this->success = false;
                    $this->message = 'There is problem to send push notification';
                }
            }
        }
    }

    /**
     * This is used to save change log
     */
    public function saveChangeLog()
    {
        $obj = new ChangeLog();
        $obj->message = $this->logMessage;
        $obj->created_by = loginId();
        $obj->save();
    }

    /**
     * This is used to get user chat messages count
     *
     * @param $userId
     */
    public function getUserChatMessagesCount($userId)
    {
        $count = 0;
        $sql = \DB::table('post_start_conversation')->select('id', 'is_read', 'receiver_id');
//        $sql->where('receiver_id', '=', $userId);
        $sql->where(function ($query) use ($userId) {
            $query->where('user_id', '=', $userId)
                ->orWhere('receiver_id', '=', $userId);
        });
        $data = $sql->get();

        if ($data) {
            foreach ($data as $row) {
                $data = PostConversation::where('conversation_id', '=', $row->id)
                        ->where('receiver_id', '=', $userId)->where('is_read', '=', 0)->count();
                $count = $count + $data;
                if (empty($row->is_read) && $row->receiver_id == $userId) {
                    $count = $count + 1;
                }
            }
        }

        return $count;
    }

    /**
     * This is used to get count of unread messages
     *
     * @param $params
     * @return int
     */
    public function getUnreadMessagesCount($params)
    {
        return PostConversation::getUnreadMessagesCount($params);
    }

}