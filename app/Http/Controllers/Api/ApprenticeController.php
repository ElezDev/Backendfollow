<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Apprentice;
use Illuminate\Http\Request;

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
            'id_user_register' => 'required|exists:user_registers,id',
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
            'id_user_register' => 'required|exists:user_registers,id',
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
}
