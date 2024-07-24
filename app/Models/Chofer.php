<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chofer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'chofer',
        'ci',
        'celular',
        'domicilio',
        
    ];
    public static function findByCI($ci)
    {
        return self::where('ci', $ci)->first();
    }
    public function remisions()
    {
        
        return $this->belongsTo(Chofer::class, 'chofers_id');
    }
}
