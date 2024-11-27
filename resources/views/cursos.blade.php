@extends('layouts.app')

@section('title', 'Cursos')

@section('hero-title', 'Cursos de Teatro')

@section('hero-bg', 'https://via.placeholder.com/1920x600?text=Cursos+de+Teatro')

@section('content')
    <div class="container my-5">
        <h1 class="text-center">Cursos de Teatro</h1>
        <div class="row g-4 mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Actuación</h5>
                        <p class="card-text">Aprende a expresar emociones y a comunicar con tu cuerpo y voz en el escenario.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Dirección Escénica</h5>
                        <p class="card-text">Desarrolla habilidades para dirigir obras y liderar producciones teatrales.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
