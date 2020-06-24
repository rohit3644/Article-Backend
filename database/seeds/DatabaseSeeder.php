<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 15; $i++) {
            DB::table('articles')->insert([
                'title' => Str::random(10),
                'content' => Str::random(100),
                'author_name' => Str::random(10),
                'image_name' => '1592416673.jpeg',
                'is_approved' => "No",
            ]);
        }
    }
}
