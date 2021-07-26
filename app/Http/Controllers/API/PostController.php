<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Forum;
use App\Models\Post;
use App\Models\Thread;
use Illuminate\Database\QueryException;
use App\Common\ResponseCode;
use App\Models\User;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'binggan' => 'required|string',
            'forum_id' => 'required|integer',
            'thread_id' => 'required|integer',
            'content' => 'required|string',
            'nickname' => '',
        ]);

        $ban_ip = array('116.199.28.99',);

        if (in_array($request->ip(), $ban_ip)) {
            return response()->json([
                'code' => ResponseCode::USER_PASSWORD_ERROR,
                'message' => ResponseCode::$codeMap[ResponseCode::USER_PASSWORD_ERROR],
            ]);
        }

        try {
            DB::beginTransaction();
            $post = new Post;
            $post->setSuffix(intval($request->thread_id / 10000));
            $post->created_binggan = $request->binggan;
            $post->forum_id = $request->forum_id;
            $post->thread_id = $request->thread_id;
            $post->content = $request->content;
            $post->nickname = $request->nickname;
            $post->created_ip = $request->ip();
            $post->random_head = random_int(1, 40);
            $post->floor = Post::suffix(intval($request->thread_id / 10000))->where('thread_id', $request->thread_id)->count();
            $post->save();

            $thread = $post->thread;
            $thread->posts_num = $thread->posts_num + 1;
            $thread->save();
            $user = User::where('binggan', $request->binggan)->first();
            $user->coin += 10; //回复+10奥利奥
            $user->save();

            DB::commit();
        } catch (QueryException $e) {
            DB::rollback();
            return response()->json([
                'code' => ResponseCode::DATABASE_FAILED,
                'message' => ResponseCode::$codeMap[ResponseCode::DATABASE_FAILED] . '，请重试',
            ]);
        }

        $post_id = $post->id;
        return response()->json(
            [
                'code' => ResponseCode::SUCCESS,
                'message' => '发表回复成功！',
                'data' => [
                    'forum_id' => $request->forum_id,
                    'thread_id' => $request->thread_id,
                    'post_id' => $post_id,
                ]
            ],
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Request $request, $id)
    {
        $request->validate([
            'binggan' => 'required|string',
            'thread_id' => 'required|integer',
        ]);

        $post = Post::suffix(intval($request->thread_id / 10000))->find($id);
        //判断删帖操作者饼干和post饼干是否相同
        if ($post->created_binggan != $request->binggan) {
            return response()->json(
                [
                    'code' => ResponseCode::USER_UNAUTHORIZED,
                    'message' => '饼干错误',
                    'data' => [
                        'post_id' => $id,
                        'created_binggan' => $post->created_binggan,
                        '$request->binggan' => $request->binggan,
                    ]
                ],
            );
        }

        //判断饼干是否足够
        $user = User::where('binggan', $request->binggan)->first();
        if ($user->coin < 300) {
            return response()->json(
                [
                    'code' => ResponseCode::COIN_NOT_ENOUGH,
                    'message' => ResponseCode::$codeMap[ResponseCode::COIN_NOT_ENOUGH],
                    'data' => [
                        'post_id' => $id,
                    ]
                ],
            );
        }

        $post->is_deleted = 1;
        $post->save();
        $user->coin -= 300; //删除帖子扣除300奥利奥
        $user->save();
        return response()->json(
            [
                'code' => ResponseCode::SUCCESS,
                'message' => '删除回复成功！',
                'data' => [
                    'post_id' => $id,
                    '$post' => $post
                ]
            ],
        );
    }
}
