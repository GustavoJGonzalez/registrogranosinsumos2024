<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parcela extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['nombre','hectarea'];

    public function recepcion()
    {
        
        return $this->belongsTo(Recepcion::class, 'parcelas_id');
    }
}
