<?php

namespace App\Http\Controllers\Api;

use App\Models\notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
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
        $notifications = Notification::query();

        // Verifica si el parámetro 'included' está presente y tiene el valor 'Company'
        if ($request->query('included') === 'UserRegister') {
            $notifications->with('userRegister'); // Carga la relación con la compañía
        }

        return response()->json($notifications->get());
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
            'shipping_date' => 'required|date',
            'content' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        // Creación del nuevo contrato
        $notification = Notification::create($request->all());

        return response()->json($notification, 201); // Respuesta con código 201
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
        $notification = Notification::findOrFail($id);

        return response()->json($notification);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Contract $contract
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Notification $notification)
    {
        // Validación de los datos de entrada
        $request->validate([
            'shipping_date' => 'required|date',
            'content' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
        ]);

        // Actualización del contrato
        $notification->update($request->all());

        return response()->json($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Contract $contract
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Notification $notification)
    {
        // Elimina el contrato
        $notification->delete();

        return response()->json(null, 204); // Respuesta vacía con código 204
    }
}

