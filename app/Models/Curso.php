<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curso extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'categoria_adiccion_id',
        'creado_por',
        'titulo',
        'slug',
        'descripcion',
        'objetivo',
        'imagen_portada',
        'estado',
        'publicado_at',
    ];

    protected $casts = [
        'publicado_at' => 'datetime',
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaAdiccion::class, 'categoria_adiccion_id');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function modulos()
    {
        return $this->hasMany(Modulo::class);
    }

    public function encuestas()
    {
        return $this->hasMany(Encuesta::class);
    }

    public function inscripciones()
    {
        return $this->hasMany(InscripcionCurso::class);
    }
}
