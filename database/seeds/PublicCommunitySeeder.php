<?php

use Illuminate\Database\Seeder;
use App\Models\Community;

class PublicCommunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $arrDate = ['2030-05-16', '2030-05-16', '2030-05-17', '2030-05-18', '2030-05-19'];
        $data = [
            'Nordjylland',
            'Midtjylland',
            'Syddanmark',
            'Hovedstaden',
            'Sjælland'
        ];
        foreach ($data as $key => $row) {
            $obj = new Region();
            $obj->company_id = 0;
            $obj->title = $row;
            $obj->active = 1;
            $obj->created_at = $arrDate[$key];
            $obj->save();
        }
        Community::Create($data);
    }
}
