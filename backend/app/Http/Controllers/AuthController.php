<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // Inscription
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|username|unique:users,username',
            'password' => 'required|string|min:8|regex:/[@$!%*#?&]/',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = auth()->login($user);
        info('Utilisateur inscrit avec succès: ' . $user->username);
        return response()->json(['access_token' => $token, 'user' => $user]);
        
    }

    // Connexion
    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        // Validation des données d'entrée
        $request->validate([
            'username' => 'required|username',
            'password' => 'required|string|min:6',
        ]);

        // Vérifier l'utilisateur dans la base de données
        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Les identifiants sont incorrects.'],
            ]);
        }

        // Créez le token JWT
        $token = JWTAuth::fromUser($user);

        // Retourne le token
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    // Déconnexion
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Déconnexion réussie']);
    }
}

