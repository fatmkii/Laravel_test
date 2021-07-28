<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Common\ResponseCode;


class CommonController extends Controller
{
    public function emoji_index()
    {
        return response()->json([
            'code' => ResponseCode::SUCCESS,
            'message' => ResponseCode::$codeMap[ResponseCode::SUCCESS],
            'data' => DB::table('emoji')->get(),
        ]);
    }

    public function subtitles_index()
    {
        return response()->json([
            'code' => ResponseCode::SUCCESS, 
            'message' => ResponseCode::$codeMap[ResponseCode::SUCCESS],
            'data' => DB::table('subtitles')->get(),
        ]);
    }

}
