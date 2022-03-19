<?php

namespace App\Http\Controllers\API\V1\Auth;

use Hash;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Auth\LoginFailedResponse;
use App\Http\Resources\Auth\LoginSuccessResponse;

class LoginController extends Controller
{

    /**
     * Handle a login request to the application.
     *
     * @param Request $request
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
        /** @var User $user */
        $user = User::where($this->username(), $request->get($this->username()))->first();
        if(!$user || !Hash::check($request->get('password'), $user->password)) {
            return new LoginFailedResponse($user);
        }
        return new LoginSuccessResponse($user);
    }
    /**
     * Get the login username to be used by the controller.
     */
    public function username(): string
    {
        return 'email';
    }
}