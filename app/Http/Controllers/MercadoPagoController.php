<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\EntradaMail; // Asegúrate de crear esta clase de correo
use App\Models\Entrada; // Modelo para guardar información del ticket (si es necesario)
use Exception;

class MercadoPagoController extends Controller
{
    public function createPaymentPreference(Request $request)
    {
    Log::info('Creando preferencia de pago');
    $this->authenticate();
    Log::info('Autenticado con éxito');

    // Paso 1: Obtener la información del producto desde la solicitud JSON
    $product = $request->input('product'); // Asumiendo que envías un campo 'product' con los datos

    if (empty($product) || !is_array($product)) {
        return response()->json(['error' => 'Los datos del producto son requeridos.'], 400);
    }

    // Paso 2: Información del comprador
    $payer = [
        "name" => $request->input('name', 'John'),
        "surname" => $request->input('surname', 'Doe'),
        "email" => $request->input('email', 'user@example.com'),
    ];

    // Paso 3: Crear la solicitud de preferencia
    $requestData = $this->createPreferenceRequest($product, $payer);

    // Log para verificar la estructura de los datos enviados
    Log::info('Datos enviados a Mercado Pago:', ['request' => $requestData]);

    // Paso 4: Crear la preferencia con el cliente de preferencia
    $client = new PreferenceClient();

    try {
        $preference = $client->create($requestData);

        Log::info('Preferencia creada con éxito:', ['preference' => $preference]);

        return response()->json([
            'id' => $preference->id,
            'init_point' => $preference->init_point,
        ]);
    } catch (MPApiException $error) {
        Log::error('Error al crear la preferencia en Mercado Pago:', ['error' => $error->getApiResponse()->getContent()]);
        return response()->json([
            'error' => $error->getApiResponse()->getContent(),
        ], 500);
    } catch (Exception $e) {
        Log::error('Error inesperado al crear la preferencia:', ['error' => $e->getMessage()]);
        return response()->json([
            'error' => $e->getMessage(),
        ], 500);
    }
    }

    

    protected function authenticate()
    {
        $mpAccessToken = config('services.mercadopago.token');
        if (!$mpAccessToken) {
            throw new Exception("El token de acceso de Mercado Pago no está configurado.");
        }
        MercadoPagoConfig::setAccessToken($mpAccessToken);
        MercadoPagoConfig::setRuntimeEnviroment(MercadoPagoConfig::LOCAL);
    }

    protected function createPreferenceRequest($items, $payer): array
    {
        $backUrls = [
            'success' => route('mercadopago.success'),
            'failure' => route('mercadopago.failed')
        ];

        return [
            "items" => $items,
            "payer" => $payer,
            "back_urls" => $backUrls,
            "payment_methods" => [
                "installments" => 12,
                "default_installments" => 1
            ],
            "auto_return" => 'approved',
        ];
    }

   public function handlePaymentSuccess(Request $request)
    {
        $paymentId = $request->query('payment_id');
        $payerEmail = $request->query('payer_email'); // Se obtiene del request

        // Guardar información del ticket
        $entrada = new Entrada();
        $entrada->payment_id = $paymentId;
        $entrada->payer_email = $payerEmail;
        $entrada->save();

        // Enviar correo al usuario con el ticket
        Mail::to($payerEmail)->send(new EntradaMail($entrada));

        return view('pagos.success', ['paymentId' => $paymentId]);
    } 
}
