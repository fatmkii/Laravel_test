<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Common\ResponseCode;
use App\Exceptions\CoinException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Models\User;
use App\Models\Post;
use App\Models\Pingbici;
use App\Models\MyEmoji;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
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

        if ($user->is_banned) {
            return response()->json(
                [
                    'code' => ResponseCode::USER_BANNED,
                    'message' => ResponseCode::$codeMap[ResponseCode::USER_BANNED],
                    'data' => [
                        'binggan' => $user->binggan,
                    ],
                ],
                401
            );
        }

        //用redis缓存所有emoji
        $emojis = Cache::remember('emoji_cache',  24 * 3600, function () {
            return DB::table('emoji')->get();
        });

        if ($user->binggan == $request->get('binggan')) {
            return response()->json(
                [
                    'code' => ResponseCode::SUCCESS,
                    'message' => '饼干认证成功，欢迎回来',
                    'data' => [
                        'binggan' => $user,
                        'pingbici' => $user->pingbici,
                        'my_emoji' => $user->MyEmoji,
                        'emojis' => $emojis,
                    ],
                ],
            );
        } else {
            return response()->json(
                [
                    'code' => ResponseCode::CANNOTLOGIN,
                    'message' => ResponseCode::$codeMap[ResponseCode::CANNOTLOGIN],
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
        if (Redis::exists('reg_record_' . $request->ip())) {
            $limted_day = intval(Redis::TTL('reg_record_' . $request->ip()) / 86400) + 1;
            return response()->json([
                'code' => ResponseCode::USER_REGISTER_FAIL,
                'message' => ResponseCode::$codeMap[ResponseCode::USER_REGISTER_FAIL] . '，你只能在'
                    . $limted_day . '天后再领取饼干。',
            ]);
        }

        try {
            DB::beginTransaction();
            $user = new User;
            do {
                $binggan = Str::random(9);
            } while (!empty(User::where('binggan', $binggan)->first));
            $user->binggan = $binggan;
            $user->created_ip = $request->ip();
            $user->coin = 0;
            $user->save();
            DB::commit();
        } catch (QueryException $e) {
            DB::rollback();
            return response()->json([
                'code' => ResponseCode::DATABASE_FAILED,
                'message' => ResponseCode::$codeMap[ResponseCode::DATABASE_FAILED] . '，请重试',
            ]);
        }
        $token = $user->createToken($binggan, ['normal'])->plainTextToken;
        //用redis记录饼干申请ip。限定7天内只能申请1次。
        Redis::setex('reg_record_' . $request->ip(), 7 * 24 * 3600, 1);

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

    public function check_reg_record(Request $request)
    {
        return response()->json(
            [
                'code' => ResponseCode::SUCCESS,
                'message' => '申请饼干记录TTL',
                'data' => [
                    'reg_record_TTL' => Redis::TTL('reg_record_' . $request->ip()),
                ],
            ],
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
            if ($user->coin < 0) {
                throw new CoinException();
            }
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
            $post->created_by_admin = 2; //0=一般用户 1=管理员发布，2=系统发布
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
        } catch (CoinException $e) {
            DB::rollback();
            return response()->json(
                [
                    'code' => ResponseCode::COIN_NOT_ENOUGH,
                    'message' => ResponseCode::$codeMap[ResponseCode::COIN_NOT_ENOUGH],
                ],
            );
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

    public function pingbici_set(Request $request)
    {
        $request->validate([
            'binggan' => 'required|string',
            'use_pingbici' => 'required|boolean',
            'title_pingbici' => 'json|max:1000',
            'content_pingbici' => 'json|max:1000',
        ]);

        $user = User::where('binggan', $request->binggan)->first();
        if (!$user) {
            return response()->json(
                [
                    'code' => ResponseCode::USER_NOT_FOUND,
                    'message' => ResponseCode::$codeMap[ResponseCode::USER_NOT_FOUND],
                ],
            );
        }

        if ($user->pingbici) {
            $pingbici = $user->pingbici;
        } else {
            $pingbici = new Pingbici();
        }
        try {
            DB::beginTransaction();
            $user->use_pingbici = $request->use_pingbici;
            $pingbici->user_id = $user->id;
            $pingbici->title_pingbici = $request->title_pingbici;
            $pingbici->content_pingbici = $request->content_pingbici;
            $user->save();
            $pingbici->save();
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
                'message' => '已设定屏蔽词',
                'data' => [
                    'user' => $user,
                    'pingbici' => $pingbici,
                ]
            ],
        );
    }

    public function my_emoji_set(Request $request)
    {
        $request->validate([
            'binggan' => 'required|string',
            'my_emoji' => 'json|max:5000',
        ]);

        $user = User::where('binggan', $request->binggan)->first();
        if (!$user) {
            return response()->json(
                [
                    'code' => ResponseCode::USER_NOT_FOUND,
                    'message' => ResponseCode::$codeMap[ResponseCode::USER_NOT_FOUND],
                ],
            );
        }

        if ($user->MyEmoji) {
            $my_emoji = $user->MyEmoji;
        } else {
            $my_emoji = new MyEmoji();
        }
        try {
            DB::beginTransaction();
            $my_emoji->user_id = $user->id;
            $my_emoji->emojis = $request->my_emoji;
            $my_emoji->save();
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
                'message' => '已设定我的表情包',
                'data' => [
                    'user' => $user,
                    'my_emoji' => $my_emoji,
                ]
            ],
        );
    }
}
