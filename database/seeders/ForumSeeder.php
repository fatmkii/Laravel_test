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
            'is_anonymous' => 1,
            'description' => '存档，专楼，想留下记录的请来此处',
        ]);
        Forum::create([
            'id' =>  12,
            'name' => '海滨乐园岛',
            'is_anonymous' => 1,
            'description' => '新来的朋友请看此处，饼干验证，杂物和无法分类的请来此岛',
        ]);
        Forum::create([
            'id' =>  40,
            'name' => '——大舞厅',
            'description' => '——大舞台，想发春你就来，24小时锁贴制，第二天不带走一片云彩',
        ]);
    }
}
