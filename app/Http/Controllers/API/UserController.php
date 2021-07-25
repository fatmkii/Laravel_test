<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Common\ResponseCode;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserController extends Controller
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
    public function show(Request $request)
    {
        $request->validate([
            'binggan' => 'required|string',
        ]);

        $user = $request->user();
        $user->last_login = Carbon::now();
        $user->save();
        if ($user->binggan == $request->get('binggan')) {
            return response()->json(
                [
                    'code' => ResponseCode::SUCCESS,
                    'message' => '饼干认证成功，欢迎回来',
                    'data' => [
                        'binggan' => $user,
                    ],
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'code' => ResponseCode::CANNOTLOGIN,
                    'message' => ResponseCode::$codeMap[ResponseCode::CANNOTLOGIN],
                    'data' => [],
                ],
                401
            );
        }
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = new User;
            do {
                $binggan = Str::random(9);
                $binggan_hash = hash('sha256', $binggan);
            } while (!empty(User::where('binggan', $binggan)->first));
            $user->binggan = $binggan;
            $user->binggan_hash = $binggan_hash;
            $user->created_ip = $request->ip();
            $user->coin = 10000;
            $user->save();
            DB::commit();
        } catch (QueryException $e) {
            DB::rollback();
            return response()->json([
                'code' => ResponseCode::DATABASE_FAILED,
                'message' => ResponseCode::$codeMap[ResponseCode::DATABASE_FAILED] . '，请重试',
            ]);
        }
        $token = $user->createToken($binggan)->plainTextToken;
        return response()->json(
            [
                'code' => ResponseCode::SUCCESS,
                'message' => '创建饼干成功！',
                'data' => [
                    'binggan' => $binggan,
                    'token' => $token,
                ],
            ],
            200
        );
    }

    public function reward(Request $request)
    {
        $request->validate([
            'binggan' => 'required|string',
            'post_floor_message' => 'required|string',
            'coin' => 'required|integer',
            'forum_id' => 'required|integer',
            'thread_id' => 'required|integer',
            'post_id' => 'required|integer',
        ]);

        $user = User::where('binggan', $request->binggan)->first();
        $post_target = Post::suffix(intval($request->thread_id / 10000))->find($request->post_id);
        $user_target = User::where('binggan', $post_target->created_binggan)->first();

        //判断用户饼干是否存在
        if (!$user) {
            return response()->json([
                'code' => ResponseCode::USER_NOT_FOUND,
                'message' => ResponseCode::$codeMap[ResponseCode::USER_NOT_FOUND],
            ]);
        }
        //判断对象用户饼干是否存在
        if (!$user_target) {
            return response()->json([
                'code' => ResponseCode::USER_NOT_FOUND,
                'message' => '打赏对象不存在',
            ]);
        }
        //判断用户饼干和对象饼干是否一致
        if ($user_target->binggan == $request->binggan) {
            return response()->json([
                'code' => ResponseCode::DEFAULT,
                'message' => '给自己打赏是想给税收做贡献吗？',
            ]);
        }
        //判断奥利奥是否够
        if ($user->coin < $request->coin) {
            return response()->json([
                'code' => ResponseCode::COIN_NOT_ENOUGH,
                'message' => ResponseCode::$codeMap[ResponseCode::COIN_NOT_ENOUGH],
            ]);
        }

        try {
            DB::beginTransaction();
            $tax = intval($request->coin * 0.07); //税率0.07
            $coin_get = $request->coin - $tax;
            $user->coin -= $request->coin;
            $user_target->coin += $coin_get;
            $user->save();
            $user_target->save();

            $post = new Post;
            $post->setSuffix(intval($request->thread_id / 10000));
            $post->created_binggan = $request->binggan;
            $post->forum_id = $request->forum_id;
            $post->thread_id = $request->thread_id;
            $post->content = "<span class='quote_content'>" .
                $request->post_floor_message .
                '</span><br>我为你打赏了' . $coin_get .
                '块奥利奥<br>——' . $request->content;
            $post->nickname = '奥利奥打赏系统';
            $post->create_by_admin = 2; //0=一般用户 1=管理员发布，2=系统发布
            $post->created_ip = $request->ip();
            $post->random_head = random_int(1, 40);
            $post->floor = Post::suffix(intval($request->thread_id / 10000))->where('thread_id', $request->thread_id)->count();
            $post->save();

            $thread = $post->thread;
            $thread->posts_num = $thread->posts_num + 1;
            $thread->save();

            DB::commit();
        } catch (QueryException $e) {
            DB::rollback();
            return response()->json([
                'code' => ResponseCode::DATABASE_FAILED,
                'message' => ResponseCode::$codeMap[ResponseCode::DATABASE_FAILED] . '，请重试',
            ]);
        }

        return response()->json(
            [
                'code' => ResponseCode::SUCCESS,
                'message' => '打赏成功！对方获得' . $coin_get . '个奥利奥，你减少了' . $request->coin . '个奥利奥',
                'data' => [
                    'binggan' => $request->binggan,
                    'binggan_target' => $request->binggan_target,
                    'coin' => $request->coin
                ]
            ],
        );
    }
}
