<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecepcionGranos extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['empresas_id','zafras_id','productos_id','parcelas_id','silos_id','chofer','pesoBruto','pesoTara','pesoNeto','pesoLiquido','chapaCamion','chapaSemi','humedad','impureza','fecha_registro','hora_registro'];

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

}
