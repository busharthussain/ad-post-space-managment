<?php

namespace App\Http\Controllers\admin;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\NotificationManagement;
use App\Models\CommunityUser;
use App\Http\Controllers\admin\SharpeepsTrait;
use Edujugon\PushNotification\PushNotification;

class NotificationManagementController extends Controller
{
    protected $page = 'notification';
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

        return view('admin.notification.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $notificationHide = false;
        if (!isAdminRole()) {
            $id = getCompanyIdByUser();
            $isNotification = Company::find($id)->is_notification;
            if (empty($isNotification)) {
                $notificationHide = true;
            }
        }
        $data = [
            'id' => '',
            'data' => '',
            'page' => $this->page,
            'notificationHide' => $notificationHide
        ];

        return view('admin.notification.create', $data);
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
        $id = $request->input('id');
        if (!empty($id)) {
            $obj = NotificationManagement::find($id);
        } else {
            $obj = new NotificationManagement();
            $obj->read_user_ids = 0;
        }
        $obj->option = $this->data['option'];
        $obj->type = $this->data['type'];
        $obj->device_type = (!empty($this->data['device_type'])) ? $this->data['device_type'] : null;
        $obj->subject = $this->data['subject'];
        $obj->description = $request->input('description');
        $obj->ids = implode(',', $this->data['ids']);
        $obj->created_by = loginId();
        if ($obj->save()) {
            if ($obj->option == 'email') {
                // save log message
                $this->logMessage = loginName() . ' ' . _lang('send an Email to app users.');
                $this->saveChangeLog();
                // end
                if ($obj->type == 'community') {
                    $params = [
                        'ids' => $this->data['ids']
                    ];
                    $emails = CommunityUser::getJoinedCommunityUsers($params);
                    $emails = \GuzzleHttp\json_decode(json_encode($emails, true));
                } else {
                    $emails = User::whereIn('id', $this->data['ids'])->get()->toArray();
                }
                if ($emails) {
                    $emails = array_column($emails, 'email');
                }
                $toEmails = [];
                foreach ($emails as $email) {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $toEmails[] = $email;
                    }
                }
                if (!\App::isLocal()) { // no need to send email in case of saving draft only
                    \Mail::send('email.notification_management', ['subject' => $this->data['subject'], 'description' => $this->data['description']], function ($message) use ($toEmails) {
                        $message->to($toEmails)
                            ->from(\Config::get('mail.from.address'), \Config::get('mail.from.name'))
                            ->subject('Notification Management');
                    });
                }
            } else {
                if ($obj->type == 'community') {
                    $params = [
                        'ids' => $this->data['ids']
                    ];
                    $usersData = CommunityUser::getJoinedCommunityUsers($params);
                    $usersData = json_decode(json_encode($usersData), true);
                } else {
                    $usersData = User::whereIn('id', $this->data['ids'])->where('active', '=', 1)->get()->toArray();
                }
                // save log message
                if ($obj->option == 'notification') {
                    $this->logMessage = loginName() . ' ' . _lang('send a notification to app users.');
                    $this->saveChangeLog();
                } else {
                    $this->logMessage = loginName() . ' ' . _lang('send a Message to app users.');
                    $this->saveChangeLog();
                }
                // end
                if ($usersData) {
                    $androidUsers = $iosUsers = $userIds = [];
                    foreach ($usersData as $row) {
                        if (!empty($row['device_token'])) {
                            $userIds[] = $row['id'];
                            if ($row['device_type'] == 'ios') {
                                $iosUsers[] = $row['device_token'];
                            } else if ($row['device_type'] == 'android') {
                                $androidUsers[] = $row['device_token'];
                            }
                        }
                    }
                }

                if (!empty($userIds)) {
                    $obj->user_ids = implode(',', $userIds);
                    $obj->save();
                }
                $this->notificationTitle = $this->data['subject'];
                $this->notificationMessage = $this->data['description'];
                if (!empty($androidUsers)) {
                    $this->deviceTokens = $androidUsers;
                    $this->deviceType = 'android';
                    $this->sendNotification();
                }
                if (!empty($iosUsers)) {
                    $this->deviceTokens = $iosUsers;
                    $this->deviceType = 'ios';
                    $this->sendNotification();
                }
            }
            $this->success = true;
            $this->message = _lang('Notification is sent successfully');
        }

        return response()->json(['success' => $this->success, 'message' => $this->message]);
    }

    /**
     * This is used to get communities data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommunities(Request $request)
    {
        $id = $request->input('id');
        $ids = [];
        if ($request->input('notification_id')) {
            $ids = NotificationManagement::find($request->input('notification_id'))->ids;
        }
        if ($id == 'user') {
            $sql = \DB::table('users as u')->select('u.id', 'u.name')->where('u.type', '=', AppUserType);
            if (!empty($request->input('device_type'))) {
                $sql->where('u.device_type', '=', $request->input('device_type'));
            }
            if (!isAdminRole()) {
                $id = getCompanyIdByUser();
                $sql->join('community_users as cu', 'cu.user_id', 'u.id');
                $sql->join('communities as c', 'c.id', 'cu.community_id');
                $sql->where('c.company_id', '=', $id);
            }
            if (!empty($request->input('search'))) {
                $search = '%' . $request->input('search') . '%';
                $sql->where(function ($query) use ($search) {
                    $query->Where('u.name', 'LIKE', $search);
                });
            }
            $data = array_merge([0 => ['id' => 0, 'name' => _lang('All Users')]], $sql->get()->toArray());
        } else {
            $sql = Community::select('id', 'title as name');
            if (!isAdminRole()) {
                $id = getCompanyIdByUser();
                $sql->where('company_id', '=', $id);
            }
            if (!empty($request->input('search'))) {
                $search = '%' . $request->input('search') . '%';
                $sql->where(function ($query) use ($search) {
                    $query->Where('title', 'LIKE', $search);
                });
            }
            $data = array_merge([0 => ['id' => 0, 'name' => _lang('All Communities')]], $sql->get()->toArray());
        }

        return response()->json(['data' => $data, 'ids' => $ids]);
    }

    /**
     * This is used to get notifications
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotifications(Request $request)
    {
        $params = [
            'perPage' => 10,
            'page' => $request->input('page'),
            'search' => $request->input('search'),
            'sortColumn' => $request->input('sortColumn'),
            'sortType' => $request->input('sortType'),
        ];
        $data = NotificationManagement::getNotifications($params);

        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = [
            'id' => $id,
            'data' => NotificationManagement::find($id),
            'page' => $this->page
        ];

        return view('admin.notification.create', $data);
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
        $obj = NotificationManagement::find($splitId);
        $this->message = _lang('There is problem to delete Notification');
        if ($obj && $obj->delete()) {
            $this->message = _lang('Notification is deleted successfully');
            $this->success = true;
        }

        return response()->json(['success' => $this->success, 'message' => $this->message, 'id' => $splitId]);
    }

    /**
     * This is used to resend notification
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function notificationResend(Request $request)
    {
        $obj = NotificationManagement::find(explode('_', $request->input('id'))[1]);
        $type = $obj->type;
        $ids = explode(',', $obj->ids);

        if ($type == 'community') {
            $params = [
                'ids' => $ids
            ];
            $usersData = CommunityUser::getJoinedCommunityUsers($params);
            $usersData = json_decode(json_encode($usersData), true);
        } else {
            $usersData = User::whereIn('id', $ids)->get()->toArray();
        }

        if ($usersData) {
            $androidUsers = $iosUsers = [];
            foreach ($usersData as $row) {
                if (!empty($row['device_token'])) {
                    if ($row['device_type'] == 'ios') {
                        $iosUsers[] = $row['device_token'];
                    } else if ($row['device_type'] == 'android') {
                        $androidUsers[] = $row['device_token'];
                    }
                }
            }
        }
        $this->notificationTitle = $obj->subject;
        $this->notificationMessage = $obj->description;
        if (!empty($androidUsers)) {
            $this->deviceTokens = $androidUsers;
            $this->deviceType = 'android';
            $this->sendNotification();
        }
        if (!empty($iosUsers)) {
            $this->deviceTokens = $iosUsers;
            $this->deviceType = 'ios';
            $this->sendNotification();
        }

        $this->success = true;
        $this->message = _lang('Notification is resend successfully');

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
            0 => ['name' => _lang('Subject'), 'sorterKey' => 'subject', 'isSorter' => true],
            1 => ['name' => _lang('Option'), 'sorterKey' => 'option', 'isSorter' => true],
            2 => ['name' => _lang('Send Date'), 'sorterKey' => 'created_at', 'isSorter' => false],
            3 => ['name' => _lang('ACTION'), 'isSorter' => false]
        ];
    }
}
