@extends('layouts.app')

@section('title', 'Obras de Teatro')

@section('hero-bg', 'path/to/your/custom-image.jpg')  {{-- Puedes personalizar la imagen de fondo aquí --}}
@section('hero-title', 'Explora las Mejores Obras de Teatro')

@push('styles')
    <style>
        .card {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .card-body {
            flex-grow: 1;
        }
        .card {
        transition: transform 0.3s ease;
            }

        .card:hover {
                transform: scale(1.05);
            }
    </style>
@endpush



@section('content')
    <h2 class="text-center mb-5">Obras Disponibles</h2>
    <div class="row g-4">
        @foreach ($obras as $obra)
            <div class="col-lg-4 col-md-6">
                <div class="card">
                    <img src="{{ asset('storage/' . $obra->imagen) }}" class="card-img-top" alt="{{ $obra->titulo }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $obra->titulo }}</h5>
                        <p class="card-text">{{ Str::limit($obra->descripcion, 100) }}</p>
                        <p><strong>Asientos disponibles:</strong> {{ $obra->asientos_disponibles }}</p>
                        <a href="{{ route('comprar-entradas', ['obra_id' => $obra->id]) }}" class="btn btn-comprar w-100">Comprar Entrada</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
