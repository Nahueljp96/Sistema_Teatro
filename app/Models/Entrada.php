<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    use HasFactory;

    protected $fillable = [
        'obra_id',
        'comprador_email',
        'cantidad',
        'nombre_comprador',
        'estado_pago'


    ];
}
