<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Forum;
use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\QueryException;
use App\Common\ResponseCode;
use Carbon\Carbon;

use function Symfony\Component\VarDumper\Dumper\esc;

class ThreadController extends Controller
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
            'title' => 'required|string',
            'content' => 'required|string',
            'anti_jingfen' => 'required|boolean',
            'nissin_time' => 'integer',
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

        //确认是否冒认管理员发公告
        if ($user->admin == 0 && $request->subtitle == "[公告]") {
            return response()->json(
                [
                    'code' => ResponseCode::ADMIN_UNAUTHORIZED,
                    'message' => ResponseCode::$codeMap[ResponseCode::ADMIN_UNAUTHORIZED],
                ],
            );
        }

        //如果饼干被ban，直接返回错误
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

        //查询饼干是否在封禁期
        if ($user->lockedTTL) {
            $lockTTL_hours = intval($user->lockedTTL / 3600) + 1;
            return response()->json(
                [
                    'code' => ResponseCode::USER_LOCKED,
                    'message' => ResponseCode::$codeMap[ResponseCode::USER_LOCKED] . '，将于' . $lockTTL_hours . '小时后解封',
                ],
            );
        }

        //执行追加新主题流程
        try {
            DB::beginTransaction();
            if ($request->title_color) {
                $user = User::where('binggan', $request->binggan)->first();
                if ($user->coin < 500) {
                    return response()->json(
                        [
                            'code' => ResponseCode::COIN_NOT_ENOUGH,
                            'message' => ResponseCode::$codeMap[ResponseCode::COIN_NOT_ENOUGH],
                        ],
                    );
                }
                $user->coin -= 500; //设置标题颜色减500奥利奥   
                $user->save();
                DB::commit();
            }
            //发主题帖（Thread）
            $thread = new Thread;
            $thread->created_binggan = $request->binggan;
            $thread->forum_id = $request->forum_id;
            if ($request->subtitle == '[公告]') {
                $thread->sub_id = $request->admin_subtitle ?   10 : 99; //$request->admin_subtitle == 0是全岛公告。把sub_id=99
            }
            $thread->title = $request->title;
            $thread->nickname = $request->nickname;
            $thread->created_ip = $request->ip();
            $thread->sub_title = $request->subtitle;
            if ($request->nissin_time > 0) { //如果请求中带有nissin_time，才设定nissin_date
                $thread->nissin_date = Carbon::now()->addSeconds($request->nissin_time);
            }
            $thread->title_color = $request->title_color;
            $thread->anti_jingfen = $request->anti_jingfen;
            $thread->save();
            DB::commit(); //先提交一次，不然$thread没有id.
            //发主题帖的第0楼（Post）
            $post = new Post;
            $post->setSuffix(intval($thread->id / 10000));
            $post->created_binggan = $request->binggan;
            $post->forum_id = $request->forum_id;
            $post->thread_id = $thread->id;
            $post->content = $request->content;
            $post->nickname = $request->nickname;
            $post->created_ip = $request->ip();
            $post->random_head = random_int(1, 40);
            $post->floor = 0;
            $post->save();
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
                'message' => '发表主题成功！',
                'data' => [
                    'forum_id' => $request->forum_id,
                    'thread_id' => $request->thread_id,
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
    public function show(Request $request, $Thread_id)
    {
        $CurrentThread = Thread::find($Thread_id);
        $CurrentForum = $CurrentThread->forum;

        //判断帖子是否已经被日清
        if ($CurrentForum->is_nissin && $CurrentThread->nissin_date < Carbon::now() && $CurrentThread->sub_id == 0) {
            return response()->json([
                'code' => ResponseCode::THREAD_WAS_NISSINED,
                'message' => ResponseCode::$codeMap[ResponseCode::THREAD_WAS_NISSINED],
            ]);
        }

        $posts = Post::suffix(intval($Thread_id / 10000))->where('thread_id', $Thread_id)->orderBy('floor', 'asc')->paginate(200);

        //如果有提供binggan，为每个post输入binggan，用来判断is_your_post（为前端提供是否是用户自己帖子的判据）
        if ($request->query('binggan')) {
            foreach ($posts as $post) {
                $post->setBinggan($request->query('binggan'));
            }
        }

        //为反精分帖子加上created_binggan_hash
        if ($CurrentThread->anti_jingfen) {
            $posts->append('created_binggan_hash');
        }

        return response()->json([
            'code' => ResponseCode::SUCCESS,
            'forum_data' => $CurrentForum,
            'thread_data' => $CurrentThread,
            'posts_data' => $posts,
        ]);
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
}
