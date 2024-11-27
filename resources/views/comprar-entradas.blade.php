@extends('layouts.app')

@section('title', 'Comprar Entrada')

@section('content')
    <div class="container">
        <h1>Comprar Entrada para: {{ $obra->titulo }}</h1>
        
        <form action="{{ route('procesar-compra') }}" method="POST">
            @csrf
            <input type="hidden" name="obra_id" value="{{ $obra->id }}">
            <div class="mb-3">
                <label for="cantidad" class="form-label">Cantidad de Entradas</label>
                <input type="number" id="cantidad" name="cantidad" class="form-control" min="1" max="{{ $obra->asientos_disponibles }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Proceder al Pago</button>
        </form>
        
    </div>
@endsection
