<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Common\ResponseCode;
use App\Jobs\ProcessUserActive;
use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use App\Models\UserActive;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class AdminController extends Controller
{
    public function thread_delete(Request $request)
    {
        $request->validate([
            'thread_id' => 'required|integer',
            'content' => 'required|string|max:255'
        ]);

        $user = $request->user();
        if (!$user->tokenCan('admin')) {
            return response()->json([
                'code' => ResponseCode::ADMIN_UNAUTHORIZED,
                'message' => ResponseCode::$codeMap[ResponseCode::ADMIN_UNAUTHORIZED],
            ]);
        }

        $thread = Thread::find($request->thread_id);
        if (!$thread) {
            return response()->json([
                'code' => ResponseCode::THREAD_NOT_FOUND,
                'message' => ResponseCode::$codeMap[ResponseCode::THREAD_NOT_FOUND],
            ]);
        }

        //确认是否拥有该版面的管理员权限
        if (
            !in_array($thread->forum_id, json_decode($user->AdminPermissions->forums))
        ) {
            return response()->json(
                [
                    'code' => ResponseCode::ADMIN_UNAUTHORIZED,
                    'message' => ResponseCode::$codeMap[ResponseCode::ADMIN_UNAUTHORIZED],
                ],
            );
        }


        $thread->is_deleted = 2;
        $thread->save();
        ProcessUserActive::dispatch(
            [
                'binggan' => $user->binggan,
                'user_id' => $user->id,
                'active' => '管理员删除了主题',
                'thread_id' => $thread->id,
                'content' => $request->content,
            ]
        );

        return response()->json([
            'code' => ResponseCode::SUCCESS,
            'message' => '该主题已删除。',
            'data' => [
                'thread_id' => $thread->id,
            ],
        ]);
    }

    public function thread_set_top(Request $request)
    {
        $request->validate([
            'thread_id' => 'required|integer',
        ]);

        $user = $request->user();
        if (!$user->tokenCan('admin')) {
            return response()->json([
                'code' => ResponseCode::ADMIN_UNAUTHORIZED,
                'message' => ResponseCode::$codeMap[ResponseCode::ADMIN_UNAUTHORIZED],
            ]);
        }

        $thread = Thread::find($request->thread_id);
        if (!$thread) {
            return response()->json([
                'code' => ResponseCode::THREAD_NOT_FOUND,
                'message' => ResponseCode::$codeMap[ResponseCode::THREAD_NOT_FOUND],
            ]);
        }

        //确认是否拥有该版面的管理员权限
        if (
            !in_array($thread->forum_id, json_decode($user->AdminPermissions->forums))
        ) {
            return response()->json(
                [
                    'code' => ResponseCode::ADMIN_UNAUTHORIZED,
                    'message' => ResponseCode::$codeMap[ResponseCode::ADMIN_UNAUTHORIZED],
                ],
            );
        }


        $thread->sub_id = 10;
        $thread->save();

        ProcessUserActive::dispatch(
            [
                'binggan' => $user->binggan,
                'user_id' => $user->id,
                'active' => '管理员置顶了主题',
                'thread_id' => $thread->id,
            ]
        );

        return response()->json([
            'code' => ResponseCode::SUCCESS,
            'message' => '该主题已经置顶',
            'data' => [
                'thread_id' => $thread->id,
            ],
        ]);
    }

    public function thread_cancel_top(Request $request)
    {
        $request->validate([
            'thread_id' => 'required|integer',
        ]);

        $user = $request->user();
        if (!$user->tokenCan('admin')) {
            return response()->json([
                'code' => ResponseCode::ADMIN_UNAUTHORIZED,
                'message' => ResponseCode::$codeMap[ResponseCode::ADMIN_UNAUTHORIZED],
            ]);
        }

        $thread = Thread::find($request->thread_id);
        if (!$thread) {
            return response()->json([
                'code' => ResponseCode::THREAD_NOT_FOUND,
                'message' => ResponseCode::$codeMap[ResponseCode::THREAD_NOT_FOUND],
            ]);
        }

        //确认是否拥有该版面的管理员权限
        if (
            !in_array($thread->forum_id, json_decode($user->AdminPermissions->forums))
        ) {
            return response()->json(
                [
                    'code' => ResponseCode::ADMIN_UNAUTHORIZED,
                    'message' => ResponseCode::$codeMap[ResponseCode::ADMIN_UNAUTHORIZED],
                ],
            );
        }

        $thread->sub_id = 0;
        $thread->save();
        ProcessUserActive::dispatch(
            [
                'binggan' => $user->binggan,
                'user_id' => $user->id,
                'active' => '管理员取消了置顶主题',
                'thread_id' => $thread->id,
            ]
        );
        return response()->json([
            'code' => ResponseCode::SUCCESS,
            'message' => '该主题已经取消置顶',
            'data' => [
                'thread_id' => $thread->id,
            ],
        ]);
    }

    public function post_delete(Request $request,$post_id)
    {
        $request->validate([
            'thread_id' => 'required|integer',
            'content' => 'required|string|max:255'
        ]);

        $user = $request->user();
        if (!$user->tokenCan('admin')) {
            return response()->json([
                'code' => ResponseCode::ADMIN_UNAUTHORIZED,
                'message' => ResponseCode::$codeMap[ResponseCode::ADMIN_UNAUTHORIZED],
            ]);
        }

        $post = Post::suffix(intval($request->thread_id / 10000))->find($post_id);
        if (!$post) {
            return response()->json([
                'code' => ResponseCode::POST_NOT_FOUND,
                'message' => ResponseCode::$codeMap[ResponseCode::POST_NOT_FOUND],
            ]);
        }

        //确认是否拥有该版面的管理员权限
        if (
            !in_array($post->forum_id, json_decode($user->AdminPermissions->forums))
        ) {
            return response()->json(
                [
                    'code' => ResponseCode::ADMIN_UNAUTHORIZED,
                    'message' => ResponseCode::$codeMap[ResponseCode::ADMIN_UNAUTHORIZED],
                ],
            );
        }

        $post->is_deleted = 2;
        $post->save();

        ProcessUserActive::dispatch(
            [
                'binggan' => $user->binggan,
                'user_id' => $user->id,
                'active' => '管理员删除了帖子',
                'thread_id' => $request->thread_id,
                'post_id' => $post->id,
                'content' => $request->content,
            ]
        );

        return response()->json([
            'code' => ResponseCode::SUCCESS,
            'message' => '该帖子已删除。',
            'data' => [
                'post_id' => $post->id,
            ],
        ]);
    }

    public function post_recover(Request $request,$post_id)
    {
        $request->validate([
            'thread_id' => 'required|integer',
            'content' => 'required|string|max:255'
        ]);

        $user = $request->user();
        if (!$user->tokenCan('admin')) {
            return response()->json([
                'code' => ResponseCode::ADMIN_UNAUTHORIZED,
                'message' => ResponseCode::$codeMap[ResponseCode::ADMIN_UNAUTHORIZED],
            ]);
        }

        $post = Post::suffix(intval($request->thread_id / 10000))->find($post_id);
        if (!$post) {
            return response()->json([
                'code' => ResponseCode::POST_NOT_FOUND,
                'message' => ResponseCode::$codeMap[ResponseCode::POST_NOT_FOUND],
            ]);
        }

        //确认是否拥有该版面的管理员权限
        if (
            !in_array($post->forum_id, json_decode($user->AdminPermissions->forums))
        ) {
            return response()->json(
                [
                    'code' => ResponseCode::ADMIN_UNAUTHORIZED,
                    'message' => ResponseCode::$codeMap[ResponseCode::ADMIN_UNAUTHORIZED],
                ],
            );
        }

        $post->is_deleted = 0;
        $post->save();

        ProcessUserActive::dispatch(
            [
                'binggan' => $user->binggan,
                'user_id' => $user->id,
                'active' => '管理员恢复了已删除的帖子',
                'thread_id' => $request->thread_id,
                'post_id' => $post->id,
                'content' => $request->content,
            ]
        );

        return response()->json([
            'code' => ResponseCode::SUCCESS,
            'message' => '该帖子已回复。',
            'data' => [
                'post_id' => $post->id,
            ],
        ]);
    }


    public function post_delete_all(Request $request)
    {
        $request->validate([
            'post_id' => 'required|Integer',
            'thread_id' => 'required|integer',
            'content' => 'required|string|max:255'
        ]);

        $user = $request->user();
        if (!$user->tokenCan('admin')) {
            return response()->json([
                'code' => ResponseCode::ADMIN_UNAUTHORIZED,
                'message' => ResponseCode::$codeMap[ResponseCode::ADMIN_UNAUTHORIZED],
            ]);
        }

        $post = Post::suffix(intval($request->thread_id / 10000))->find($request->post_id);
        if (!$post) {
            return response()->json([
                'code' => ResponseCode::POST_NOT_FOUND,
                'message' => ResponseCode::$codeMap[ResponseCode::POST_NOT_FOUND],
            ]);
        }

        //确认是否拥有该版面的管理员权限
        if (
            !in_array($post->forum_id, json_decode($user->AdminPermissions->forums))
        ) {
            return response()->json(
                [
                    'code' => ResponseCode::ADMIN_UNAUTHORIZED,
                    'message' => ResponseCode::$codeMap[ResponseCode::ADMIN_UNAUTHORIZED],
                ],
            );
        }

        $user_to_delete_all = User::where('binggan', $post->created_binggan)->first();
        $posts_to_delete = Post::suffix(intval($request->thread_id / 10000))->where('thread_id', $request->thread_id)->where('created_binggan', $user_to_delete_all->binggan)->get();

        foreach ($posts_to_delete as $post_to_delete) {
            $post_to_delete->is_deleted = 2;
            $post_to_delete->save();
        }

        //清除redis的posts缓存
        // $thread = $post->thread;
        // for ($i = 1; $i <= ceil($thread->posts_num / 200); $i++) {
        //     Cache::forget('threads_cache_' . $thread->id . '_' . $i);
        // }
        ProcessUserActive::dispatch(
            [
                'binggan' => $user->binggan,
                'user_id' => $user->id,
                'active' => '管理员删除该用户全部的回帖',
                'thread_id' => $request->thread_id,
                'binggan_target' => $user_to_delete_all->binggan,
                'content' => $request->content,
            ]
        );

        return response()->json([
            'code' => ResponseCode::SUCCESS,
            'message' => '该作者全部帖子已删除。',
            'data' => [
                'posts_to_delete' => $posts_to_delete,
            ],
        ]);
    }

    public function user_ban(Request $request)
    {
        $request->validate([
            'post_id' => 'required|Integer',
            'thread_id' => 'required|integer',
            'content' => 'required|string|max:255'
        ]);

        $user = $request->user();
        if (!$user->tokenCan('admin')) {
            return response()->json([
                'code' => ResponseCode::ADMIN_UNAUTHORIZED,
                'message' => ResponseCode::$codeMap[ResponseCode::ADMIN_UNAUTHORIZED],
            ]);
        }

        $post = Post::suffix(intval($request->thread_id / 10000))->find($request->post_id);
        if (!$post) {
            return response()->json([
                'code' => ResponseCode::POST_NOT_FOUND,
                'message' => ResponseCode::$codeMap[ResponseCode::POST_NOT_FOUND],
            ]);
        }

        //确认是否拥有该版面的管理员权限
        if (
            !in_array($post->forum_id, json_decode($user->AdminPermissions->forums))
        ) {
            return response()->json(
                [
                    'code' => ResponseCode::ADMIN_UNAUTHORIZED,
                    'message' => ResponseCode::$codeMap[ResponseCode::ADMIN_UNAUTHORIZED],
                ],
            );
        }

        $user_to_ban = User::where('binggan', $post->created_binggan)->first();
        if (!$user_to_ban) {
            return response()->json([
                'code' => ResponseCode::USER_NOT_FOUND,
                'message' => ResponseCode::$codeMap[ResponseCode::USER_NOT_FOUND],
            ]);
        }

        $user_to_ban->is_banned = true;
        $user_to_ban->save();
        ProcessUserActive::dispatch(
            [
                'binggan' => $user->binggan,
                'user_id' => $user->id,
                'active' => '管理员碎了饼干',
                'binggan_target' => $user_to_ban->binggan,
                'content' => $request->content,
            ]
        );
        return response()->json([
            'code' => ResponseCode::SUCCESS,
            'message' => '已碎饼干。阿弥陀佛，善哉善哉。',
        ]);
    }

    public function user_lock(Request $request)
    {
        $request->validate([
            'post_id' => 'required|Integer',
            'thread_id' => 'required|integer',
            'content' => 'required|string|max:255'
        ]);

        $user = $request->user();
        if (!$user->tokenCan('admin')) {
            return response()->json([
                'code' => ResponseCode::ADMIN_UNAUTHORIZED,
                'message' => ResponseCode::$codeMap[ResponseCode::ADMIN_UNAUTHORIZED],
            ]);
        }

        $post = Post::suffix(intval($request->thread_id / 10000))->find($request->post_id);
        if (!$post) {
            return response()->json([
                'code' => ResponseCode::POST_NOT_FOUND,
                'message' => ResponseCode::$codeMap[ResponseCode::POST_NOT_FOUND],
            ]);
        }

        //确认是否拥有该版面的管理员权限
        if (
            !in_array($post->forum_id, json_decode($user->AdminPermissions->forums))
        ) {
            return response()->json(
                [
                    'code' => ResponseCode::ADMIN_UNAUTHORIZED,
                    'message' => ResponseCode::$codeMap[ResponseCode::ADMIN_UNAUTHORIZED],
                ],
            );
        }

        $user_to_lock = User::where('binggan', $post->created_binggan)->first();
        if (!$user_to_lock) {
            return response()->json([
                'code' => ResponseCode::USER_NOT_FOUND,
                'message' => ResponseCode::$codeMap[ResponseCode::USER_NOT_FOUND],
            ]);
        }

        $user_to_lock->locked_until = Carbon::now()->addDays(3);
        $user_to_lock->save();
        ProcessUserActive::dispatch(
            [
                'binggan' => $user->binggan,
                'user_id' => $user->id,
                'active' => '管理员封禁了饼干',
                'binggan_target' => $user_to_lock->binggan,
                'content' => $request->content,
            ]
        );
        return response()->json([
            'code' => ResponseCode::SUCCESS,
            'message' => '该饼干已封禁3天。',
        ]);
    }
}
