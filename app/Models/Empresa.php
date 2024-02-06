<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['nombre','ruc','direccion','fecha_registro'];

    // public function recepcions()
    // {
    //     return $this->hasMany(Recepcion::class);
    // }



    // Un usuario puede tener muchas empresas
    public function users()
    {
        return $this->belongsToMany(User::class, 'empresa_user', 'empresa_id', 'user_id');
    }

    //Una empresa puede tener muchas recepciones
    public function recepcions()
    {
        return $this->hasMany(Recepcion::class, 'empresas_id');
    }
}
