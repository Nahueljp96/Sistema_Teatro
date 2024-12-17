@extends('layouts.app')

@section('title', 'Inicio')

@section('hero-bg', 'https://via.placeholder.com/1920x600?text=Teatro')

@section('hero-title', 'Bienvenidos al Mundo del Teatro')

@section('content')
    <h2 class="text-center mb-4">Explora, Aprende y Vive el Teatro</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card text-center mb-4">
                <img src="https://via.placeholder.com/300x200?text=Obras" class="card-img-top" alt="Obras">
                <div class="card-body">
                    <h3 class="card-title">Obras</h3>
                    <p class="card-text">Disfruta de nuestras producciones únicas, llenas de emoción y creatividad.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center mb-4">
                <img src="https://via.placeholder.com/300x200?text=Cursos" class="card-img-top" alt="Cursos">
                <div class="card-body">
                    <h3 class="card-title">Cursos</h3>
                    <p class="card-text">Aprende sobre actuación, dirección y producción con nuestros cursos especializados.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center mb-4">
                <img src="https://via.placeholder.com/300x200?text=Nosotros" class="card-img-top" alt="Nosotros">
                <div class="card-body">
                    <h3 class="card-title">Nosotros</h3>
                    <p class="card-text">Conoce nuestra historia y el equipo que hace posible cada espectáculo.</p>
                </div>
            </div>
        </div>
    </div>
@endsection