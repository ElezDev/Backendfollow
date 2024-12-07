<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trainer;
use Illuminate\Http\Request;

class TrainerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Recupera todos los contratos
        $trainers = Trainer::query();

        // Verifica si el parámetro 'included' está presente y tiene el valor 'Company'
        if ($request->query('included') === 'UserRegister') {
            $trainers->with('userRegister'); // Carga la relación con la compañía
        }

        return response()->json($trainers->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validación de los datos de entrada
        $request->validate([
            'number_of_monitoring_hours' => 'required|integer',
            'month' => 'required|date',
            'number_of_trainees_assigned' => 'required|integer',
            'network_knowledge' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'user_id' => 'required|exists:users,id',
        ]);

        // Creación del nuevo contrato
        $trainer = Trainer::create($request->all());

        return response()->json($trainer, 201); // Respuesta con código 201
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Recupera un contrato específico
        $trainer = Trainer::findOrFail($id);

        return response()->json($trainer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Contract $contract
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Trainer $trainer)
    {
        // Validación de los datos de entrada
        $request->validate([
            'number_of_monitoring_hours' => 'required|integer',
            'month' => 'required|date',
            'number_of_trainees_assigned' => 'required|integer',
            'network_knowledge' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'user_id' => 'required|exists:users,id',
        ]);

        // Actualización del contrato
        $trainer->update($request->all());

        return response()->json($trainer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Contract $contract
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Trainer $trainer)
    {
        // Elimina el contrato
        $trainer->delete();

        return response()->json(null, 204); 
    }
}
