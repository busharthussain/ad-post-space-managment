<?php
use App\Models\User;
use App\Models\UserCustomValues;
use App\Models\Company;
use App\Models\CommunityPosts;
use App\Models\CommunityUser;

define('uploadCompanyImage', '/company/images');
define('uploadCompanyThumbNailImage', '/company/thumbnails');
define('uploadCompanyDocument', '/company/documents');
define('uploadCommunityImage', '/community/images');
define('uploadCommunityThumbNail', '/community/thumbnails');

define('uploadPostImage', '/company/post/images');
define('uploadPostThumbNailImage', '/company/post/thumbnails');
define('uploadWantedImage', '/assets/wanted');

define('userProfileImage', '/users/');

define('uploadPostConversationImage', '/post/conversation/images');
define('uploadConversationThumbNailImage', '/post/conversation/thumbnails');

define('uploadAdImage', '/ad/images');
define('uploadAdThumbNailImage', '/ad/thumbnails');

define('subAdminType', 'company-users');
define('companyType', 'company');
define('AppUserType', 'app-users');


define('uploadAppUserImage', '/users');
define('QRCodePath', '/qrcode');



/**
 * make_complete_pagination_block
 * @param $obj
 * @param string $type | three possible values 1)short (for short paragraph) 2)long (for long paragraph) 3) null (for no paragraph) .
 * @return  complete pagination block
 */
function make_complete_pagination_block($obj, $type = null)
{
    $info = get_pager_info_paragraph($obj, $type);

    return view('partials._pager', compact('info', 'obj'))->render();
}

/**
 * get_pager_info_paragraph | it will a paginator object provided by laravel paginate method and will return a paragraph line item with the info about total records and showing records range according to the current page.
 * @param array $obj | paginator object provided by laravel paginate method
 * @param string $type | three possible values 1)short (for short paragraph) 2)long (for long paragraph) 3) null (for no paragraph) .
 * @return returns string | returns a string (paragraph line with star end and total records according to the current page.)
 *
 */
function get_pager_info_paragraph($obj, $type = null)
{
    $info = "";
    $end = $obj->currentPage() * $obj->perPage();
    $start = $end - ($obj->perPage() - 1);
    $current_page = $obj->currentPage();
    $last_page = $obj->lastPage();
    if ($start < 1) {
        $start = 1;
    }
    $total = $obj->total();
    if ($end > $total) {
        $end = $total;
    }
    if ($type) {
        if ($total > 0) {
            if ($type == 'long') {
                $info = "<div class='pager-info'><p>Showing $start to $end of $total Records.</p><div class='clr'></div></div>";
            } else {
                $info = "<div class='pager-info'><p>Side $current_page "._lang('of')." $last_page </p><div class='clr'></div></div>";
            }
        }
    }

    return $info;
}

/**
 * This function returns login user id
 *
 * @return mixed
 */
function loginId()
{
    $id = 0;
    if (\Auth::check())
        $id = \Auth::user()->id;

    return $id;
}

/**
 * This is used to get login name
 *
 * @return string
 */
function loginName()
{
    $name = '';
    if (\Auth::check())
        $name = \Auth::user()->name;

    return $name;
}

function sendEmail($params)
{
    \Mail::send('emails.'.$params['template'], ['data' => $params['data']], function ($message) use ($params)
    {
        $message->from('no-reply@scotch.io');
        $message->to($params['toEmail']);
        $message->subject($params['subject']);
    });
}

/**
 * This function returns unique image name
 *
 * @param $extension
 * @return string
 */
function createImageUniqueName($extension)
{
    $uniqueId = time() . uniqid(rand());
    $imageName = $uniqueId . '.' . $extension;

    return $imageName;
}

function databaseDateFromat($date)
{
    return date_format(new \DateTime($date), 'Y-m-d ');
}

function DateFromatNew($date)
{
    return date_format(new \DateTime($date), 'd-m-Y');
}

/**
 * This is used to changed date picker to date time
 *
 * @param $date
 * @return false|string
 */
function databaseDateTimeFromat($date)
{
    return date_format(new \DateTime($date), 'Y-m-d h:i');
}

/**
 * This is used to format errors
 *
 * @param $data
 *     array:2 [
"email" => array:1 [
0 => "The email has already been taken."
]
"mobile_number" => array:1 [
0 => "The mobile number has already been taken."
]
]
 * @return array
 *
 * array:2 [
0 => "The email has already been taken."
1 => "The mobile number has already been taken."
]
 */
function formatErrors($data)
{
    $errors = [];
    if (!empty($data)) {
        foreach ($data as $row) {
            if ($row) {
                foreach ($row as $value) {
                    $errors[] = $value;
                }
            }
        }
    }

    return $errors;
}

/**
 * This is used to return random password
 *
 * @return string
 */
function randomPassword($length,$count, $characters) {

// $length - the length of the generated password
// $count - number of passwords to be generated
// $characters - types of characters to be used in the password

// define variables used within the function
    $symbols = array();
    $passwords = array();
    $used_symbols = '';
    $pass = '';

// an array of different character types
    $symbols["lower_case"] = 'abcdefghijklmnopqrstuvwxyz';
    $symbols["upper_case"] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $symbols["numbers"] = '1234567890';
//    $symbols["special_symbols"] = '!?~@#-_+<>[]{}';

    $characters = explode(",",$characters); // get characters types to be used for the passsword
    foreach ($characters as $key=>$value) {
        $used_symbols .= $symbols[$value]; // build a string with all characters
    }
    $symbols_length = strlen($used_symbols) - 1; //strlen starts from 0 so to get number of characters deduct 1
    $pass = '';
    for ($p = 0; $p < $count; $p++) {
        for ($i = 0; $i < $length; $i++) {
            $n = rand(0, $symbols_length); // get a random character from the string with all characters
            $pass .= $used_symbols[$n]; // add the character to the password string
        }
        $passwords[] = $pass;
    }

    return $pass; // return the generated password
}

/**
 * This is used to check if user has company role
 *
 * @param string $user
 * @return bool
 */
function isCompanyRole($user = '')
{
    $isCompanyRole = false;
    if (empty($user)) {
        $id = loginId();
        if ($id) {
            $user = User::find($id);
        }
    }

    if (!empty($user)) {
        if ($user->hasRole('company')) {
            $isCompanyRole = true;
        }
    }

    return $isCompanyRole;
}

/**
 * This is used to check if user has company role
 *
 * @param string $user
 * @return bool
 */
function isCompanyUserRole($userId = '')
{
    $isCompanyUserRole = false;
    if (empty($userId)) {
        $id = loginId();
        if ($id) {
            $user = User::find($id);
        }
    }

    if (!empty($user)) {
        if ($user->hasRole('company-user')) {
            $isCompanyUserRole = true;
        }
    }

    return $isCompanyUserRole;
}

/**
 * This is used to check if user has company role
 *
 * @param string $user
 * @return bool
 */
function isAdminRole($user = '')
{
    $isAdminRole = false;
    if (empty($user)) {
        $id = loginId();
        if ($id) {
            $user = User::find($id);
        }
    }

    if (!empty($user)) {
        if ($user->hasRole('super-admin')) {
            $isAdminRole = true;
        }
    }

    return $isAdminRole;
}

/**
 * This is used to check if user has company role
 *
 * @param string $user
 * @return bool
 */
function isCompanyUser($userId = '')
{
    $isCompanyUser = false;
    if (empty($userId)) {
        $id = loginId();
        if ($id) {
            $user = User::find($id);
            if ($user->type == subAdminType) {
                $isCompanyUser = true;
            }
        }
    }

    return $isCompanyUser;
}

/**
 * This is used to get companies by user
 *
 * @param string $userId
 * @return int|mixed#
 */
function getCompanyIdByUser($userId = '')
{
    $id = 0;
    if (empty($userId)) {
        $userId = loginId();
    }
    $objUser = User::find($userId);
    if ($objUser) {
        $id = $objUser->parent_id;
    }

    return $id;
}

/**
 * This is used to check notification should be hide or not
 */

function isHideNotification()
{
    $notificationHide = false;
    if (!isAdminRole()) {
        $id = getCompanyIdByUser();
        $getCompany = Company::find($id);
        $isNotification = '';
        if($getCompany){
            $isNotification = Company::find($id)->is_notification;
        }
        if (empty($isNotification)) {
            $notificationHide = true;
        }
    }

    return $notificationHide;
}

/**
 * This is used to check users should be hide or not
 */

function isHideUsers()
{
    $UsersHide = false;
    if (!isAdminRole()) {
        $id = getCompanyIdByUser();
        $getCompany = Company::find($id);
        $isUsers = '';
        if($getCompany){
            $isUsers = Company::find($id)->is_users;
        }
        if (empty($isUsers)) {
            $UsersHide = true;
        }
    }

    return $UsersHide;
}

/**
 * This is used to get user id by company id
 *
 * @param $companyId
 * @return mixed
 */
function getUserIdByCompanyId($companyId)
{
    return User::where('parent_id', '=', $companyId)->first()->id;
}

/**
 * This is used to get company name by user
 *
 * @param string $userId
 * @return mixed|string
 */
function getCompanyNameByUser($userId = '')
{
    $companyName = '';
    if (empty($userId)) {
        $userId = loginId();
    }
    $objUser = User::find($userId);
    if ($objUser) {
        $companyName = Company::where('id', '=',$objUser->parent_id)->first()->name;
    }

    return $companyName;
}

/**
 * This is used to check if stats allow for company admin or not
 *
 * @param string $userId
 * @return bool|mixed
 */
function isStatAllow($userId = '')
{
    $isStatAllow = false;
    if (empty($userId)) {
        $userId = loginId();
    }
    $objUser = User::find($userId);
    if ($objUser) {
        $isStatAllowQuery = Company::where('id', '=', $objUser->parent_id)->first();
        if ($isStatAllowQuery && $isStatAllowQuery->is_stat) {
            $isStatAllow = true;
        }
    }

    return $isStatAllow;
}

/**
 * This is used to get logo image
 *
 * @param string $userId
 * @return string
 */
function getLogoImage($userId = '')
{
    $logoImage = '';
    if (empty($userId)) {
        $userId = loginId();
    }
    $objUser = User::find($userId);
    if ($objUser->hasRole('super-admin')) {
        $logoImage = asset('assets/images/login-logo.png');
    } else {
        $objCompany = Company::where('id', '=',$objUser->parent_id)->first();
        if ($objCompany) {
            $logoImage = asset(uploadCompanyThumbNailImage . '/' . $objCompany->image);
        }
    }

    return $logoImage;
}

/**
 * @param string $userId
 * @return array
 */
function getUserImage($userId = '')
{
    $image = $relativePath = '';
    if (empty($userId)) {
        $userId = loginId();
    }
    $objUser = User::find($userId);
    if ($objUser->type == AppUserType) {
        $image = $objUser->image;
        $relativePath = $objUser->relative_path;
    } else if ($objUser->hasRole('super-admin')) {
        $image = 'login-logo.png';
        $relativePath = '/assets/images';
    } else {
        $objCompany = Company::where('id', '=', $objUser->parent_id)->first();
        if ($objCompany) {
            $image = $objCompany->image;
            $relativePath = uploadCompanyThumbNailImage;
        }
    }

    return compact('image', 'relativePath');
}

/**
 * This is used to get profile image name
 *
 * @param string $userId
 * @return mixed|string
 */
function getProfileName($userId = '', $isCompanyName = '')
{
    $name = '';
    if (empty($userId)) {
        $userId = loginId();
    }
    $objUser = User::find($userId);
    if ($objUser->hasRole('super-admin') || ($objUser->hasRole('company-user') && empty($isCompanyName))) {
        $name = $objUser->name;
    } else {
        $objCompany = Company::where('id', '=',$objUser->parent_id)->first();
        if ($objCompany) {
            $name = $objCompany->name;
        }
    }

    return $name;
}

function arrayToObject($d) {
    if (is_array($d)) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
        return (object) array_map(__FUNCTION__, $d);
    }
    else {
        // Return object
        return $d;
    }
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
function downloadFile($fileName, $fileUrl, $attachment = 'attachment')
{

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Type: application/octet-stream');
    if ($attachment == 'inline') {
        header("Content-type: application/pdf");
    }
    header("Content-Transfer-Encoding: Binary");
    header("Content-Disposition: $attachment; filename=\"" . $fileName . "\"");
    ob_end_flush();
    readfile($fileUrl);
    exit;
}

/**
 * This is used to generate number
 *
 * @return array
 */
function generateNumberDropDown($prefix)
{
    $numbers = range(0,120);
    unset($numbers[0]);

    return array_merge([ 0 => _lang('select').' '.$prefix.' '._lang('range')], $numbers);
}

/**
 * take_assoc_transpose | it will take a multidimensional associative array and will return a transpose of that array
 * @param array $data | take a multidimensional associative array
 * @return it will return transposed multidimensional array of the input array
 *
 */
function take_assoc_transpose($data)
{
    $first_column = array_keys($data[0]);
    array_unshift($data, null);
    $rest_of_columns = call_user_func_array('array_map', $data);
    foreach ($first_column as $key => $val) {
        array_unshift($rest_of_columns[$key], $val);
    }
    return $rest_of_columns;
}
function get_lang(){
    $return = 'english';
    if(!empty(session()->get('lang')) && session()->get('lang') != 'english'){
        $return = 'dutch';
    }
    return $return;
}
function _lang($key){
    if(get_lang() == 'english'){
        return $key;
    }else{
        require app_path().'/Languages/language.php';
        if(isset($language_array[$key])){
            return $language_array[$key];
        }else{
            return $key;
        }
    }
}
function get_dictionary(){
    require app_path().'/Languages/language.php';
    return $language_array;
}

function loop_lang_convert($array){
    $data = [];
    if(count($array) > 0){
        if(get_lang() == 'english'){
            return $array;
        }else {
            foreach ($array as $key => $value) {
                $data[$key] = _lang(trim($value));
            }
        }
    }else{
        $data = $array;
    }
    return $data;
}


function getCF($user_id,$company_id,$field)
{
    
    $obj = UserCustomValues::where([['user_id','=',$user_id],['company_id','=',$company_id]])->first();

    if($obj)
    {
        if($field==1)
        {
            return $obj->CF1;
        }
        elseif($field==2)
        {
         return $obj->CF2;   
        }
        elseif($field==3)
        {
          return $obj->CF3;  
        }
        else
        {
            return '';
        }
    }
    else
    {
        return '';
    }
    }
function isAppUser()
{
    $isAppUser = false;

    if (\Auth::check() && \Auth::user()->type == 'app-users') {
        $isAppUser = true;
    }

    return $isAppUser;
}

function getTotalPosts($id)
{
    $count = CommunityPosts::where('community_id',$id)->count();
    return $count;
}
function getTotalUsers($id)
{
    $count = CommunityUser::where('community_id',$id)->count();
    return $count;
}

