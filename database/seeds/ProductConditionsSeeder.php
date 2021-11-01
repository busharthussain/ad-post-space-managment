<?php

use Illuminate\Database\Seeder;
use App\Models\ProductCondition;

class ProductConditionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'New',
            'Old',
            'Very Old'
        ];
        foreach ($data as $row) {
            $obj = new ProductCondition();
            $obj->name = $row;
            $obj->save();
        }
    }
}
