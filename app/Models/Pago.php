<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = ['alumno_id', 'monto', 'fecha_pago'];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }
}