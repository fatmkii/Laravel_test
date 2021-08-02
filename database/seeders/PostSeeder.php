<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use Faker\Factory;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Post::factory(5000)->create();
        $faker = Factory::create();
        for ($i = 0; $i < 5000; $i++) {
            DB::table('posts_1')->insert([
                'forum_id' => $faker->randomElement(array('1', '12', '40')),
                'thread_id' => $faker->randomNumber($nbDigits = 1) + 10000,
                'floor' => $faker->randomNumber($nbDigits = 2),
                'random_head' => $faker->randomDigit(),
                'content' => $faker->text(200),
            ]);
        }
    }
}
