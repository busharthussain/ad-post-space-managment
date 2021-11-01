<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    /**
     * This is used to get post category data
     *
     * @param $params
     * @return mixed
     */
    public static function getPostCategory($params)
    {
        $sql = \DB::table('categories as c')->select('c.id', 'c.name', 'c.image');
        $sql->join('category_post as cp', function ($join) use ($params) {
            $join->on('cp.category_id', '=', 'c.id')
                ->where('cp.post_id', '=', $params['post_id']);
        });

        return $sql->get()->toArray();
    }
}
