<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CursoVenta extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada.
     *
     * @var string
     */
    protected $table = 'cursos_ventas';

    /**
     * La clave primaria de la tabla.
     *
     * @var string
     */
    protected $primaryKey = 'curso_venta_id';

    /**
     * Indica si la clave primaria es incremental.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * El tipo de dato de la clave primaria.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'comprador_email',
        'cantidad',
        'nombre_comprador',
        'estado_pago',
        'telefono',
        'preference_id',
    ];

    /**
     * Los atributos que deben ser tratados como fechas.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id', 'id');
    }
}
