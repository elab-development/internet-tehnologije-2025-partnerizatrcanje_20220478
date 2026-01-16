<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends OdgovorController
{
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

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();

        return $this->uspesno([], "Uspesno odjavljeni");
    }
}
