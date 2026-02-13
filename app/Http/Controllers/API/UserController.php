<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

#[OA\Info(
    title: "API-Laravel",
    version: "1.0.0"
)]

class UserController extends Controller
{
    #[OA\Post(
        path: "/api/register",
        summary: "Inscrire un utilisateur",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name","email","password"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Thomas"),
                    new OA\Property(property: "email", type: "string", format: "email", example: "thomas@example.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "motdepasse123")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: "Utilisateur créé"),
            new OA\Response(response: 422, description: "Erreur de validation")
        ]
    )]
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }

    #[OA\Post(
        path: "/api/login",
        summary: "Connexion d'un utilisateur",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email","password"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "thomas@thomas.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "motdepasse123")
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Connexion réussie"),
            new OA\Response(response: 401, description: "Identifiants invalides")
        ]
    )]
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    #[OA\Post(
        path: "/api/logout",
        summary: "Déconnexion",
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(response: 204, description: "Utilisateur déconnecté")
        ]
    )]
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }
}
