<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('lang/switch/{lang}', array('as' => 'changeLanguage' ,'uses' => 'HomeController@changeLanguage'));
Auth::routes();

Route::get('/', array('as' => 'home_page' ,'uses' => 'admin\DashboardController@index'));
Route::get('/test', array('as' => 'test' ,'uses' => 'admin\CompanyController@test'));
Route::get('babar/test', array('as' => 'babar.test' ,'uses' => 'admin\CompanyController@babar_test'));
Route::get('/home', array('as' => 'home_page' ,'uses' => 'admin\DashboardController@index'));
Route::get('/dashboard/{month?}', array('as' => 'super.admin.dashboard' ,'uses' => 'admin\DashboardController@index'));

/***********************************************/
// company routes
/***********************************************/
Route::get('/companies', array('as' => 'super.admin.company' ,'uses' => 'admin\CompanyController@index'));
Route::get('/company/create', array('as' => 'super.admin.create.company' ,'uses' => 'admin\CompanyController@create'));
Route::get('/company/edit/{id?}', array('as' => 'super.admin.edit.company' ,'uses' => 'admin\CompanyController@edit'));
Route::get('/company/users/{id}', array('as' => 'super.admin.company.users' ,'uses' => 'admin\UsersController@companyUsers'));
Route::post('/company/community/users/{id}', array('as' => 'super.admin.company.community.users' ,'uses' => 'admin\UsersController@companyCommunityUsers'));


Route::post('/company/upload/image', array('as' => 'company.upload.image' ,'uses' => 'admin\CompanyController@uploadFile'));
Route::post('/company/upload/pdf', array('as' => 'company.upload.pdf' ,'uses' => 'admin\CompanyController@uploadFile'));
Route::post('/company/add/', array('as' => 'company.add' ,'uses' => 'admin\CompanyController@store'));
Route::post('/company/get/', array('as' => 'company.get' ,'uses' => 'admin\CompanyController@getCompanies'));
Route::post('/company/delete/', array('as' => 'company.delete' ,'uses' => 'admin\CompanyController@destroy'));

/***********************************************************/
//community routes
/**********************************************************/

Route::get('/communities/{id?}', array('as' => 'super.admin.community' ,'uses' => 'admin\CommunityController@index'));
Route::get('/community/create/{id?}', array('as' => 'super.admin.create.community' ,'uses' => 'admin\CommunityController@create'));
Route::get('/community/edit/{id?}', array('as' => 'super.admin.edit.community' ,'uses' => 'admin\CommunityController@edit'));
Route::post('/community/upload/image', array('as' => 'community.upload.image' ,'uses' => 'admin\CommunityController@uploadFile'));
Route::post('/community/add/', array('as' => 'community.add' ,'uses' => 'admin\CommunityController@store'));
Route::post('/community/get/', array('as' => 'community.get' ,'uses' => 'admin\CommunityController@getCommunities'));
Route::post('/community/delete/', array('as' => 'community.delete' ,'uses' => 'admin\CommunityController@destroy'));
Route::get('/qrcode/download/{id?}', array('as' => 'qrcode.download' ,'uses' => 'admin\CommunityController@downloadImage'));
Route::get('join/communities', array('as' => 'community.join' ,'uses' => 'admin\CommunityController@joinCommunities'));
Route::post('get/communities/join', array('as' => 'get.community.join' ,'uses' => 'admin\CommunityController@getJoinCommunities'));
Route::post('join/communities/action', array('as' => 'join.community.action' ,'uses' => 'admin\CommunityController@joinCommunityAction'));
Route::get('/community/invitation/{id?}', array('as' => 'community.invitation' ,'uses' => 'admin\CommunityController@communityInvitation'));
Route::post('send/invitation/community', array('as' => 'send.invitation.community' ,'uses' => 'admin\CommunityController@sendInvitationCommunity'));
Route::get('/public-community/{country?}', array('as' => 'communities.all.public' ,'uses' => 'admin\CommunityController@publicCommunity'));
Route::get('/community/search', array('as' => 'community.search' ,'uses' => 'admin\CommunityController@searchCommunities'));
Route::get('/community/user-all/{id}', array('as' => 'communities.all.users' ,'uses' => 'admin\CommunityController@publicCommunityUsers'));
Route::post('/community/getUsers', array('as' => 'communities.getUsers' ,'uses' => 'admin\CommunityController@getUserOfCommunity'));
Route::post('/community/export-excel', array('as' => 'communities.exportUsers' ,'uses' => 'admin\CommunityController@ExportExcelAllUsers'));

// Route::post('/community/delete/public', array('as' => 'community.delete.public' ,'uses' => 'admin\CommunityController@destroy_admin'));
/***********************************************************/
// post routes
/***********************************************************/

Route::get('/posts/', array('as' => 'super.admin.post' ,'uses' => 'admin\PostController@index'));
Route::get('/post/create/{id?}', array('as' => 'super.admin.create.post' ,'uses' => 'admin\PostController@create'));
Route::get('/post/edit/{id?}', array('as' => 'super.admin.edit.post' ,'uses' => 'admin\PostController@edit'));
Route::post('/post/upload/image', array('as' => 'post.upload.image' ,'uses' => 'admin\PostController@uploadFile'));
Route::post('/post/add/', array('as' => 'post.add' ,'uses' => 'admin\PostController@store'));
Route::post('/post/get/', array('as' => 'post.get' ,'uses' => 'admin\PostController@getPosts'));
Route::post('/post/delete/', array('as' => 'post.delete' ,'uses' => 'admin\PostController@destroy'));
Route::post('/post/delete/image', array('as' => 'delete.post.image' ,'uses' => 'admin\PostController@destroyPostImage'));
Route::get('/post/message/{id?}', array('as' => 'post.message' ,'uses' => 'admin\PostController@postMessage'));
Route::post('get/post/message/', array('as' => 'get.post.messages' ,'uses' => 'admin\PostController@getPostMessages'));

Route::get('/post/chat/{id?}', array('as' => 'post.chat' ,'uses' => 'admin\PostController@postChat'));
Route::post('/send/post/message', array('as' => 'send.post.message' ,'uses' => 'admin\PostController@sendPostMessage'));
Route::post('/post/message/image', array('as' => 'post.message.image' ,'uses' => 'admin\PostController@postMessageImage'));
Route::post('/get/post/chats', array('as' => 'get.post.chats' ,'uses' => 'admin\PostController@getPostChats'));


Route::post('/get/companies', array('as' => 'get.companies' ,'uses' => 'admin\PostController@getCompanies'));
Route::post('/get/communities', array('as' => 'get.communities' ,'uses' => 'admin\PostController@getCommunities'));

Route::get('/reported/posts', array('as' => 'reported.posts' ,'uses' => 'admin\PostController@reportedPosts'));
Route::post('get/reported/posts', array('as' => 'get.reported.posts' ,'uses' => 'admin\PostController@getReportedPosts'));
Route::post('delete/reported/post', array('as' => 'delete.reported.post' ,'uses' => 'admin\PostController@deleteReportedPost'));
Route::post('view/reported/post', array('as' => 'view.reported.post' ,'uses' => 'admin\PostController@viewReportedPost'));
Route::post('report/post/message', array('as' => 'report.post.message' ,'uses' => 'admin\PostController@reportPostMessage'));
Route::post('send/report/post/message', array('as' => 'send.report.post.message' ,'uses' => 'admin\PostController@sendReportPostMessage'));


/************************************************************
 * ads routes
 *************************************************************/

Route::get('/ads/', array('as' => 'super.admin.ad' ,'uses' => 'admin\AdController@index'));
Route::get('/ad/create/{id?}', array('as' => 'super.admin.create.ad' ,'uses' => 'admin\AdController@create'));
Route::get('/ad/edit/{id?}', array('as' => 'super.admin.edit.ad' ,'uses' => 'admin\AdController@edit'));
Route::post('/ad/upload/image', array('as' => 'ad.upload.image' ,'uses' => 'admin\AdController@uploadFile'));
Route::post('/ad/add/', array('as' => 'ad.add' ,'uses' => 'admin\AdController@store'));
Route::post('/ad/get/', array('as' => 'ad.get' ,'uses' => 'admin\AdController@getAds'));
Route::post('/ad/delete/', array('as' => 'ad.delete' ,'uses' => 'admin\AdController@destroy'));
Route::post('/ad/delete/image', array('as' => 'delete.ad.image' ,'uses' => 'admin\AdController@destroyImage'));

Route::get('/adSpace/clicks', array('as' => 'super.adspace.clicks' ,'uses' => 'admin\AdController@adSpaceClicks'));
Route::post('/get/adSpace/clicks', array('as' => 'super.get.adspace.clicks' ,'uses' => 'admin\AdController@getAdSpaceClicks'));

Route::get('/sub-admins/', array('as' => 'super.admin.sub' ,'uses' => 'admin\SubAdminController@index'));
Route::get('/sub/create/{id?}', array('as' => 'super.admin.create.sub' ,'uses' => 'admin\SubAdminController@create'));
Route::get('/sub/edit/{id?}', array('as' => 'super.admin.edit.sub' ,'uses' => 'admin\SubAdminController@edit'));
Route::post('/sub/admin/add/', array('as' => 'sub.admin.add' ,'uses' => 'admin\SubAdminController@store'));
Route::post('/sub/admins/get/', array('as' => 'sub.get' ,'uses' => 'admin\SubAdminController@getSubAdmins'));
Route::post('/sub/delete/', array('as' => 'sub.delete' ,'uses' => 'admin\SubAdminController@destroy'));


//Users
Route::get('/users/', array('as' => 'super.admin.users' ,'uses' => 'admin\UsersController@index'));
Route::get('/users/all/', array('as' => 'users.all' ,'uses' => 'admin\UsersController@getAppUsers'));
Route::post('/user/update/custom-field/{id}', 'admin\UsersController@updateCustomFeild');
Route::post('/user/update/custom-field-values/{id}', 'admin\UsersController@updateCustomFeildValue');
Route::post('/user/search/{id?}', 'admin\UsersController@search');
Route::post('/user/export-excel', 'admin\UsersController@userExportExcel');
Route::post('/all-user/export-excel', 'admin\UsersController@ExportExcelAllUsers');
Route::get('/all-user/search', 'admin\UsersController@userAllSearch');
Route::get('/all-user-sort', 'admin\UsersController@userSort');


//users new
Route::post('/aapUsers/', array('as' => 'aapUsers.get' ,'uses' => 'admin\UsersController@getUserOfApp'));
// Notifications route

Route::get('/notifications/', array('as' => 'super.admin.notifications' ,'uses' => 'admin\NotificationManagementController@index'));
Route::get('/notification/create/{id?}', array('as' => 'super.admin.create.notification' ,'uses' => 'admin\NotificationManagementController@create'));
Route::get('/notification/edit/{id?}', array('as' => 'super.admin.edit.notification' ,'uses' => 'admin\NotificationManagementController@edit'));
Route::post('/notification/add/', array('as' => 'notification.add' ,'uses' => 'admin\NotificationManagementController@store'));
Route::post('/notification/get/', array('as' => 'notification.get' ,'uses' => 'admin\NotificationManagementController@getNotifications'));
Route::post('/communities/get/', array('as' => 'super.admin.get.communities' ,'uses' => 'admin\NotificationManagementController@getCommunities'));
Route::post('/notification/delete/', array('as' => 'notification.delete' ,'uses' => 'admin\NotificationManagementController@destroy'));
Route::post('/notification/resend/', array('as' => 'notification.resend' ,'uses' => 'admin\NotificationManagementController@notificationResend'));
Route::get('/test/notification/', array('as' => 'notification.test' ,'uses' => 'admin\NotificationManagementController@testNotification'));
 
// stats route

Route::get('/user/stats', array('as' => 'user.stats' ,'uses' => 'admin\StatsController@userStats'));
Route::get('/user/stats/detail/{id?}', array('as' => 'user.stats.detail' ,'uses' => 'admin\StatsController@userStatsDetail'));
Route::post('/user/action}', array('as' => 'user.action' ,'uses' => 'admin\StatsController@userActivate'));
Route::post('/user/stats/excel', array('as' => 'user.stats.excel' ,'uses' => 'admin\StatsController@exportUserStatExcel'));
Route::post('/user/stats/', array('as' => 'stats.get' ,'uses' => 'admin\StatsController@getUserStats'));

// company stats

Route::get('/company/stats', array('as' => 'company.stats' ,'uses' => 'admin\StatsController@companyStats'));
Route::get('/company/stats/detail/{id?}', array('as' => 'company.stats.detail' ,'uses' => 'admin\StatsController@companyStatsDetail'));
Route::post('/company/stats/excel', array('as' => 'user.company.excel' ,'uses' => 'admin\StatsController@exportCompanyStatExcel'));
Route::post('/company/stats/', array('as' => 'company.get.stats' ,'uses' => 'admin\StatsController@getCompanyStats'));

// communities stats

Route::get('/community/stats', array('as' => 'communities.stats' ,'uses' => 'admin\StatsController@communitiesStats'));
Route::post('/community/stats/excel', array('as' => 'user.community.excel' ,'uses' => 'admin\StatsController@exportCommunityStatExcel'));
Route::post('/community/stats/', array('as' => 'communities.get.stats' ,'uses' => 'admin\StatsController@getCommunitiesStats'));

// post stats

Route::get('/post/stats', array('as' => 'post.stats' ,'uses' => 'admin\StatsController@postStats'));
Route::post('/post/stats/excel', array('as' => 'user.post.excel' ,'uses' => 'admin\StatsController@exportPostStatExcel'));
Route::post('/top/post/stats/', array('as' => 'post.get.stats' ,'uses' => 'admin\StatsController@getPostStats'));

// Top searches

Route::get('/top/post/search', array('as' => 'top.post.search.stats' ,'uses' => 'admin\StatsController@topSearchPostStats'));
Route::post('/top/search/stats/excel', array('as' => 'user.top.post.search.excel' ,'uses' => 'admin\StatsController@exportTopPostSearchStatExcel'));
Route::post('/top/post/search/', array('as' => 'top.post.search.get.stats' ,'uses' => 'admin\StatsController@getTopSearchPostStats'));

Route::get('/chart', array('as' => 'chart.stats' ,'uses' => 'admin\StatsController@index'));

