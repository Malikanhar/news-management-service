<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
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
            return (new UserResource('Wrong username or password', null, null))
                ->response()
                ->setStatusCode(Response::HTTP_UNAUTHORIZED);
        }

        $scope = ['post-comment'];
        if (auth()->user()->is_admin) {
            $scope = ['crud-news'];
        }

        $token = auth()->user()->createToken('Access Token', $scope)->accessToken;

        return (new UserResource('Success', auth()->user(), $token))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return (new UserResource('Success', null, null))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
