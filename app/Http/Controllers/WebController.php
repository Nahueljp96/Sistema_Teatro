<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Obra; 
use App\Models\Entrada; 

class WebController extends Controller
{
    public function inicio() {
        return view('inicio');
    }
    
    public function obras() {
        $obras = Obra::all();
        return view('obras', compact('obras'));
    }
    
    public function comprarEntrada(Request $request) {
        $obra = Obra::find($request->obra_id);
        if ($obra->asientos_disponibles < $request->cantidad) {
            return redirect()->back()->withErrors('No hay suficientes asientos disponibles.');
        }
    
        $obra->asientos_disponibles -= $request->cantidad;
        $obra->save();
    
        Entrada::create([
            'obra_id' => $obra->id,
            'comprador_email' => $request->email,
            'cantidad' => $request->cantidad,
        ]);
    
        // Generar PDF
        $pdf = PDF::loadView('pdf.entrada', compact('obra', 'request'));
        Mail::to($request->email)->send(new EntradaMail($pdf));
    
        return redirect()->back()->with('success', 'Compra realizada. Revisa tu email.');
    }
    
}
