<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Apprentice;
use App\Models\Contract;
use App\Models\User;
use App\Models\User_register;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ApprenticeController extends Controller
{
    public function index(Request $request)
    {
        $apprentices = Apprentice::query();

        // Verifica si el parámetro 'included' está presente y tiene el valor 'Company'
        if ($request->query('included') === 'UserRegister') {
            $apprentices->with('userRegister'); // Carga la relación con la compañía
        }

        return response()->json($apprentices->get());

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validación de los datos de entrada
        $request->validate([
            'academic_level' => 'required|max:255',
            'program' => 'required|max:255',
            'ficha' => 'required|max:255',
            'user_id' => 'required|exists:users,id',
            'id_contract' => 'required|exists:contracts,id',
            'id_trainer' => 'required|exists:trainers,id', // Cambiado a followup_id
        ]);

        $apprentice = Apprentice::create($request->all());
        return response()->json($apprentice, 201); // Respuesta con código 201
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Recupera una agenda en específico, aplicando el scope de inclusión
        $apprentice = Apprentice::included()->findOrFail($id);
        return response()->json($apprentice);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Diary  $diary
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Apprentice $apprentice)
    {
        // Validación de los datos de entrada
        $request->validate([
            'academic_level' => 'required|max:255',
            'program' => 'required|max:255',
            'ficha' => 'required|max:255',
            'user_id' => 'required|exists:users,id',
            'id_contract' => 'required|exists:contracts,id',
            'id_trainer' => 'required|exists:trainers,id', // Cambiado a followup_id
        ]);

        // Actualización de agenda
        $apprentice->update($request->all());
        return response()->json($apprentice);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Diary  $diary
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Apprentice $apprentice)
    {
        // Elimina agenda
        $apprentice->delete();
        return response()->json(null, 204); // Respuesta vacía con código 204
    }





    public function asignarInstructorAprendiz(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'identification' => $request->identification,
                'name' => $request->name,
                'last_name' => $request->last_name,
                'telephone' => $request->telephone,
                'email' => $request->email,
                'address' => $request->address,
                'department' => $request->department,
                'municipality' => $request->municipality,
                'password' => bcrypt('aprendiz'), 
                'id_role' => 4, 
            ]);
    
            $contract = Contract::create([
                'code' => rand(1000, 9999), 
                'type' => 'default', 
                'start_date' => now(),
                'end_date' => now()->addYear(),
                'id_company' => $request->id_company, 
            ]);
    
            Apprentice::create([
                'academic_level' => $request->academic_level,
                'program' => $request->program,
                'ficha' => $request->ficha,
                'user_id' => $user->id,
                'id_contract' => $contract->id,
                'id_trainer' => $request->id_trainer,
            ]);
    
            DB::commit();
    
            return response()->json(['message' => 'Aprendiz registrado exitosamente.'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Ocurrió un error al registrar el aprendiz: ' . $e->getMessage()], 500);
        }
    }
    
}
