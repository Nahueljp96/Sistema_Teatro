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
use Symfony\Component\Console\Input\Input;

class MercadoPagoController extends Controller
{
    public function createPaymentPreference(Request $request)

    {   
        
        // Guardar la entrada (Ejemplo)
        $entrada = new Entrada();
        $entrada->obra_id = $request->input('obra_id');
        $entrada->telefono = $request->input('telefono');
        $entrada->comprador_email = $request->input('comprador_email');
        $entrada->nombre_comprador = $request->input('nombre_comprador');
        $entrada->save();
        
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
    public function guardarEntrada(Request $request)
    { //prueba
        // Validar los datos
        $validated = $request->validate([
            'obra_id' => 'required|exists:obras,id',
            'telefono' => 'required|string',
            'comprador_email' => 'required|email',
            'nombre_comprador' => 'required|string',
        ]);

        // Guardar la entrada (Ejemplo)
        $entrada = new Entrada();
        $entrada->obra_id = $validated['obra_id'];
        $entrada->telefono = $validated['telefono'];
        $entrada->comprador_email = $validated['comprador_email'];
        $entrada->nombre_comprador = $validated['nombre_comprador'];
        $entrada->save();

        return response()->json(['message' => 'Entrada guardada con éxito']);
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
            "excluded_payment_types" => [
                'id' => 'ticket',
                'id' => 'credit_card',
                ] ,
            "auto_return" => 'approved',
        ];
    }
  /* public function handlePaymentSuccess(Request $request)
    {
        $paymentId = $request->query('payment_id');
        $payerEmail = $request->query('payer_email'); // Se obtiene del request
        $cantidad = $request->query('cantidad');
        // Guardar información del ticket
        $entrada = new Entrada();
        $entrada->obra_id = $paymentId;
        $entrada->comprador_email = $payerEmail;
        $entrada->cantidad = $cantidad;
        $entrada->save();
        // Enviar correo al usuario con el ticket
        Mail::to($payerEmail)->send(new EntradaMail($entrada));
        return view('pagos.success', ['paymentId' => $paymentId]);
    } */

    public function handlePaymentSuccess(Request $request)
        {   
            dd($request->all());
            $paymentId = $request->query('payment_id');
            $payerEmail = $request->query('payer_email'); 
            $obraId = $request->query('obra_id'); 
            


            Log::info('Guardando entrada:', [
                'obra_id' => $obraId,
                'comprador_email' => $payerEmail,
                
            ]);
            
            // Guardar la entrada
            $entrada = new Entrada();
            $entrada->obra_id = $obraId;
            $entrada->comprador_email = $payerEmail;

            $entrada->save();

            // Enviar el correo
            try {
                Mail::to($payerEmail)->send(new EntradaMail($entrada));
            } catch (Exception $e) {
                Log::error('Error enviando email:', ['error' => $e->getMessage()]);
            }

            return view('pagos.success', ['paymentId' => $paymentId]);
        }


                //a testear luego:
          /*   */

}