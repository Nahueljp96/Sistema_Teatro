<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    protected $fillable = ['dni', 'nombre', 'apellido', 'email'];

    public function altas()
    {
        return $this->hasMany(Alta::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function cursos()
    {   
        return $this->belongsToMany(Curso::class, 'altas')->withTimestamps();
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class);
    }





}