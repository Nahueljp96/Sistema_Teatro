<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno; // Modelo correspondiente a alumnos
use App\Models\Asistencia; // Modelo para registrar asistencias
use App\Models\Pago; // Modelo para registrar pagos
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PagoController extends Controller
{
    public function showVerificarPago()
    {
        return view('verificar-pago');
    }

    public function verificarPago(Request $request)
    {
        $request->validate([
            'dni' => 'required|numeric|exists:alumnos,dni',
        ]);

        // Buscar al alumno
        $alumno = Alumno::where('dni', $request->dni)->first();

        if (!$alumno) {
            return back()->withErrors(['error' => 'Alumno no encontrado.']);
        }

        // Buscar el curso asociado al alumno
        $cursoId = DB::table('altas') // 
            ->where('alumno_id', $alumno->id)
            ->value('curso_id');

        if (!$cursoId) {
            return back()->withErrors(['error' => 'El alumno no está inscrito en ningún curso.']);
        }

        // Registrar asistencia
        Asistencia::create([
            'alumno_id' => $alumno->id,
            'curso_id' => $cursoId,
            'fecha_asistencia' => Carbon::now(),
        ]);

        // Obtener los días restantes de la cuota
        $diasRestantes = $this->calcularDiasRestantes($alumno->id);

        // Retornar la vista con información
        return view('verificar-pago', [
            'alumno' => $alumno,
            'diasRestantes' => $diasRestantes,
        ]);
    }

    private function calcularDiasRestantes($alumnoId)
    {
        // Obtener el último pago del alumno
        $ultimoPago = Pago::where('alumno_id', $alumnoId)->latest()->first();

        if (!$ultimoPago) {
            return 0; // Si no hay pagos, no hay días restantes
        }

        // Calcular la fecha de vencimiento
        $vencimiento = Carbon::parse($ultimoPago->fecha_pago)->addDays(30);

        // Calcular los días restantes (si son negativos, devolver 0)
        return max(Carbon::now()->diffInDays($vencimiento, false), 0);
    }
}
