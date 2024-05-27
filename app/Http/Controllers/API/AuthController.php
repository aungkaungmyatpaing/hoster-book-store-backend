<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;

    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(UserRegisterRequest $request)
    {
        $token = $this->authService->register($request->validated());
        return $this->success('Create account successful', [
            'token' => $token,
        ]);
    }

    public function login(UserLoginRequest $request)
    {
        $token = $this->authService->login($request->validated());
        return $this->success('Login successful', [
            'token' => $token,
        ]);
    }

    public function getUserProfile()
    {
        $user = $this->authService->getUserProfile();
        $data = [
            'user' => new UserResource($user),
        ];
        return $this->success('Get user profile successful', $data);
    }

    public function logout()
    {
        $this->authService->logout();
        return $this->success('Logout successful');
    }

    public function updateProfile(UpdateUserRequest $request)
    {
        $this->authService->updateProfile($request->validated());
        return $this->success('Profile update successful');
    }
}
