@extends('layouts.app')

@section('title', 'Cursos')

@section('hero-title', 'Cursos de Teatro')

@section('hero-bg', 'https://via.placeholder.com/1920x600?text=Cursos+de+Teatro')

@push('styles')
    <style>
        .card {
            display: flex;
            flex-direction: column;
            height: 100%;
            border: none; /* Sin borde para un aspecto más limpio */
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); /* Sombra más pronunciada */
            transition: transform 0.3s ease, box-shadow 0.3s ease; /* Transición para sombra */
        }

        .card-body {
            flex-grow: 1;
            padding: 20px;
        }

        .card-title {
            font-size: 1.5rem; /* Aumentar el tamaño del título */
            font-weight: 600; /* Hacer el título más destacado */
        }

        .card-text {
            font-size: 1rem;
            color: #555;
        }

        .card:hover {
            transform: translateY(-5px); /* Efecto de elevación */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Sombra más intensa al pasar el ratón */
        }

        .btn-comprar {
            background-color: #007bff; /* Color de fondo moderno */
            color: white; /* Texto blanco */
            border: none; /* Sin borde */
            font-size: 16px;
            padding: 12px;
            border-radius: 25px; /* Bordes redondeados */
            text-transform: uppercase; /* Texto en mayúsculas */
            font-weight: 600;
            transition: all 0.3s ease; /* Efecto de transición */
        }

        .btn-comprar:hover {
            background-color: #0056b3; /* Color de fondo al pasar el ratón */
            transform: scale(1.05); /* Efecto de ampliación */
        }

        .modal-header {
            background-color: #007bff;
            color: white;
        }

        .modal-footer .btn-secondary {
            background-color: #f1f1f1;
        }
        
        .modal-footer .btn-primary {
            background-color: #28a745;
        }

        /* Estilo para el contenedor de cursos */
        .cursos-container {
            margin-top: 30px;
        }
    </style>
@endpush

@section('content')
    <h2 class="text-center mb-5">Cursos Disponibles</h2>
    <div class="cursos-container">
        <div class="row g-4">
            @foreach ($cursos as $curso)
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <img src="{{ asset('storage/' . $curso->imagen) }}" class="card-img-top" alt="{{ $curso->nombre }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $curso->nombre }}</h5>
                            <p class="card-text">{{ Str::limit($curso->descripcion, 100) }}</p>
                            <p class="card-text"><strong>Precio:</strong> ${{ $curso->precio }}</p>
                            <button class="btn btn-comprar w-100 py-3 mt-3" data-bs-toggle="modal" data-bs-target="#modalComprar-{{ $curso->id }}">
                                <i class="fas fa-cart-plus"></i> Comprar Curso
                            </button>
                        </div>
                    </div>
                </div>
                

                <!-- Modal para la compra -->
                <div class="modal fade" id="modalComprar-{{ $curso->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $curso->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="checkout-form-{{ $curso->id }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel-{{ $curso->id }}">Comprar Curso: {{ $curso->nombre }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" id="product_id" value="{{ $curso->id }}">
                                    <input type="hidden" id="product_price" value="{{ $curso->precio }}">
                                    <p><strong>Precio del Curso:</strong> ${{ $curso->precio }}</p>
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Teléfono</label>
                                        <input type="text" id="phone" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Correo Electrónico/Email</label>
                                        <input type="email" id="email" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nombre</label>
                                        <input type="text" id="name" class="form-control" required>
                                    </div>
                                    <div id="wallet_container"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="button" id="checkout-btn" class="btn btn-primary">Pagar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>
        const mp = new MercadoPago("{{ env('MERCADO_PAGO_PUBLIC_KEY') }}");

        document.querySelectorAll('#checkout-btn').forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('form');
                const cursoId = form.querySelector('#product_id').value;
                const telefono = form.querySelector('#phone').value;
                const nombre = form.querySelector('#name').value;
                const email = form.querySelector('#email').value;

                if (!telefono || !email || !nombre) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Por favor, completa todos los campos del formulario.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                    return;
                }

                const orderData = {
                    product: [{
                        id: cursoId,
                        title: '{{ $curso->nombre }}',
                        description: 'Curso de {{ $curso->nombre }}',
                        currency_id: "ARS",
                        quantity: 1,
                        unit_price: parseFloat('{{ $curso->precio }}'),
                    }],
                    name: nombre,
                    phone: telefono,
                    email: email,
                    curso_id: cursoId,
                    telefono: telefono,
                    comprador_email: email,
                    nombre_comprador: nombre
                };
                
                fetch('/create-preference2', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify(orderData)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(preference => {
                    if (preference.error) {
                        throw new Error(preference.error);
                    }
                    window.location.href = preference.init_point;
                })
                .catch(error => console.error('Error al crear la preferencia:', error));
            });
        });
    </script>
@endpush