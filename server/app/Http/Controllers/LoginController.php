<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class LoginController extends OdgovorController
{
    #[OA\Post(
        path: "/api/login",
        description: "Prijava korisnika sa email i lozinkom",
        summary: "Prijava korisnika",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email", "password"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "korisnik@email.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "lozinka123")
                ]
            )
        ),
        tags: ["Autentifikacija"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Prijava uspesna",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "token", type: "string"),
                        new OA\Property(property: "user", ref: "#/components/schemas/User")
                    ]
                )
            ),
            new OA\Response(response: 422, description: "Validaciona greska"),
            new OA\Response(response: 401, description: "Neispravni kredencijali")
        ]
    )]
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->neuspesno($validator->errors(), "Validaciona greska", 422);
        }

        $credentials = $request->only('email', 'password');

        if (!auth()->attempt($credentials)) {
            return $this->neuspesno(['error' => 'Neispravni kredencijali'], "Prijava neuspesna", 401);
        }

        $user = auth()->user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->uspesno([
            'token' => $token,
            'user' => new \App\Http\Resources\UserResource($user),
        ], "Prijava uspesna");
    }

    #[OA\Post(
        path: "/api/register",
        description: "Registracija novog korisnika",
        summary: "Registracija korisnika",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email", "password", "name"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "novi@email.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "lozinka123"),
                    new OA\Property(property: "name", type: "string", example: "Ime Prezime")
                ]
            )
        ),
        tags: ["Autentifikacija"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Registracija uspesna",
                content: new OA\JsonContent(ref: "#/components/schemas/User")
            ),
            new OA\Response(response: 422, description: "Validaciona greska")
        ]
    )]
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
            'name' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->neuspesno($validator->errors(), "Validaciona greska", 422);
        }

        $user = \App\Models\User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'name' => $request->name,
            'tipKorisnika' => 'trkac',
            'rank' => User::RANK_BEGGINNER
        ]);

        return $this->uspesno(new \App\Http\Resources\UserResource($user), "Registracija uspesna, sada se mozete prijaviti!!!");
    }

    #[OA\Post(
        path: "/api/logout",
        description: "Odjava korisnika i brisanje tokena",
        summary: "Odjava korisnika",
        security: [["sanctum" => []]],
        tags: ["Autentifikacija"],
        responses: [
            new OA\Response(response: 200, description: "Uspesno odjavljeni"),
            new OA\Response(response: 401, description: "Neautorizovan pristup")
        ]
    )]
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return $this->uspesno([], "Uspesno odjavljeni");
    }
}
