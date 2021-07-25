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
            'anti_jingfen' => 'required|boolean'
        ]);

        try {
            DB::beginTransaction();
            if ($request->title_color) {
                $user = User::where('binggan', $request->binggan)->first();
                $user->coin -= 500; //设置标题颜色减500奥利奥
                $user->save();
                DB::commit();
            }
            //发主题帖（Thread）
            $thread = new Thread;
            $thread->created_binggan = $request->binggan;
            $thread->forum_id = $request->forum_id;
            $thread->title = $request->title;
            $thread->nickname = $request->nickname;
            $thread->created_ip = $request->ip();
            $thread->sub_title = $request->subtitle;
            $thread->nissin_time = $request->nissin_time;
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
        $CurrentThread = Thread::where('id', $Thread_id)->first();
        $CurrentForum = $CurrentThread->forum;
        if ($request->query('binggan')) {
            $posts = Post::suffix(intval($Thread_id / 10000))->where('thread_id', $Thread_id)->orderBy('floor', 'asc')->paginate(10);
            foreach ($posts as $post) {
                $post->setBinggan($request->query('binggan')); //为每个post输入binggan，用来判断is_your_post（为前端提供是否是用户自己帖子的判据）
            }
        } else {
            $posts = Post::suffix(intval($Thread_id / 10000))->where('thread_id', $Thread_id)->orderBy('floor', 'asc')->paginate(10);
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
