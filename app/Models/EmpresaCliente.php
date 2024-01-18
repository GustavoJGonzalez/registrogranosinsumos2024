<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpresaCliente extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['nombre','ruc','direccion','fecha_registro'];

    public function remision()
    {
        
        return $this->belongsTo(Remision::class, 'empresa_clientes_id');
    }
}
