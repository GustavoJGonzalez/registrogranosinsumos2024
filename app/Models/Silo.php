<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Silo extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['nombre'];

    public function recepcion()
    {
        
        return $this->belongsTo(Recepcion::class, 'silos_id');
    }
}
