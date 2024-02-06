<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

use Spatie\Permission\Traits\HasRoles;

use Illuminate\Database\Eloquent\Model;



class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    // Una empresa puede tener muchos usuarios
    public function empresas()
    {
        return $this->belongsToMany(Empresa::class);
    }



   // Una recepcion puede pertenecer a muchos usuarios
    public function recepcions()
    {
        return $this->belongsToMany(Recepcion::class);
    }





        // En el modelo User
    public function essuper_admin()
    {
        return $this->hasRole('super_admin');
    }



    // public function empresas()
    // {
    //     if ($this->esUsuarioH3()) {
    //         // Si el usuario es UsuarioH3, mostrar solo la empresa H3 Agricola
    //         return $this->belongsToMany(Empresa::class, 'empresa_user', 'user_id', 'empresas_id')
    //             ->where('nombre_empresa', 'H3 Agricola');
    //     }

    //     // Para otros roles o usuarios, mostrar todas las empresas asociadas a ellos
    //     return $this->belongsToMany(Empresa::class, 'empresa_user', 'user_id', 'empresas_id');
    // }


    // public function esJefe()
    // {
    //     // Aquí debes implementar la lógica para determinar si el usuario es un jefe
    //     // Por ejemplo, supongamos que el campo 'rol' en la tabla de usuarios indica el rol de cada usuario
    //     return $this->roles === 'jefe';
    // }

    // public function esUsuarioH3()
    // {
    //     // Aquí debes implementar la lógica para determinar si el usuario es un jefe
    //     // Por ejemplo, supongamos que el campo 'rol' en la tabla de usuarios indica el rol de cada usuario
    //     return $this->roles === 'UsuarioH3';
    // }
    // public function recepcion()
    // {

    //     return $this->belongsTo(Recepcion::class, 'user_id');
    // }

}





