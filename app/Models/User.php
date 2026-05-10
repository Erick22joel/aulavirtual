<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;
use App\Models\PerfilUsuario;
use App\Models\Curso;
use App\Models\InscripcionCurso;
use App\Models\IntentoEncuesta;
use App\Models\ProgresoContenido;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'estado',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function perfil()
    {
        return $this->hasOne(PerfilUsuario::class);
    }

    public function cursosCreados()
    {
        return $this->hasMany(Curso::class, 'creado_por');
    }

    public function inscripciones()
    {
        return $this->hasMany(InscripcionCurso::class);
    }

    public function intentosEncuesta()
    {
        return $this->hasMany(IntentoEncuesta::class);
    }

    public function progresoContenidos()
    {
        return $this->hasMany(ProgresoContenido::class);
    }

    public function esHiperAdmin(): bool
    {
        return $this->role && $this->role->slug === 'hiperadmin';
    }

    public function esAdmin(): bool
    {
        return $this->role && $this->role->slug === 'admin';
    }

    public function esUsuario(): bool
    {
        return $this->role && $this->role->slug === 'usuario';
    }
}
