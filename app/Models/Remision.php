<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Remision extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['empresas_id','zafras_id','productos_id','empresa_clientes_id','transportadoras_id','chofers_id','ci','celular','domicilio','pesoTara','pesoBruto','pesoNeto','chapaCamion','chapaSemi','humedad','impureza','fecha_ingreso','hora_ingreso'];

    public function empresas()
    {
        
        return $this->belongsTo(Empresa::class, 'empresas_id');
    }
    public function zafras()
    {
        
        return $this->belongsTo(Zafra::class, 'zafras_id');
    }
    public function productos()
    {
        
        return $this->belongsTo(Producto::class, 'productos_id');

    }
    public function transportadoras()
    {
        
        return $this->belongsTo(Transportadora::class, 'transportadoras_id');
    }

    public function empresas_clientes()
    {
        
        return $this->belongsTo(EmpresaCliente::class, 'empresa_clientes_id');
    }

    public function chofers()
    {
        
        return $this->belongsTo(Chofer::class, 'chofers_id');
    }
}

