<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Common\ResponseCode;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
    public function show($id)
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

    public function get_user()
    {

        return response()->json([
            'code' => ResponseCode::SUCCESS,
            'message' => '登陆成功',
            'data' => [
                'binggan' => Auth::user(),
            ],
        ]);
    }

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
