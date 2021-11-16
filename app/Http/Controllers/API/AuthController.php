<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\UserRegistrationRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends BaseController
{

    /**
     * Create new user
     * @param UserRegistrationRequest $request
     * @return JsonResponse
     */
    public function register(UserRegistrationRequest $request): JsonResponse
    {
        try {
            $user = User::create($request->safe()->only(['email', 'name', 'password']));
            $accessToken = $user->createToken(env("TOKEN_AUTH_KEY"))->plainTextToken;
            return $this->handleResponse(['user' => $user, 'access_token' => $accessToken], "User created successfully.");
        } catch (Exception $exception) {
            Log::error("Error on creating new user.", ["error" => $exception->getMessage(), "data" => $request->all()]);
            return $this->handleError("Error on creating new user", [$exception->getMessage()]);
        }
    }

    /**
     * Login user
     * @param AuthRequest $request
     * @return JsonResponse
     */
    public function login(AuthRequest $request): JsonResponse
    {
        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $auth = Auth::user();
                return $this->handleResponse([
                    'name' => $auth->name,
                    'token' => $auth->createToken(env("TOKEN_AUTH_KEY"))->plainTextToken,
                ], 'User logged-in!');
            } else {
                return $this->handleError('Unauthorised.', ['error' => 'Unauthorised']);
            }
        } catch (Exception $exception) {
            Log::error("Error on login user.", ["error" => $exception->getMessage(), "data" => $request->all()]);
            return $this->handleError("Error on login user", [$exception->getMessage()]);
        }
    }

    /**
     * Logout user
     * @return JsonResponse
     *
     */
    public function logout()
    {
        try {
            auth()->user()->tokens()->logout();
            return $this->handleResponse([], "Logged out successfuly");
        } catch (Exception $exception) {
            Log::error("Error on logout user.", ["error" => $exception->getMessage(), "user" => \auth()->user()]);
            return $this->handleError("Error on logout user", [$exception->getMessage()]);
        }
    }
}
