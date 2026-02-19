<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class WelcomeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1",
     *     summary="Welcome message",
     *     description="Returns a welcome message for the API",
     *     tags={"Welcome"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Welcome to Booking API")
     *         )
     *     )
     * )
     */
    public function __invoke()
    {
        return response()->json([
            'message' => 'Welcome to Booking API'
        ]);
    }
}
