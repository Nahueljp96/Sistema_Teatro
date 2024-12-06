@extends('layouts.app')

@section('title', 'Obras de Teatro')

@section('hero-bg', 'path/to/your/custom-image.jpg')
@section('hero-title', 'Explora las Mejores Obras de Teatro')

@push('styles')
    <style>
        .card {
            display: flex;
            flex-direction: column;
            height: 100%;
            transition: transform 0.3s ease;
        }

        .card-body {
            flex-grow: 1;
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
                        <p class="card-text">{{ Str::limit($obra->precio, 100) }}</p>
                        <p><strong>Asientos disponibles:</strong> {{ $obra->asientos_disponibles }}</p>
                        <button class="btn btn-comprar w-100" data-bs-toggle="modal" data-bs-target="#modalComprar-{{ $obra->id }}">
                            Comprar Entrada
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal para la compra -->
            <div class="modal fade" id="modalComprar-{{ $obra->id }}" tabindex="-1" aria-labelledby="modalLabel-{{ $obra->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="checkout-form-{{ $obra->id }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel-{{ $obra->id }}">Comprar Entrada: {{ $obra->titulo }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="product_id" value="{{ $obra->id }}">
                                <input type="hidden" id="product_price" value="{{ $obra->precio }}">
                                <p><strong>Precio de la Entrada:</strong> ${{ $obra->precio }}</p>
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
                                <!-- Contenedor para el wallet de Mercado Pago -->
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
@endsection

@push('scripts')
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script>
        const mp = new MercadoPago("{{ env('MERCADO_PAGO_PUBLIC_KEY') }}");

        // Inicializar el wallet de Mercado Pago cuando se abra el modal
        document.querySelectorAll('#checkout-btn').forEach(button => {
         button.addEventListener('click', function () {
            const form = this.closest('form'); //datos que enviamos al back!!
            const obraId = form.querySelector('#product_id').value;
            const telefono = form.querySelector('#phone').value;
            const nombre = form.querySelector('#name').value;
            const email = form.querySelector('#email').value;

            // Validación de los campos
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
                    id: obraId,
                    title: '{{ $obra->titulo }}',
                    description: 'Entrada para la obra {{ $obra->titulo }}',
                    currency_id: "ARS",
                    quantity: 1, // Siempre 1 entrada
                    unit_price: parseFloat('{{ $obra->precio }}'),
                }],
                name: nombre,
                phone: telefono,
                email: email,
                obra_id: obraId,
                telefono: telefono,
                comprador_email: email,
                nombre_comprador: nombre

            };
            //pruebaaaaaa (no anda )
            // fetch('/guardar-entrada', {
            //     method: 'POST',
            //     headers: {
            //         'Content-Type': 'application/json',
            //         'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value
            //     },
            //     body: JSON.stringify({
            //         obra_id: obraId,
            //         telefono: telefono,
            //         comprador_email: email,
            //         nombre_comprador: nombre
            //     })
            // })
            // .then(response => response.json())
            // .then(data => {
            //     console.log('Respuesta:', data);
            // })
            // .catch(error => console.error('Error al guardar la entrada:', error));

            //fin prueba
            // Enviar los datos al backend para crear la preferencia
            fetch('/create-preference', {
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
                console.log("respuesta", response);
                
                return response.json();
            })
            
            .then(preference => {
                if (preference.error) {
                    throw new Error(preference.error);
                }

                // Redirige al usuario a la URL generada por Mercado Pago
                window.location.href = preference.init_point;
            })
            .catch(error => console.error('Error al crear la preferencia:', error));
        });
    });
    </script>
@endpush
