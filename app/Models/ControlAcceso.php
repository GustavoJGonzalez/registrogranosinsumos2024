<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ControlAcceso extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'operacion',
        'chofers_id',
        'ci',
        'celular',
        'domicilio',
        'colorCamion',
        'ejesCamion',
        'chapaCamion',
        'colorSemi',
        'ejesSemi',
        'chapaSemi',
        'transportadoras_id',
        'empresas_id',
        'empresa_clientes_id',
        'productos_id',
        'insumos_id',
        'fecha_ingreso',
        'hora_ingreso',
        'fecha_salida',
        'hora_salida',
    ];



        protected static function booted()
    {
        static::created(function ($controlAcceso) {
            Remision::create([
                'chofers_id' => $controlAcceso->chofers_id,
                'ci'=>$controlAcceso->ci,
                'celular'=>$controlAcceso->celular,
                'domicilio'=>$controlAcceso->domicilio,
                'chapaCamion' => $controlAcceso->chapaCamion,
                    'empresas_id' => $controlAcceso->empresas_id,
                    'empresa_clientes_id' => $controlAcceso->empresa_clientes_id,
                    'productos_id' => $controlAcceso->productos_id,
                'chapaSemi' => $controlAcceso->chapaSemi,
                'transportadoras_id' => $controlAcceso->transportadoras_id,
                'fecha_ingreso' => $controlAcceso->fecha_ingreso,
                'hora_ingreso' => $controlAcceso->hora_ingreso,
                
            ]);
           // Recepcion::create([
            //    'chofers_id' => $controlAcceso->chofers_id,
            //    'ci'=>$controlAcceso->ci,
            //    'celular'=>$controlAcceso->celular,
            //    'domicilio'=>$controlAcceso->domicilio,
            //    'chapaCamion' => $controlAcceso->chapaCamion,
            //        'empresas_id' => $controlAcceso->empresas_id,
            //        'empresa_clientes_id' => $controlAcceso->empresa_clientes_id,
             //       'productos_id' => $controlAcceso->productos_id,
             //   'chapaSemi' => $controlAcceso->chapaSemi,
            //    'transportadoras_id' => $controlAcceso->transportadoras_id,
             //   'fecha_ingreso' => $controlAcceso->fecha_ingreso,
             //   'hora_ingreso' => $controlAcceso->hora_ingreso,
                
            //]);
            //Chofer::create([
             //   'chofers_id' => $controlAcceso->chofers_id,
            //    'ci'=> $controlAcceso->ci,
              //  'celular'=> $controlAcceso->celular,
              //  'domicilio'=>$controlAcceso->domicilio,
                
           // ]);
        });
   }
// protected static function booted()
// {
//     static::created(function ($controlAcceso) {
//         if ($controlAcceso->tipo_ingreso === 'remisions') {
//             Remision::create([
//                 'chofer' => $controlAcceso->chofer,
//                 'chapaCamion' => $controlAcceso->chapaCamion,
//                 'chapaSemi' => $controlAcceso->chapaSemi,
//                 'transportadoras_id' => $controlAcceso->transportadoras_id,
//                 'empresas_id' => $controlAcceso->empresas_id,
//                 'empresa_clientes_id' => $controlAcceso->empresa_clientes_id,
//                 'productos_id' => $controlAcceso->productos_id,
//                 'insumos_id' => $controlAcceso->insumos_id,
//
//
//                 // Aquí puedes agregar más campos si es necesario
 //            ]);
  //       } elseif ($controlAcceso->tipo_ingreso === 'recepcions') {
//             Recepcion::create([
 //                'chofer' => $controlAcceso->chofer,
 //                'chapaCamion' => $controlAcceso->chapaCamion,
 //                'chapaSemi' => $controlAcceso->chapaSemi,
 //                'transportadoras_id' => $controlAcceso->transportadoras_id,
 //                // Aquí puedes agregar más campos si es necesario
 //            ]);
  //       }
  //   });
 //}






    public function transportadoras()
    {
        
        return $this->belongsTo(Transportadora::class, 'transportadoras_id');
    }

    public function empresas()
    {
        
        return $this->belongsTo(Empresa::class, 'empresas_id');
    }
    public function empresas_clientes()
    {
        
        return $this->belongsTo(EmpresaCliente::class, 'empresa_clientes_id');
    }
    public function productos()
    {
        
        return $this->belongsTo(Producto::class, 'productos_id');

    }
    public function insumos()
    {
        
        return $this->belongsTo(Insumo::class, 'insumos_id');
    }

    public function chofers()
    {
        
        return $this->belongsTo(Chofer::class, 'chofers_id');
    }
}
