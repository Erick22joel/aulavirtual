<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $fillable = [
        'curso_id',
        'titulo',
        'descripcion',
        'orden',
        'estado',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function contenidos()
    {
        return $this->hasMany(Contenido::class);
    }
}
