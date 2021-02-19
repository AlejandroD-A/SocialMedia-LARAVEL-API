<?php

namespace App\Http\Controllers\api;


use App\Models\User;
use App\Http\Controllers\api\ApiResponseController;

class FollowController extends ApiResponseController
{
    public function store(User $user)
    {
        $userLogged = request()->user();

        if ($userLogged->id === $user->id) {
            return $this->errorResponse('same user', 400);
        }

        $toggle = request()->user()->following()->toggle($user->id);
        return $this->successResponse($toggle, 201);
    }

    public function getFollowing(User $user)
    {
        return $this->successResponse($user->following);
    }

    public function getFollowers(User $user)
    {

        return $this->successResponse($user->followers);
    }
}
