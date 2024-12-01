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
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Cantidad de Entradas</label>
                                    <input type="number" id="quantity" class="form-control" min="1" max="{{ $obra->asientos_disponibles }}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Teléfono</label>
                                    <input type="text" id="phone" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Dirección</label>
                                    <input type="text" id="address" class="form-control" required>
                                </div>
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

        document.querySelectorAll('#checkout-btn').forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('form');
                const obraId = form.querySelector('#product_id').value;
                const cantidad = parseInt(form.querySelector('#quantity').value, 10);
                const telefono = form.querySelector('#phone').value;
                const direccion = form.querySelector('#address').value;

                if (!cantidad || !telefono || !direccion) {
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
                        quantity: cantidad,
                        unit_price: parseFloat('{{ $obra->precio }}'),
                    }],
                    phone: telefono,
                    address: direccion,
                };

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
                        return response.json();
                    })
                    .then(preference => {
                        if (preference.error) {
                            throw new Error(preference.error);
                        }
                        mp.checkout({
                            preference: {
                                id: preference.id
                            },
                            autoOpen: true
                        });
                    })
                    .catch(error => console.error('Error al crear la preferencia:', error));
            });
        });
    </script>
@endpush
