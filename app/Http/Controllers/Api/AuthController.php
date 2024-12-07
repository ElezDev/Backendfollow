<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $rules = [
            'identification'      => 'required',
            'name'                => 'required|string|max:255',
            'last_name'           => 'nullable|string|max:255',
            'email'               => 'required|email|unique:users,email',
            'id_role'             => 'required|integer|exists:roles,id',
            'telephone'           => 'required|string|max:15',
            'address'             => 'required|string|max:255',
            'department'          => 'required|string|max:255',
            'municipality'        => 'required|string|max:255',
            'password'            => 'required|string|min:8',
        ];

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

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        $user = new User;
        $user->identification = $request->identification;
        $user->name           = $request->name;
        $user->last_name      = $request->last_name ?? null;
        $user->email          = $request->email;
        $user->id_role        = $request->id_role;
        $user->telephone      = $request->telephone;
        $user->address        = $request->address;
        $user->department     = $request->department;
        $user->municipality   = $request->municipality;
        $user->password       = bcrypt($request->password);

        $user->save();


        return response()->json([
            'message' => 'Usuario creado correctamente.',
            'user'    => $user
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        // Validar que el email y la contraseña sean proporcionados
        $credentials = $request->only('email', 'password');

        try {
            // Intentar obtener el token con las credenciales
            if (!$token = JWTAuth::attempt($credentials)) {

                var_dump($token);
                // Si el token no es generado, devolver error 401
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Si el token es generado exitosamente, responder con el token
            return $this->respondWithToken($token);
        } catch (JWTException $e) {
            // Si ocurre algún error con el JWT, devolver un error con mensaje específico
            return response()->json(['error' => 'Could not create token', 'message' => $e->getMessage()], 500);
        }
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
