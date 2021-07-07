<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Forum;

class ForumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Forum::create([
            'id' =>  1,
            'name' => '有爱讨论岛',
            'description' => '有爱讨论岛',
        ]);
        Forum::create([
            'id' =>  12,
            'name' => '海滨乐园岛',
            'description' => '海滨乐园岛',
        ]);
        Forum::create([
            'id' =>  40,
            'name' => '发春大舞厅',
            'description' => '发春大舞厅',
        ]);
    }
}
