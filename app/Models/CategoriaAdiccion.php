<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaAdiccion extends Model
{
    protected $table = 'categorias_adicciones';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    public function cursos()
    {
        return $this->hasMany(Curso::class);
    }
}
