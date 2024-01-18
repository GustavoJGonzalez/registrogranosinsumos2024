<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zafra extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['año'];

    public function recepcion()
    {
        
        return $this->belongsTo(Recepcion::class, 'zafras_id');
    }

}
