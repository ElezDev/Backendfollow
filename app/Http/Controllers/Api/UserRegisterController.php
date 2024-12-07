<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use App\Models\User;
use Illuminate\Http\Request;

class UserRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Recupera todos los registros de usuario
        $userRegisters = User::with('Role')->get();
        return response()->json($userRegisters);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */


    public function store(Request $request)
{
    
    $request->validate([
        'identification' => 'required|integer',
        'name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'telephone' => 'required|integer',
        'email' => 'required|email|max:255|unique:users',
        'address' => 'required|string|max:255',
        'department' => 'required|string|max:255',
        'municipality' => 'required|string|max:255',
        'password' => 'nullable|string|max:255', // La contraseña no es requerida, se establecerá por defecto
        'id_role' => 'required|exists:roles,id',
    ]);

    // Asignar la contraseña por defecto 'sena' si no se proporciona
    $request->merge([
        'password' => $request->password ?? 'sena',
    ]);

    $user = User::create($request->all());

    if ($request->id_role == 3) {
        $trainerData = [
            'number_of_monitoring_hours' => 1,
            'month' => now(), 
            'number_of_trainees_assigned' => 0, 
            'network_knowledge' => 'Basic', 
            'start_date' => now(),
            'end_date' => now()->addMonth(), 
            'user_id' => $user->id, 
        ];

        $trainer = Trainer::create($trainerData);
    }

    return response()->json($user, status: 201);
}


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Recupera un registro de usuario específico
        $userRegister = User::findOrFail($id);

        return response()->json($userRegister);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        // Validación de los datos de entrada
        $request->validate([
            'identification' => 'required|integer',
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'telephone' => 'required|integer',
            'email' => 'required|email|max:255|unique:users',
            'address' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'municipality' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'id_role' => 'required|exists:roles,id',
        ]);

        // Actualización del registro de usuario
        $user->update($request->all());

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        // Elimina el registro de usuario
        $user->delete();

        return response()->json(null, 204); // Respuesta vacía con código 204
    }

    public function getUserRegistersByRoles()
    {
        $users = User::whereIn('id_role', [1, 2])->with('Role')->get();
        return response()->json($users);
    }

    public function getUserRegistersByRolesInstructor()
    {
        $users = User::whereIn('id_role', [3])
            ->with(['role', 'trainer', 'apprentices']) 
            ->get()
            ->map(function($user) {
                $user->num_apprentices_assigned = $user->apprentices->count();
                return $user;
            });
        
        return response()->json($users);
    }
    


    public function getTrainer()
    {
        $users = User::whereIn('id_role', [3])
                     ->with('trainers', 'Role')  
                     ->get();
    
        return response()->json($users);
    }
    

    
    public function getUserRegistersByAprendiz()
    {
        $users = User::whereIn('id_role', [4])
        ->with([
            'role',
            'apprentice.trainer.user'
        ])
        ->get();

    
        return response()->json($users);
    }
    
}
