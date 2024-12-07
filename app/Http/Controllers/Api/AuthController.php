<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $rules = [
            'identification' => 'required',
            'name'           => 'required|string|max:255',
            'last_name'      => 'nullable|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'id_role'        => 'required|integer|exists:roles,id',
            'telephone'      => 'required|string|max:15',
            'address'        => 'required|string|max:255',
            'department'     => 'required|string|max:255',
            'municipality'   => 'required|string|max:255',
            'password'       => 'required|string|min:8'
        ];

        // Mensajes personalizados
        $messages = [
            'identification.required' => 'La identificación es obligatoria.',
            'identification.numeric'  => 'La identificación debe ser un número.',
            'email.required'          => 'El correo electrónico es obligatorio.',
            'email.email'             => 'El formato del correo electrónico no es válido.',
            'email.unique'            => 'El correo ya está registrado.',
            'id_role.required'        => 'El rol es obligatorio.',
            'id_role.exists'          => 'El rol seleccionado no es válido.',
            'password.required'       => 'La contraseña es obligatoria.',
            'password.min'            => 'La contraseña debe tener al menos 8 caracteres.',
        ];

        // Validar datos
        $validator = Validator::make($request->all(), $rules, $messages);

        // Comprobar si hay errores
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Si pasa la validación, procesamos los datos
        $validatedData = $validator->validated();

        // Encriptar la contraseña
        $validatedData['password'] = Hash::make($validatedData['password']);

        // Crear un usuario con los datos validados
        $user = User::create($validatedData);

        // Responder con el usuario creado
        return response()->json([
            'message' => 'Usuario creado correctamente.',
            'user' => $user
        ], 201);
    }

    public function login(): JsonResponse
    {

        if (!$token = auth('api')->attempt([
            "email"     => request()->email,
            "password"  => request()->password,
        ])) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function getUser(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    protected function respondWithToken($token): JsonResponse
    {
        $user_id = auth('api')->user()->id;
        $user = User::with('role')->findOrFail($user_id);
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'user'         => $user
        ]);
    }
}
