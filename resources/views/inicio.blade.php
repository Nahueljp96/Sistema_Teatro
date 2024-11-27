@extends('layouts.app')

@section('title', 'Inicio')

@section('hero-bg', 'https://via.placeholder.com/1920x600?text=Teatro')

@section('hero-title', 'Bienvenidos al Mundo del Teatro')

@section('content')
    <h2 class="text-center mb-4">Explora, Aprende y Vive el Teatro</h2>
    <div class="row">
        <div class="col-md-4 text-center">
            <h3>Obras</h3>
            <p>Disfruta de nuestras producciones únicas, llenas de emoción y creatividad.</p>
        </div>
        <div class="col-md-4 text-center">
            <h3>Cursos</h3>
            <p>Aprende sobre actuación, dirección y producción con nuestros cursos especializados.</p>
        </div>
        <div class="col-md-4 text-center">
            <h3>Nosotros</h3>
            <p>Conoce nuestra historia y el equipo que hace posible cada espectáculo.</p>
        </div>
    </div>
@endsection
