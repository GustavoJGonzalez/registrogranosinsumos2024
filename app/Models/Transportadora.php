<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transportadora extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['nombre','ruc'];
    public function remision()
    {
        
        return $this->belongsTo(Transportadora::class, 'transportadoras_id');
    }
}
