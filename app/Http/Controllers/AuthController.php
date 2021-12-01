<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            "name" => 'required|string',
            "email" => 'required|string|unique:users,email',
            "password"=>'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password'])
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            "email" => 'required|string',
            "password" => 'required|string',
        ]);

        // Check Email
        $where = ["email"=>$fields['email']];
        $user = User::where($where)->first();

        // Check Password
        if(!$user || !Hash::check($fields['password'], $user->password))
        {
            return response([
                'message' => "Bad credentials"
            ],401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user'=>$user,
            'token'=>$token
        ];

        return response($response,201);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        $response = [
            'message' => 'logged out'
        ];

        return response($response,201);
    }

    public function SocialLogin($provider, Request $request){
        $user = User::firstOrCreate(
            ['email' => $request->get('email')],
            ['name' => $request->get('name'),]
        );

        $user->providers()->updateOrCreate(
            ['provider' => $provider,
             'provider_id' => $request->get('id')
            ],
            [
             'avatar' => $request->get('avatar')
            ]
        );

        $token = $user->createToken('app_token')->plainTextToken;

        return response(['user' => $user, 'token' => $token], 200);
    }
}
