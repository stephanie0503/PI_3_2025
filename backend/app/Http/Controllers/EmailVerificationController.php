<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function verify(Request $request)
    {
        $request->validate(['token' => 'required|string']);

        $user = User::where('verification_token', $request->token)->first();

        if ($user && $user->status == "pending") {
            $user->verification_token = null;
            $user->status  = "active";
            $user->save();

            return response()->json(['message' => 'E-mail verificado com sucesso!']);
        }

        return response()->json(['message' => 'Token inválido ou já utilizado.'], 400);
    }
}
