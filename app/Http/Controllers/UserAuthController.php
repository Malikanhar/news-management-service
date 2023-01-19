<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($data)) {
            $response = [
                'message' => 'Wrong username or password'
            ];
            return response($response, Response::HTTP_UNAUTHORIZED);
        }

        $scope = ['post-comment'];
        if (auth()->user()->is_admin) {
            $scope = ['crud-news'];
        }

        $token = auth()->user()->createToken('Access Token', $scope)->accessToken;

        $response = [
            'message' => 'Success',
            'user' => auth()->user(),
            'token' => $token
        ];
        return response($response, Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        $response = [
            'message' => 'Success'
        ];
        return response($response, Response::HTTP_OK);
    }
}
