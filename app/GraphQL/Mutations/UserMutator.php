<?php

namespace App\GraphQL\Mutations;

use App\User;
use Illuminate\Support\Str;

class UserMutator
{
    public function create($root, array $args)

    {
        $token = Str::random(60);
        $user = new User($args);
        $user->api_token = $token;
        $user->save();

        return [
            'user'=> $user,
            'token'=>$token,
        ];
    }
}
