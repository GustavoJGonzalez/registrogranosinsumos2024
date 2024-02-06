<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Recepcion;



use Illuminate\Support\Facades\Auth;


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



    

        public function index()
        {
            $user = Auth::user()->load('empresas');

            $empresaId = $user->empresas->isNotEmpty() ? $user->empresas->first()->id : null;

            if ($empresaId) {
                $recepciones = Recepcion::porEmpresa($empresaId)->get();
            } else {
                $recepcions = collect();
            }

            return view('recepciones.index', compact('recepciones'));
        }








   // public function index()
    //{
    //    $user = Auth::user();

        // Obtener las recepciones del usuario actual
     //   $recepciones = $user->essuper_admin() ? Recepcion::all() : $user->empresa->recepciones;

    //    return view('recepciones.index', compact('recepciones'));
   // }





   // public function index()
   // {
   //     $user = Auth::user();

        // Obtén las recepciones del usuario actual y filtra por la empresa del usuario
    //    $recepciones = Recepcion::porEmpresa($user->empresas->first()->id)->get();

    //    return view('recepciones.index', compact('recepciones'));
    //}







      //  public function index()
   // {
     //   // Obtener el usuario actualmente autenticado
       /// $user = Auth::user();

        // Obtener las recepciones asociadas con el usuario actual
      //  $recepcions = $user->recepcions;

        // Pasar los datos de recepciones a la vista 'recepcions.index'
      //  return view('recepcions.index', compact('recepcions'));
   // }

}
