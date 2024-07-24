<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chofer;
use Illuminate\Database\QueryException;

class ChoferController extends Controller
{
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'chofer' => 'required|string|max:255',
            'ci' => 'required|string|max:255|unique:chofers,ci',
            'celular' => 'required|string|max:255',
            'domicilio' => 'required|string|max:255',
        ]);

        try {
            // Intentar crear el nuevo chofer
            $chofer = Chofer::create($validatedData);

            // Redirigir o devolver una respuesta exitosa
            return redirect()->route('chofers.index')->with('success', 'Chofer creado exitosamente.');
        } catch (QueryException $exception) {
            // Si la excepción es por una entrada duplicada, mostrar un mensaje de error amigable
            if ($exception->errorInfo[1] == 1062) {
                return back()->withErrors(['ci' => 'Ya existe un chofer con el mismo número de cédula.'])->withInput();
            }

            // Si es otro tipo de excepción, lanzar la excepción
            throw $exception;
        }
    }
}
