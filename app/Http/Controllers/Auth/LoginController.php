<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     summary="User login",
     *     description="Logs in a user and returns an access token",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="test@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials"
     *     )
     * )
     */
    public function __invoke(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            auth()->user()->tokens()->delete();

            return response()->json([
                'access_token' => auth()->user()->createToken(
                    'client',
                    expiresAt: now()->addDays((config('sanctum.dates_to_expiration')))
                )->plainTextToken,
            ], 200);
        }

        return response()->json([
            'message' => 'invalid credentials'
        ], 401);
    }
}
