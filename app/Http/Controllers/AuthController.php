<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $user)
    {
        $userAuth = User::where('email', $user->email)->first();

        if(!$userAuth) {
            return response()->json([
                'ok' => false,
                'message' => 'Usuario o clave invalida',
                'data' => null,
            ], 403);
        }

        if(!Hash::check( $user->password, $userAuth->password) ) {
            return response()->json([
                'ok' => false,
                'message' => 'Usuario o clave invalida',
                'data' => null,
            ], 403);
        }

        return response()->json([
            'ok' => true,
            'message' => "Bienvenido {$userAuth->first_name} {$userAuth->last_name}",
            "data" => [
                "user" => $userAuth,
                "token" => $userAuth->createToken('personalToken')->plainTextToken,
            ]
        ], 200);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::make($request->validated());
        $user->password = Hash::make($request->password);
        if($user->save()) {
            return response()->json([
                'ok' => true,
                'message' => "Usuario {$request->first_name} {$request->last_name} creado correctamente",
                "data" => [
                    "user" => $user,
                    "token" => $user->createToken('clientToken')->plainTextToken,
                ]
            ], 201);
        }

        return response()->json([
            'ok' => false,
            'message' => "Error al registrar el usuario {$request->name}{$request->first_name} {$request->last_name}.",
            "data" => null,
        ], 500);

    }

    public function me()
    {
        return response()->json([
            'ok'    => true,
            'message' => 'Usuario Logueado',
            'data' => [
                'user' => auth()->user()
                ]
        ]);
    }
}
