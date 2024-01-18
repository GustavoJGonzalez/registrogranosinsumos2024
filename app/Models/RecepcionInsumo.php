<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecepcionInsumo extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['empresa_clientes_id','empresas_id','insumos_id','numeroRemision','chofer','pesoBruto','pesoTara','pesoNeto','chapaCamion','chapaSemi','fecha_registro','hora_registro'];



    public function empresas()
    {
        
        return $this->belongsTo(Empresa::class, 'empresas_id');
    }

    public function insumos()
    {
        
        return $this->belongsTo(Insumo::class, 'insumos_id');
    }

    public function empresas_clientes()
    {
        
        return $this->belongsTo(EmpresaCliente::class, 'empresa_clientes_id');
    }
   // public function medidas()
   // {
    //    
    //    return $this->belongsTo(Medida::class, 'medidas_id');
    //}
}
