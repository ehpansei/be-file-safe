<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthenticationController extends Controller
{
    //
    public function login(Request $request)
    {
        Log::info($request);

        $user = User::where("email", $request->email)->first()->makeVisible(["password"]);

        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken($request->email);

            return response()->json([
                "token" => $token
            ]);
        }

        return response()->json(["error" => true, "message" => "Not valid credentials"]);
    }

    public function logout(Request $request)
    {
        // remove all tokens
        $user = $request->user()->tokens()->where('tokenable_id', auth()->id())->delete();

        return response()->json(["message" => "You have logged out"]);
    }

    public function renew(Request $request)
    {
        $user = $request->user();

        // create new token for user
        $token = $user->createToken($user->email);

        // return token
        return response()->json([
            "token" => $token
        ]);
    }
}
