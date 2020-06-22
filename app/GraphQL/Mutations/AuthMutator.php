<?php

namespace App\GraphQL\Mutations;

use App\Http\Controllers\Controller;
use App\User;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use RuntimeException;
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
                throw new RuntimeException('email or password error');
            }

        } catch(JWTException $e) {
            return [
                'result' => '帳號密碼錯誤',
                'token' => '',
                'code' => '40100'
            ];
        }

        $this->JWTAuth->setToken($token)->toUser();
        $user = auth()->user();

        $user->api_token = $token;
        $user->save();

        $all =[
            'result'=> $user,
            'token' => $token,
            'code'  => '200001'
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
