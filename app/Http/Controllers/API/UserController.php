<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Common\ResponseCode;
use Illuminate\Support\Facades\DB;
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

    public function get_user(Request $request)
    {

        $token_header = $request->header('Authorization');
        if (isset($token_header)) {
            $token_header = str_replace('bearer', '', $token_header);
            [$id, $user_token] = explode('|', $token_header, 2);
            $token_data = DB::table('personal_access_tokens')->where('token', hash('sha256', $user_token))->first();
            $user_id = $token_data->tokenable_id;
        }
        $user = User::where('id',$user_id)->first();
        $user->tokens()->delete();

        return response()->json([
            'code' => ResponseCode::SUCCESS,
            'message' => '登陆成功',
            'data' => [
                'binggan' => $user,
            ],
        ]);
    }
}
