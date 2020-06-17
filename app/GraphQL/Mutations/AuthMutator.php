<?php

namespace App\GraphQL\Mutations;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;
class AuthMutator extends Controller
{
    protected  $JWTAuth;
    public function __construct(JWTAuth $JWTAuth){
        $this->JWTAuth = $JWTAuth;
    }
    public function login($root, array $args)
    {
        // Arr::only(數組， [查詢的object])  => ['email':XXX, 'password':XXX]
        $credentials = Arr::only($args, ['email', 'password']);
        try {
            if (!$token = $this->JWTAuth->attempt($credentials)) {
                return [
                    'error' => '帳號密碼錯誤',
                    'code' => '20001'
                ];
            }

        } catch(JWTException $e) {
            return [
                'error' => '帳號密碼錯誤',
                'code' => '20001'
            ];
        }

        $this->JWTAuth->setToken($token)->toUser();
        $user = auth()->user();

        $user->api_token = $token;
        $user->save();
        $data = [
            'token' => $token,
            'user' => $user
        ];
        $all =[
            'result'=> $data,
            'code' => '200001'
        ];

        return $all;

    }

    public function logout($root ,array $args)
    {
        try{
            Auth::logout();
        } catch (JWTException $e) {
            return 'error';
        }
        return [
            'msg'=>'Logout Success',
            'code'=>'20001'
        ] ;
    }
}
