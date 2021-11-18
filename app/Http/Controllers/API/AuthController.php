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
     * @OA\Post(
     * path="/api/register",
     * operationId="Register",
     * tags={"Register"},
     * summary="User Register",
     * description="User Register here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\Schema(
     *               type="object",
     *               required={"name","email", "password"},
     *               @OA\Property(property="name", type="text"),
     *               @OA\Property(property="email", type="text"),
     *               @OA\Property(property="password", type="password"),
     *            )
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
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
     * @OA\Post(
     * path="/api/login",
     * operationId="authLogin",
     * tags={"Login"},
     * summary="User Login",
     * description="Login User Here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"email", "password"},
     *               @OA\Property(property="email", type="email"),
     *               @OA\Property(property="password", type="password")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Login Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Login Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
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
     * @OA\Post (
     *     path="api/logout",
     *     operationId="authLogout",
     *     tags={"Logout"},
     *     summary="User logout",
     *     security={ {"Bearer": {} }},
     *     description="Logout current user",
     *          @OA\RequestBody(
     *              @OA\JsonContent(),
     *          ),
     *          @OA\Response(
     *              response=200,
     *              description="Logout successfully",
     *              @OA\JsonContent()
     *          ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     *     )
     */
    public function logout()
    {
        try {
            auth()->user()->tokens()->logout();
            return $this->handleResponse([], "Logged out successfully");
        } catch (Exception $exception) {
            Log::error("Error on logout user.", ["error" => $exception->getMessage(), "user" => \auth()->user()]);
            return $this->handleError("Error on logout user", [$exception->getMessage()]);
        }
    }
}
