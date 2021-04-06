<?php

namespace App\Http\Controllers\api;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Controllers\api\ApiResponseController;

class AuthController extends ApiResponseController
{


    public function register(RegisterUserRequest $request)
    {

        $request['password'] = bcrypt($request->password);

        $user = User::create($request->all());

        $user->profile()->create(['']);
        return $this->successResponse('', 201, 'Created Succesfully');
    }

    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if (!Auth::attempt($credentials)) {
            return $this->errorResponse($data = array('errors' => 'invalid Credentials'), $code = 403, 'invalid Credentials');
        }
        $user = $request->user();
        $token = $user->createToken('authToken');
        return $this->successResponse(['user' => $user, 'access_token' => $token->accessToken, 'token_type' => 'Bearer ', 'expires_at' => Carbon::parse($token->token->expires_at)->toDateTimeString()]);
    }

    public function user(Request $request)
    {
        $user = Auth::user();
        $user->profile;

        return $this->successResponse(['user' => $user]);
    }
}
