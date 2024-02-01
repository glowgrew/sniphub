<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\v1\User\AuthUserResource;
use App\Http\Resources\api\v1\User\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function show(User $user)
    {
        if (auth('sanctum')->check()) {
            if ($user->id === auth('sanctum')->user()->id) {
                return new AuthUserResource($user);
            }
        }
        return new UserResource($user);
    }
}
