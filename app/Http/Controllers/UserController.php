<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    function login(Request $request){

        $validator = Validator($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(),422);
        }

        $validate = $validator->validated();


        if (Auth::attempt($validate)) {

            //isi dari token
            $payload = [
                'name' => 'Administrator',
                'role' => 'admin',
                'iat' => Carbon::now()->timestamp,
                'exp' => Carbon::now()->timestamp + 60*60*2,

            ];

            $token = JWT::encode($payload,env('JWT_SECRET_KEY'),'HS256');

            return response()->json([
                'msg' => 'token berhasil dibuat',
                'data' => 'Bearer ' .$token
            ],200);
        } else{

            return response()->json([
                'msg' => 'Email atau password salah'
            ],422);
        }
    }
}
