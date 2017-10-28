<?php

use Illuminate\Database\Seeder;

class GenresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('genres')->insert([
            ['id' => 1, 'name' => 'Comedy ', 'description' => 'Comedy'],
            ['id' => 2, 'name' => 'Suspense', 'description' => 'Suspense'],
            ['id' => 3, 'name' => 'Adventure', 'description' => 'Adventure'],
        ]);
    }
}
