<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call('CategoriesSeeder');
        $this->call('ParentCategorySeeder');
        $this->call('ProductConditionsSeeder');
        $this->command->info('Seeded the countries!');
    }
}
