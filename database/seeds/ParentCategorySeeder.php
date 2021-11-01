<?php

use Illuminate\Database\Seeder;
use App\Models\ParentCategory;

class ParentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            0 => ['title' => 'Swap', 'image' => 'swap-icon.png'],
            1 => ['title' => 'Borrow', 'image'=> 'borrow-icon.png'],
            2 => ['title' => 'Wanted', 'image'=> 'wanted-icon.png'],
            3 => ['title' => 'Give away', 'image'=> 'give-icon.png']
        ];
        foreach ($data as $row) {
            $obj = new ParentCategory();
            $obj->title = $row['title'];
            $obj->image = $row['image'];
            $obj->save();
        }
//        ParentCategory::Create($data);
    }
}
