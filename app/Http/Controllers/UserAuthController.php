<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserAuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($data)) {
            return response(['message' => 'Wrong username or password']);
        }

        $scope = ['post-comment'];
        if (auth()->user()->is_admin) {
            $scope = ['crud-news'];
        }

        $token = auth()->user()->createToken('Access Token', $scope)->accessToken;

        return response(['message' => 'Successfully logged in', 'user' => auth()->user(), 'token' => $token]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response(['message' => 'Successfully logged out']);
    }
}
