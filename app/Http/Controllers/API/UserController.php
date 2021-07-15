<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Common\ResponseCode;
use App\Models\User;

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

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'binggan' => 'required|string',
        ]);

        $binggan  = $request->get('binggan');
        $user_data = User::where('binggan', $binggan)->first();

        if ($user_data) {
            return response()->json([
                'code' => ResponseCode::SUCCESS,
                'message' => '登陆成功',
                'data' => [
                    'binggan' => $binggan,
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
