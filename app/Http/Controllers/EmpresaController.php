<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmpresaController extends Controller
{
            public function asignarEmpresaAUsuario($usersId, $empresasId)
        {
            $users = User::find($userId);
            $users->empresas()->attach($empresasId);
        }

}
