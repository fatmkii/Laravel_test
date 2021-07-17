<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Common\ResponseCode;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'binggan' => 'required|string',
        ]);

        $binggan  = $request->get('binggan');
        $user2 = User::where('binggan', 'abc')->first();
        Auth::attempt(['email' => '47155837@qq.com', 'password' => '84228184']);
        $user = Auth::user();

        if (Auth::check()) {
            $token = $user2->createToken($binggan)->plainTextToken;
            // Auth::login($user);
            return response()->json([
                'code' => ResponseCode::SUCCESS,
                'message' => '登陆成功',
                'data' => [
                    'binggan' => Auth::user(),
                    'token' => $token,
                ],
            ]);
        } else {
            return response()->json([
                'code' => ResponseCode::USER_NOT_FOUND,
                'message' => '找不到此饼干',
                'data' => [
                    'binggan' => $binggan,
                ],
            ]);
        }
    }
}
