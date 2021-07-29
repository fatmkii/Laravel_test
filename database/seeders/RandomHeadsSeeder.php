<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RandomHeadsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('random_heads')->insert([
            'name' => '咒岛',
            'random_heads' => json_encode(array(
                'https://www.z4a.net/images/2021/04/28/1ec2f3b016cc3993d.png',
                'https://www.z4a.net/images/2021/04/28/2c9d9c4cfced955f1.png',
                'https://www.z4a.net/images/2021/04/28/3a72868d2ed97544e.png',
                'https://www.z4a.net/images/2021/04/28/4a530a7008124f895.png',
                'https://www.z4a.net/images/2021/04/28/16.png',
                'https://www.z4a.net/images/2021/04/28/5c3d0f8f7e94c0f87.png',
                'https://www.z4a.net/images/2021/04/28/6.png',
                'https://www.z4a.net/images/2021/04/28/7.png',
                'https://www.z4a.net/images/2021/04/28/8.png',
                'https://www.z4a.net/images/2021/04/28/9.png',
                'https://www.z4a.net/images/2021/04/28/17.png',
                'https://www.z4a.net/images/2021/04/28/10a78173cca4d2b9e4.png',
                'https://www.z4a.net/images/2021/04/28/116817e6f9946a883b.png',
                'https://www.z4a.net/images/2021/04/28/12d6faaf27dd411273.png',
                'https://www.z4a.net/images/2021/04/28/13cd6b9af2dee496c2.png',
                'https://www.z4a.net/images/2021/04/28/158cb6d1c543151992.png',
                'https://www.z4a.net/images/2021/04/28/191dbde0611454f9c4.png',
                'https://www.z4a.net/images/2021/04/28/149dd44da8e8badd09.png',
                'https://www.z4a.net/images/2021/04/28/189f30c4bbf3eb9015.png',
                'https://www.z4a.net/images/2021/04/28/201161035e7d5b8c56.png',
                'https://www.z4a.net/images/2021/04/28/21.png',
                'https://www.z4a.net/images/2021/04/28/222dc14b16d6f5e3ad.png',
                'https://www.z4a.net/images/2021/04/28/23.png',
                'https://www.z4a.net/images/2021/04/28/24.png',
                'https://www.z4a.net/images/2021/04/28/25.png',
                'https://www.z4a.net/images/2021/04/28/26.png',
                'https://www.z4a.net/images/2021/04/28/27.png',
                'https://www.z4a.net/images/2021/04/28/28.png',
                'https://www.z4a.net/images/2021/04/28/29.png',
                'https://www.z4a.net/images/2021/04/28/30.png',
                'https://www.z4a.net/images/2021/04/28/31.png',
                'https://www.z4a.net/images/2021/04/28/32.png',
                'https://www.z4a.net/images/2021/04/28/333753a209916c1bac.png',
                'https://www.z4a.net/images/2021/04/28/34.png',
                'https://www.z4a.net/images/2021/04/28/35.png',
                'https://www.z4a.net/images/2021/04/28/36.png',
                'https://www.z4a.net/images/2021/04/28/37.png',
                'https://www.z4a.net/images/2021/04/28/38.png',
                'https://www.z4a.net/images/2021/04/28/39.png',
                'https://www.z4a.net/images/2021/04/28/40.png',
            ))
        ]);
    }
}
