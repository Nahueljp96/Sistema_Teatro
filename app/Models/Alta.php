<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Alta extends Model
{
    use HasFactory;

    protected $fillable = ['alumno_id', 'curso_id', 'pago_al_dia', 'fecha_alta'];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function isCaducada()
    {
        $fechaAlta = Carbon::parse($this->fecha_alta);
        return $fechaAlta->addMonth()->isPast();
    }
}