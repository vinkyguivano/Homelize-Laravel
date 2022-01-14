<?php

namespace App\Http\Controllers;

use App\Models\Professional;
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
            "password"=>'required|string'
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password'])
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
            'provider' => 'local',
            'type' => 'user'
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

        if(!$user){
            return response([
                'message' => "User not found"
            ],401);
        }

        // Check Password
        if(!Hash::check($fields['password'], $user->password))
        {
            return response([
                'message' => "Bad credentials"
            ],401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user'=>$user,
            'token'=>$token,
            'provider'=>'local',
            'type' => 'user'
        ];

        return response($response,201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

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

        return response(['user' => $user, 'token' => $token, 'provider' => $provider, 'type' => 'user'], 200);
    }

    public function registerProfessional(Request $request){
        $res = $request->validate([
            "name" => 'required|string|min:3',
            "phone_number" => 'required',
            "professional_type_id"=>'required|integer',
            "email" => 'required|string|unique:professionals,email',
            "password" => 'required|string'
        ]);

        $res['password'] = bcrypt($res['password']);
        $user = Professional::create($res);
        
        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'type_id' => $user->professional_type_id,
                'status_id' => 1
            ],
            'token' => $token,
            'provider' => 'local',
            'type' => 'professional'
        ];

        return response($response, 201);
    }

    public function loginProfessional(Request $request){
        $fields = $request->validate([
            "email" => 'required|string',
            "password" => 'required|string',
        ]);

        $where = ["email"=>$fields['email']];
        $user = Professional::where($where)->first();

        if(!$user || !Hash::check($fields['password'], $user->password))
        {
            return response([
                'message' => "The provided credentials are incorrect"
            ],401);
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'type_id' => $user->professional_type_id,
                'status_id' => $user->status_id
            ],
            'token' => $token,
            'provider' => 'local',
            'type' => 'professional'
        ];

        return response($response,201);

    }
}
