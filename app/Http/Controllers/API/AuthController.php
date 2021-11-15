<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{

    /**
     * Create new user
     * @param UserRegistrationRequest $request
     * @return JsonResponse
     */
    public function register(UserRegistrationRequest $request)
    {
        $user = User::create($request->safe()->only(['email', 'name', 'password']));
        $accessToken = $user->createToken(env("TOKEN_AUTH_KEY"))->plainTextToken;
        return $this->handleResponse(['user' => $user, 'access_token' => $accessToken], "User created successfully.");
    }

    /**
     * Login user
     * @param AuthRequest $request
     * @return JsonResponse
     */
    public function login(AuthRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $auth = Auth::user();
            return $this->handleResponse([
                'name' => $auth->name,
                'token' => $auth->createToken(env("TOKEN_AUTH_KEY"))->plainTextToken,
            ], 'User logged-in!');
        } else {
            return $this->handleError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    /**
     * Logout user
     * @return JsonResponse
     *
     */
    public function logout()
    {
        auth()->user()->tokens()->logout();
        return $this->handleResponse([], "Logged out successfuly");
    }
}
