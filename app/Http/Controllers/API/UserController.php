<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Common\ResponseCode;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = new User;
            do {
                $binggan = Str::random(9);
            } while (!empty(User::where('binggan', $binggan)->first));
            $user->binggan = $binggan;
            $user->created_ip = $request->ip();
            $user->coin = 100;
            $user->save();
            DB::commit();
        } catch (QueryException $e) {
            DB::rollback();
            return response()->json([
                'code' => ResponseCode::DATABASE_FAILED,
                'message' => ResponseCode::$codeMap[ResponseCode::DATABASE_FAILED] . '，请重试',
            ]);
        }
        $token = $user->createToken($binggan)->plainTextToken;
        return response()->json(
            [
                'code' => ResponseCode::SUCCESS,
                'message' => '创建饼干成功！',
                'data' => [
                    'binggan' => $binggan,
                    'token' => $token,
                ],
            ],
            200
        );
    }




    public function get_user(Request $request)
    {

        $token_header = $request->header('Authorization');
        if (isset($token_header)) {
            $token_header = str_replace('bearer', '', $token_header);
            [$token_id, $user_token] = explode('|', $token_header, 2);
            $token_data = DB::table('personal_access_tokens')->where('token', hash('sha256', $user_token))->first();
            $user_id = $token_data->tokenable_id;
        }
        $user = User::where('id', $user_id)->first();
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
