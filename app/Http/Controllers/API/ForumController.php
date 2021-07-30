<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Forum;
use App\Models\Thread;
use App\Common\ResponseCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ForumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $forums = Cache::remember('forums_cache',  24 * 3600, function () {
            return Forum::where('id', '<>', 0)->get(); //把0岛隐藏掉
        });
        return response()->json([
            'code' => ResponseCode::SUCCESS,
            'data' => $forums,
        ]);
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
    public function show($forum_id)
    {
        $CurrentForum = Forum::find($forum_id);
        $threads = $CurrentForum->threads()->where('is_deleted', 0);

        //如果是日清版，加入日清条件。
        if ($CurrentForum->is_nissin == true) {
            $threads->where('nissin_date', '>', Carbon::now());
        }

        $threads
            ->orWhere('sub_id', 99) //加入全岛公告（sub_id=99）
            ->orderBy('sub_id', 'desc')->orderBy('updated_at', 'desc'); //sub_id是用来把公告等提前的

        return response()->json([
            'code' => ResponseCode::SUCCESS,
            'message' => ResponseCode::$codeMap[ResponseCode::SUCCESS],
            'forum_data' => $CurrentForum,
            'threads_data' => $threads->paginate(30),
        ]);
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
