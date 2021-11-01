<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
Route::get('test', array('as' => 'test' ,'uses' => 'api\ApiController@test'));
Route::post('register', array('as' => 'api.register' ,'uses' => 'api\ApiController@register'));
Route::post('edit/user', array('as' => 'api.edit.user' ,'uses' => 'api\ApiController@register'));
Route::post('user/update/image', array('as' => 'user.update.image' ,'uses' => 'api\ApiController@updateUserImage'));
Route::post('login', array('as' => 'api.login' ,'uses' => 'api\ApiController@login'));
Route::post('logout', array('as' => 'api.logout' ,'uses' => 'api\ApiController@logout'));
Route::post('forget/password', array('as' => 'api.forget.password' ,'uses' => 'api\ApiController@forgetPassword'));
Route::get('curl', array('as' => 'api.curl' ,'uses' => 'api\ApiController@curl'));
Route::get('regions/{coutry?}', array('as' => 'api.regions' ,'uses' => 'api\ApiController@getRegions'));
Route::post('companies', array('as' => 'api.companies' ,'uses' => 'api\ApiController@getCompanies'));
Route::post('assign/company/user', array('as' => 'api.company' ,'uses' => 'api\ApiController@associateCompanyToUser'));
Route::post('user', array('as' => 'api.company' ,'uses' => 'api\ApiController@getUser'));
Route::post('communities', array('as' => 'api.communities' ,'uses' => 'api\ApiController@getCommunities'));
Route::post('categories', array('as' => 'api.categories' ,'uses' => 'api\ApiController@getCategories'));
Route::post('posts', array('as' => 'api.posts' ,'uses' => 'api\ApiController@getPosts'));
Route::post('report/post', array('as' => 'api.report.post' ,'uses' => 'api\ApiController@reportPost'));
Route::post('favourite/post', array('as' => 'api.favourite.post' ,'uses' => 'api\ApiController@favouritePost'));
Route::post('get/favourite/post', array('as' => 'api.get.favourite.post' ,'uses' => 'api\ApiController@getPosts'));
Route::post('add/post', array('as' => 'api.add.post' ,'uses' => 'api\ApiController@addPost'));
Route::post('upload/post/image', array('as' => 'api.upload.post.image' ,'uses' => 'api\ApiController@uploadPostImage'));
Route::post('get/public/community', array('as' => 'api.get.public.community' ,'uses' => 'api\ApiController@getPublicCommunity'));
Route::post('/community', array('as' => 'api.get.community' ,'uses' => 'api\ApiController@getCommunity'));
Route::post('/community/join', array('as' => 'api.community.join' ,'uses' => 'api\ApiController@joinCommunity'));
Route::post('/community/leave', array('as' => 'api.community.leave' ,'uses' => 'api\ApiController@leaveCommunity'));
Route::post('/community/mark', array('as' => 'api.community.mark' ,'uses' => 'api\ApiController@markCommunity'));
Route::post('/community/search', array('as' => 'api.community.search' ,'uses' => 'api\ApiController@searchCommunities'));
Route::post('get/community/joined', array('as' => 'api.community.get.joined' ,'uses' => 'api\ApiController@getJoinedCommunities'));
Route::post('get/user/community/joined', array('as' => 'api.community.get.joined' ,'uses' => 'api\ApiController@getUserJoinedCommunities'));
Route::post('start/post/conversation', array('as' => 'api.start.post.conversation' ,'uses' => 'api\ApiController@startPostConversation'));
Route::post('post/conversation', array('as' => 'api.post.conversation' ,'uses' => 'api\ApiController@postConversation'));
Route::post('post/conversation/upload/image', array('as' => 'api.post.conversation.upload.image' ,'uses' => 'api\ApiController@postStartConversationImage'));

Route::post('get/post/conversation/', array('as' => 'api.get.post.messages' ,'uses' => 'api\ApiController@getPostMessages'));
Route::post('get/post/chat/', array('as' => 'api.get.post.chat' ,'uses' => 'api\ApiController@getPostChats'));
Route::post('get/notification/messages/', array('as' => 'api.get.notification.messages' ,'uses' => 'api\ApiController@getNotificationMessages'));
Route::post('/adspace/click', array('as' => 'api.adspace.click' ,'uses' => 'api\ApiController@addAdSpaceClick'));
Route::post('/get/adspaces', array('as' => 'api.get.adspaces' ,'uses' => 'api\ApiController@getAdSpaces'));
Route::post('/post/trade/completed', array('as' => 'api.adspace.click' ,'uses' => 'api\ApiController@postTradeCompleted'));
Route::post('/delete/post', array('as' => 'delete.post' ,'uses' => 'api\ApiController@deletePost'));
Route::post('/delete/chat/message', array('as' => 'delete.chat.message' ,'uses' => 'api\ApiController@deleteChatMessage'));
Route::post('/delete/message/notification', array('as' => 'delete.message.notification' ,'uses' => 'api\ApiController@deleteMessageNotification'));
Route::post('/read/message', array('as' => 'read.message' ,'uses' => 'api\ApiController@readMessage'));
Route::post('/user/chat/message', array('as' => 'user.chat.message' ,'uses' => 'api\ApiController@userChatMessages'));
Route::post('/read/notification', array('as' => 'read.notification' ,'uses' => 'api\ApiController@readNotification'));

//Route::group(['module' => 'Api', 'prefix' => 'api', 'middleware' => ['cors'],'namespace' => 'App\Http\Controllers\api'], function(){
//    Route::get('/test', array('as' => 'test' ,'uses' => 'api\ApiController@index'));
//});
