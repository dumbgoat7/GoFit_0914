<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
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

        if ($validate->fails()) {
            return response(['message' => $validate->error()],400);
        }

        if (!Auth::guard('pegawai')->attempt($loginData)) {
            return response(['message'=> 'Invalid Credential'],401);
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

        if ($validate->fails()) {
            return response(['message' => $validate->error()],400);
        }

        if (!Auth::guard('member')->attempt($loginData)) {
            return response(['message'=> 'Invalid Credential'],401);
        }
        $user = Auth::guard('member')->user();
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

        if ($validate->fails()) {
            return response(['message' => $validate->error()],400);
        }

        if (!Auth::guard('instruktur')->attempt($loginData)) {
            return response(['message'=> 'Invalid Credential'],401);
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
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response([
            'message' => 'Logged out'
        ], 200);
    }
}
