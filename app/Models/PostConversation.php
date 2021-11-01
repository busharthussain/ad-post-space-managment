<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostConversation extends Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'conversation_id', 'type', 'message'];

    /**
     * This is used to get post messages
     *
     * @return array
     */
    public static function getPostMessages($params)
    {
        $sql = \DB::table('post_conversations as pc')->select(
            'pc.id', 'pc.message as message', 'u.image', 'u.relative_path', 'u.name', 'psc.user_id as request_id', 'psc.receiver_id as request_receiver_id',
            'pc.conversation_id', 'pc.type', 'pc.image as post_image', 'pc.receiver_id', 'pc.sender_id', 'pc.created_at as unformatted_date', 'psc.date_from', 'psc.date_to',
            \DB::raw("DATE_FORMAT(pc.created_at, '%a, %b %d') as created_at")
        );

        $sql->join('users as u', 'u.id', 'pc.sender_id');

        if (!empty($params['post_id'])) {
            $sql->join('post_start_conversation as psc', function ($join) use ($params) {
                $join->on('psc.id', '=', 'pc.conversation_id')
                    ->where('psc.post_id', '=', $params['post_id']);
            });
            $sql->where('pc.receiver_id', '>', 0);
        }

        if (!empty($params['conversation_id'])) {
            $sql->join('post_start_conversation as psc', function ($join) use ($params) {
                $join->on('psc.id', '=', 'pc.conversation_id')
                    ->where('psc.id', '=', $params['conversation_id']);
            });
            $sql->where('pc.conversation_id', '=', $params['conversation_id']);
        } else {
            $sql->groupBy('u.id');
        }

        if (!empty($params['sortColumn']) && !empty($params['sortType'])) {
//            $sql->orderBy($params['sortColumn'], $params['sortType']);
        }
        if (!empty($params['isApiRequest'])) {
            $sql->orderBy('pc.id', 'desc');
        } else {
            $sql->orderBy('pc.id', 'asc');
        }

        $grid = [];
        $grid['query'] = $sql;
        $grid['perPage'] = $params['perPage'];
        $grid['page'] = $params['page'];
        $record = [];

        if ($params['page'] == 1) {
            $sql = \DB::table('post_start_conversation as psc')
                ->select('psc.id', 'psc.id as conversation_id', 'psc.message', 'psc.image_1 as first_image'
                    , 'u.image', 'u.relative_path', 'u.name','psc.created_at as unformatted_date', 'psc.date_to', 'psc.date_from',
                    'psc.image_1', 'psc.image_2', 'psc.image_3','pct.title as option',
                    'psc.user_id as sender_id',
                    \DB::raw("DATE_FORMAT(psc.created_at, '%a, %b %d') as created_at"));
            $sql->join('posts as p', 'p.id', '=', 'psc.post_id');
            $sql->join('parent_categories as pct', 'pct.id', '=', 'p.parent_category_id');
            $sql->join('users as u', 'u.id', 'psc.user_id');
            if (!empty($params['post_id'])) {
                $sql->where('psc.post_id', '=', $params['post_id']);
            }
            if (!empty($params['conversation_id'])) {
                $sql->where('psc.id', '=', $params['conversation_id']);
            }
            $record = (array)$sql->first();
        }

        $data = \Grid::runSql($grid)['result'];
        $data = json_decode(json_encode($data), true);

        if (($record || $data) && !empty($params['isApiRequest'])) {
            if ($data) {
                foreach ($data as $key => $row) {
                    $data[$key]['sender_data'] = User::find($row['sender_id'])->toArray();
                    $data[$key]['relative_thumbnail_path'] = uploadConversationThumbNailImage;
                    $data[$key]['relative_conversation_path'] = uploadPostConversationImage;
                    $data[$key]['first_message'] = false;
                }
                $data = array_reverse($data);
            }
            if (!empty($record)) {
                $record['sender_data'] = User::find($record['sender_id'])->toArray();
                $record['relative_thumbnail_path'] = uploadConversationThumbNailImage;
                $record['relative_conversation_path'] = uploadPostConversationImage;
                $record['first_message'] = true;
                if ($data) {
                    array_unshift($data, $record);
                } else {
                    $data = [];
                    $data[] = $record;
                }
            }
        } else if (empty($params['isApiRequest'])) {
            if (!empty($record)) {
                if ($data) {
                    array_unshift($data, $record);
                } else {
                    $data = [];
                    $data[] = $record;
                }
            }
        }

        return $data;
    }

    /**
     * This is used to get post first message
     *
     * @param $params
     * @return mixed
     */
    public static function getPostLatestMessage($params)
    {
        $sql = \DB::table('post_start_conversation as psc')
            ->select('psc.id as conversation_id', 'psc.message', 'psc.image_1 as first_image', 'psc.post_id as post_id'
                , 'u.image', 'u.relative_path', 'u.name', 'psc.date_to', 'psc.date_from', 'pc.message as post_conversation_message',
                'pc.id as post_conversation_id', 'psc.user_id',
                'psc.receiver_id',
                'psc.date_to as unformatted_psc_date_to',
                'psc.date_from as unformatted_psc_date_from',
                \DB::raw("DATE_FORMAT(psc.created_at, '%a, %b %d') as created_at"),
                \DB::raw("DATE_FORMAT(psc.date_to, '%d-%m-%Y') as date_to"),
                \DB::raw("DATE_FORMAT(psc.date_from, '%d-%m-%Y') as date_from"));
        $sql->join('users as u', 'u.id', 'psc.user_id');
        $sql->leftjoin('post_conversations as pc', 'pc.conversation_id', 'psc.id');
        if (!empty($params['post_id'])) {
            $sql->where('psc.post_id', '=', $params['post_id']);
        }
        if (!empty($params['conversation_id'])) {
            $sql->where('psc.id', '=', $params['conversation_id']);
        }
        if (!empty($params['user_id'])) {
            $sql->where('psc.user_id', '=', $params['user_id']);
        }

        $sql->latest('pc.created_at');
        $data = $sql->get()->unique('conversation_id');

        $record = [];
        if ($data) {
            foreach ($data as $key => $row) {
                $row->message = (!empty($row->post_conversation_message)) ? $row->post_conversation_message : $row->message;
                $row->receiver_data = User::find($row->receiver_id)->toArray();
                $row->sender_data = User::find($row->user_id)->toArray();
                if (!empty($params['isApi'])) {
                    $paramsCount = [
                        'id' => $row->conversation_id,
                        'post_id' => $row->post_id,
                        'user_id' => $params['count_user_id'],
                        'isAll' => false
                    ];
                    $UnreadMessagesCount = self::getSingleUnreadMessagesCount($paramsCount);
                    $row->UnreadMessagesCount = $UnreadMessagesCount;
                }
                $record[] = $row;
            }
        }

        return $record;
    }

    public static function getSingleUnreadMessagesCount($params)
    {
        $count = 0;
        $data = \DB::table('post_start_conversation')->select('id', 'is_read', 'receiver_id')->where('id', '=', $params['id'])->first();

        $countSingle = PostConversation::where('conversation_id', '=', $data->id)
            ->where('receiver_id', '=', $params['user_id'])->where('is_read', '=', 0)->count();
        if (empty($data->is_read) && $data->receiver_id == $params['user_id']) {
            $count = $countSingle + 1;
        } else {
            $count = $countSingle;
        }

        return $count;
    }


    /**
     * This is used to get unread message count
     *
     * @param $params
     * @return int
     */
    public static function getUnreadMessagesCount($params) {
        $count = 0;
        $userId = $params['user_id'];
        $sql = \DB::table('post_start_conversation')->select('id', 'is_read', 'receiver_id');
        if (empty($params['isAll'])) {
            $sql->where(function ($query) use ($userId) {
                $query->where('user_id', '=', $userId)
                    ->orWhere('receiver_id', '=', $userId);
            });
        }
        if (!empty($params['post_id'])) {
            $sql->where('post_id', '=', $params['post_id']);
        }
        if (empty($params['isAll'])) {
            $data = $sql->first();
            $countSingle = PostConversation::where('conversation_id', '=', $data->id)
                ->where('receiver_id', '=', $params['user_id'])->where('is_read', '=', 0)->count();
            if (empty($data->is_read) && $data->receiver_id == $params['user_id']) {
                $count = $countSingle + 1;
            } else {
                $count = $countSingle;
            }
        } else {
            $data = $sql->get();
            if ($data) {
                if ($data) {
                    foreach ($data as $row) {
                        $countSingle = PostConversation::where('conversation_id', '=', $row->id)
                            ->where('receiver_id', '=', $params['user_id'])->where('is_read', '=', 0)->count();
                        $count = $count + $countSingle;
                        if (empty($row->is_read) && $row->receiver_id == $params['user_id']) {
                            $count = $count + 1;
                        }
                    }
                }
            }
        }

        return $count;
    }

}
