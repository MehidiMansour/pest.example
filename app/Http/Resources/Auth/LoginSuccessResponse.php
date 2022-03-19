<?php

namespace App\Http\Resources\Auth;

use App\Models\User;
use Illuminate\Contracts\Support\Responsable;

class LoginSuccessResponse implements Responsable
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function toResponse($request)
    {
        // Revoke all previous tokens...
        $this->user->tokens()->where('name', 'Authentication')->delete();

        // Create new token
        $token = $this->user->createToken('Authentication');

        // Get user timezone, fallback to app timezone
        $timezone = config('app.timezone');
        $expiresIn = (int) config('sanctum.expiration'); // sanctum expiration is in minutes

        // Return response & attach cookies
        return response([
            'token_type' => 'Bearer',
            'access_token' => $token->plainTextToken,
            'expires_in' => $expiresIn * 60, // converts to seconds
        ])->cookie(
            'access_token',
            $token->plainTextToken,
            $expiresIn
        )->cookie(
            'timezone',
            $timezone,
            $expiresIn
        );
    }
}
