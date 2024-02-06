<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Recepcion extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['empresas_id','zafras_id','productos_id','parcelas_id','silos_id','chofer','pesoBruto','pesoTara','pesoNeto','chapaCamion','chapaSemi','humedad','impureza','fecha_registro','hora_registro'];

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


    public function parcelas()
    {
        
        return $this->belongsTo(Parcela::class, 'parcelas_id');
    }

    public function silos()
    {
        
        return $this->belongsTo(Silo::class, 'silos_id');
    }




  




    // Un usuario puede tener muchas recepciones
    public function users()
    {
        return $this->belongsToMany(User::class);
    }


   

    // Una recepcion pertenece a una empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresas_id');
    }

    public function scopePorEmpresa($query, $empresaId)
    {
        return $query->where('empresas_id', $empresaId);
    }
}
