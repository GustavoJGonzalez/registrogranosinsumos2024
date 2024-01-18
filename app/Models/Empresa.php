<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['nombre','ruc','direccion','fecha_registro'];

    public function recepcion()
    {
        
        return $this->belongsTo(Recepcion::class, 'empresas_id');
    }
}
