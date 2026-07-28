<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthRequest;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Registrar un nuevo usuario desde la API.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        $token = $user
            ->createToken($data['device_name'] ?? 'api')
            ->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado correctamente.',
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    /**
     * Iniciar sesión desde la API.
     * 
     */
    public function login(LoginRequest $request): JsonResponse
    {

        //$data = $request->validated();

        if(!Auth::attempt($request->only('email', 'password'))) {
        return response()->json([
            'message' => 'Inicio de sesión incorrecto.',
        ],401); 
        }

        $user = Auth::user(); 

        return response()->json([
            'message' => 'Inicio de sesión correcto.',
            'user' => $user,
            'token' => $user->createToken($request['device_name'] ?? 'api')->plainTextToken,
            'token_type' => 'Bearer',
        ]); 

       /*  $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => [
                    'Las credenciales proporcionadas son incorrectas.',
                ],
            ]);
        }

        $token = $user
            ->createToken($data['device_name'] ?? 'api')
            ->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión correcto.',
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ]); */
    }

    /**
     * Obtener el usuario autenticado.
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    /**
     * Cerrar solamente la sesión/token actual.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->tokens()->where('id', $request->user()->currentAccessToken()->id)->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente.',
        ]);
    }

    /**
     * Eliminar todos los tokens API del usuario.
     */
    public function logoutAll(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Todos los tokens fueron eliminados.',
        ]);
    }
}