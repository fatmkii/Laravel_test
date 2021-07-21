<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Common\ResponseCode;

class EmojiController extends Controller
{
    public function index()
    {
        return response()->json([
            'code' => ResponseCode::SUCCESS,
            'message' => ResponseCode::$codeMap[ResponseCode::SUCCESS],
            'data' => DB::table('emoji')->get(),
            // 'data' => json_decode(DB::table('emoji')->value('emojis')),
        ]);
    }
}
