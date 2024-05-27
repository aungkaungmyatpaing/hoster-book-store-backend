<?php

namespace App\Services;

use App\Exceptions\RegistrationFailException;
use App\Exceptions\ResourceForbiddenException;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(array $requestArray): string
    {
        DB::beginTransaction();
        try {
            $user = User::create($requestArray);
            $token = $user->createToken('USER-AUTH-TOKEN')->plainTextToken;
            DB::commit();
            return $token;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new RegistrationFailException('Failed to register user');
        }
    }

    public function login(array $requestArray)
    {
        $user = User::where('phone', $requestArray['phone'])->first();

        if (is_null($user)) {
            throw new ResourceNotFoundException('User not found');
        }

        if (!Hash::check($requestArray['password'], $user->password)) {
            throw new ResourceForbiddenException('Phone or password is incorrect');
        }
        $user->tokens()->delete();

        $user->fcn_token = $requestArray['fcm_token'];

        $data = [
            'token' => $user->createToken('USER-AUTH-TOKEN')->plainTextToken,
            'user_id' => $user->id
        ];

        return $data;
    }

    public function getUserProfile()
    {
        return Auth::guard('user')->user();
    }

    public function logout()
    {
        /** @var \App\Models\User $user **/
        $user = Auth::guard('user')->user();
        $user->tokens()->delete();
    }

    public function updateProfile(array $requestArray)
    {
            /** @var \App\Models\User $user **/
            $user = Auth::guard('user')->user();
            if (isset($requestArray['image'])) {
                // Clear existing media to replace with new one
                $user->clearMediaCollection('user-profile');

                // Add the new image to the 'user-profile' media collection
                $user->addMedia($requestArray['image'])
                    ->toMediaCollection('user-profile');
            }
            if (isset($requestArray['phone'])) {
                $checkPhone = User::where('phone', $requestArray['phone'])->first();
                if ($checkPhone) {
                    if ($checkPhone->id == $user->id) {
                        $user->phone = $requestArray['phone'];
                    }else{
                        throw new ResourceForbiddenException('This phone is already used');
                    }
                }else{
                    $user->phone = $requestArray['phone'];
                }
            }
            if (isset($requestArray['password'])) {
                $user->password = bcrypt($requestArray['password']);
            }
            $user->update($requestArray);
            $user->save();

    }
}
