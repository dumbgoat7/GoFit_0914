<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function loginPegawai(Request $request)
    {
        $loginData = $request->all();

        $validate = Validator::make($loginData,[
            'username' => 'required',
            'password' => 'required'
        ]);

        if(is_null($request->username) || is_null($request->password)){
            return response(['message' => 'Inputan tidak boleh kosong'], 400);
        }

        if (!Auth::guard('pegawai')->attempt($loginData)) {
            return response(['message'=> 'This username and password is not a employee'],401);
        }
        $user = Auth::guard('pegawai')->user();
        $token = $user->createToken('Authentication Token')->accessToken;

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]);

    }

    public function loginMember(Request $request)
    {
        $loginData = $request->all();

        $validate = Validator::make($loginData,[
            'username' => 'required',
            'password' => 'required'
        ]);

        if(is_null($request->username) || is_null($request->password)){
            return response(['message' => 'Input data cannot be empty'], 400);
        }

        if (!Auth::guard('member')->attempt($loginData)) {
            return response(['message'=> 'This username and password is not a member'],401);
        }
        $member = DB::table('member')
            ->select('member.*')
            ->where('member.username', $request->username)
            ->where('member.password', $request->password)
            ->where('member.status','=', 0)
            ->first();
               
        $user = Auth::guard('member')->user();
        if($user->status == 0){
            return response(['message'=> 'This member is not active'],401);
        }
        $token = $user->createToken('Authentication Token')->accessToken;

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]);

    }
    public function loginInstruktur(Request $request)
    {
        $loginData = $request->all();

        $validate = Validator::make($loginData,[
            'username' => 'required',
            'password' => 'required'
        ]);

        if(is_null($request->username) || is_null($request->password)){
            return response(['message' => 'Input data cannot be empty'], 400);
        }

        if (!Auth::guard('instruktur')->attempt($loginData)) {
            return response(['message'=> 'This username and password is not an instructur'],401);
        }

        $user = Auth::guard('instruktur')->user();
        $token = $user->createToken('Authentication Token')->accessToken;

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ]);

    }
    public function loginMO(Request $request){
        $loginData = $request->all();

        $validate = Validator::make($loginData,[
            'username' => 'required',
            'password' => 'required'
        ]);

        if(is_null($request->username) || is_null($request->password)){
            return response(['message' => 'Input data cannot be empty'], 400);
        }

        if (!Auth::guard('pegawai')->attempt($loginData)) {
            return response(['message'=> 'This username and password is not an employee'],401);
        }
        $user = Auth::guard('pegawai')->user();
        
        if($user->role == 'Manager Operasional'){
            
            $token = $user->createToken('Authentication Token')->accessToken;
    
            return response([
                'message' => 'Authenticated',
                'user' => $user,
                'token_type' => 'Bearer',
                'access_token' => $token
            ]);
        } else {
            return response(['message'=> 'This username and password is not a Manager Operational'],401);
        }
        
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response([
            'message' => 'Logged out'
        ], 200);
    }
}
