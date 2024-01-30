<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['string', 'max:50', 'required'],
            'email' => ['email', 'required'],
            'password' => ['required', 'min:6']
        ]);

        $user = User::query()->create($data);

        return response()->json([
            'data' => $user,
            'token' => $user->createToken('api_token')->plainTextToken,
            'token_type' => 'Bearer'
        ]);
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            return response()->json([
                'token' => $user->createToken('api_token')->plainTextToken,
                'token_type' => 'Bearer'
            ]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }
}