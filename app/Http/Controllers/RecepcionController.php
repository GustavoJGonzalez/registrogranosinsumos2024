<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Recepcion;

class RecepcionController extends Controller
{
    public function obtenerChapaCamion(Request $request)
    {
        $chofer = $request->input('chofer');

        // Buscar el último registro de chapa de camión para el chofer
        $ultimoRegistro = Recepcion::where('chofer', $chofer)->latest()->first();

        // Devolver la chapa del camión como respuesta
        return response()->json(['chapaCamion' => $ultimoRegistro ? $ultimoRegistro->chapaCamion : '']);
    }
}
