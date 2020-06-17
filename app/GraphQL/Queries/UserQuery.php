<?php

namespace App\GraphQL\Queries;

use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class UserQuery
{
    public function me()
    {
        return Auth::guard('api')->user();
    }

    public function user($root ,array $args)

    {
        $credentials = Arr::only($args, ['name']);

        return User::where('name', $credentials)->get();
    }
}
