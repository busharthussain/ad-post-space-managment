<?php

use Illuminate\Database\Seeder;

use App\Models\Category;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            0 => ['name' => 'All', 'image' => 'all-cat.png'],
            1 => ['name' => 'Fashion', 'image' => 'fashion-cat.png'],
            2 => ['name' => 'Interior', 'image' => 'interior-cat.png'],
            3 => ['name' => 'Electronics', 'image' => 'electronics-cat.png'],
            4 => ['name' => 'Sound', 'image' => 'sound-cat.png'],
            5 => ['name' => 'Transport', 'image' => 'transport-cat.png'],
            6 => ['name' => 'Personal Care', 'image' => 'personal-care-cat.png'],
            7 => ['name' => 'Leisure', 'image' => 'leisure-cat.png'],
            8 => ['name' => 'Tools', 'image' => 'tools-cat.png'],
            9 => ['name' => 'Services', 'image' => 'service-cat.png'],
            10 => ['name' => 'Miscellaneours', 'image' => 'miscellaneous-cat.png'],
            11 => ['name' => 'Old Junk', 'image' => 'junk-cat.png'],
        ];
        foreach ($data as $row) {
            $obj = new Category();
            $obj->name = $row['name'];
            $obj->image = $row['image'];
            $obj->save();
        }
//        Region::Create($data);
    }
}
