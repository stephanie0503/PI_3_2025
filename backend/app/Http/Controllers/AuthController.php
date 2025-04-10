<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{


    public function __construct
    (
        protected AuthService $authService
    )
    {}

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $token = $this->authService->login($credentials);

        return response()->json(['token' => $token]);
    }

    public function me(Request $request)
    {
     
        return response()->json($request->user());
    }
}
