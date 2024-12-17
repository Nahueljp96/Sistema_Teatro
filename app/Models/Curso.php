<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'descripcion', 'precio', 'imagen'];

    public function altas()
    {
        return $this->hasMany(Alta::class);
    }

    /**
 * Relación con el modelo CursoVenta.
 * Un curso puede tener muchas ventas.
 */
    public function ventas()
    {
        return $this->hasMany(CursoVenta::class, 'curso_id', 'id');
    }

}