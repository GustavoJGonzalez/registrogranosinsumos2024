<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ControlAcceso;
use App\Models\Remision;
class RemisionController extends Controller
{
    public function createFromControlAcceso($id)
    {
        // Busca el registro en control_accesos
        $controlAcceso = ControlAcceso::findOrFail($id);

      // Crea el registro en remisions
      $remision = Remision::create([
        'empresa_clientes_id' => $controlAcceso->empresa_clientes_id,
        'empresas_id' => $controlAcceso->empresas_id,
        'zafras_id' => $controlAcceso->zafras_id,
        'productos_id' => $controlAcceso->productos_id,
        'transportadoras_id' => $controlAcceso->transportadoras_id,
        'chofer' => $controlAcceso->chofer,
        'pesoTara' => 0, // Ajusta según sea necesario
        'pesoBruto' => 0, // Ajusta según sea necesario
        'pesoNeto' => 0, // Ajusta según sea necesario
        'chapaCamion' => $controlAcceso->chapaCamion,
        'chapaSemi' => $controlAcceso->chapaSemi,
        'humedad' => 0, // Ajusta según sea necesario
        'impureza' => 0, // Ajusta según sea necesario
        'fecha_registro' => now()->toDateString(),
        'hora_registro' => now()->toTimeString(),
    ]);

        return response()->json([
            'message' => 'Remisión creada exitosamente.',
            'remision' => $remision,
        ]);
    }
}
