<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Forum;
use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Sanctum\HasApiTokens;
use App\Jobs\ReplyProcess;
use App\Models\Thread;
use Illuminate\Support\Facades\Redis;

class TestController extends Controller
{
    public function test()
    {

        return 'test ok';
    }

    public function login()
    {
        return 'login Page';
    }

    public function request(Request $request)
    {
        // $all=$request->cookie();

        return response()->json(['name', 'chin']);
    }

    public function reply_queue()
    {
        $reply = new Reply([
            'forum_id' => '1',
            'thread_id' => '100001',
            'floor' => '5',
            'random_head' => '2',
            'content' => '内容',
        ]);
        // $reply->save();

        ReplyProcess::dispatch(
            new Reply([
                'forum_id' => '1',
                'thread_id' => '100001',
                'floor' => '5',
                'random_head' => '2',
                'content' => '内容',
            ])
        );
        Redis::set('akey', 'avalue');

        return Reply::all();
    }

    public function vue_test($id = '')
    {
        return view('vue_test' . $id);
    }
}
