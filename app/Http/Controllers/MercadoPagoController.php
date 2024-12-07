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
            "phone" => [
                    "number" => $request->input('phone', '221331265'),
                    "area_code" => $request->input('area_code', '11', '221') // Opcional: puedes agregar un campo en el formulario o usar un valor por defecto
                ]
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
            //aca modificamos TEST 
            
            // Guardar los datos en la base de datos
            $entrada = new Entrada();
            $entrada->obra_id = $product[0]['id']; // Ajusta esto según la estructura del producto
            $entrada->telefono = $payer['phone']['number'] ?? null;
            $entrada->comprador_email = $payer['email'] ?? null;
            $entrada->nombre_comprador = $payer['name'] ?? null;
            $entrada->preference_id = $preference->id ?? null; // Guardar el preference_id
            $entrada->estado_pago = 'pendiente'; // Estado inicial hasta que llegue la confirmación
            $entrada->save();
            
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
        
      
        // Log::info("Intentamos guardar:", $request->all()); // Verifica los datos enviados en la solicitud
        // // Guardar la entrada
        // $entrada = new Entrada();
        // $entrada->obra_id = $request->input('product.0.id'); // Usar el ID de la obra desde los datos del producto
        // $entrada->telefono = $request->input('payer.phone'); // Asegúrate de que estos datos lleguen en la solicitud
        // $entrada->comprador_email = $request->input('payer.email'); // Obtener el email del comprador
        // $entrada->nombre_comprador = $request->input('payer.name'); // Obtener el nombre del comprador
        // $entrada->preference_id = $preference->id; // Asociar el preference_id generado por Mercado Pago
        // $entrada->save();


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

    // public function handlePaymentSuccess(Request $request)
    //     {   
    //         dd($request->all());
    //         // $paymentId = $request->query('payment_id');
    //         // $payerEmail = $request->query('payer_email'); 
    //         // $obraId = $request->query('obra_id'); 
            


    //         Log::info('Guardando entrada:', [
    //             'obra_id' => $obraId,
    //             'comprador_email' => $payerEmail,
                
    //         ]);
            
    //         // Guardar la entrada
    //         $entrada = new Entrada();
    //         $entrada->obra_id = $request->input('obra_id');
    //         $entrada->telefono = $request->input('telefono');
    //         $entrada->comprador_email = $request->input('comprador_email');
    //         $entrada->nombre_comprador = $request->input('nombre_comprador');
    //         $entrada->save();

    //         // Enviar el correo
    //         try {
    //             Mail::to($payerEmail)->send(new EntradaMail($entrada));
    //         } catch (Exception $e) {
    //             Log::error('Error enviando email:', ['error' => $e->getMessage()]);
    //         }

    //         return view('pagos.success', ['paymentId' => $paymentId]);
    // }
   // este deberia funcionar jaja
    public function handlePaymentSuccess(Request $request)
{   
    
    // Verificar datos recibidos desde Mercado Pago
    $paymentId = $request->query('payment_id');
    $status = $request->query('status');
    $preferenceId = $request->query('preference_id');

    // Log de los datos recibidos
    Log::info('Datos recibidos de Mercado Pago:', [
        'payment_id' => $paymentId,
        'status' => $status,
        'preference_id' => $preferenceId,
    ]);

    try {
        // Buscar la entrada asociada por el preference_id
        $entrada = Entrada::where('preference_id', $preferenceId)->first();

        if (!$entrada) {
            Log::error('No se encontró la entrada asociada al preference_id:', ['preference_id' => $preferenceId]);
            return response()->json(['error' => 'Entrada no encontrada.'], 404);
        }

        // Actualizar el estado de la entrada
        if ($status === 'approved') {
            $entrada->estado_pago = 'pagado';
            $entrada->preference_id = $paymentId; // Asociar el payment_id al registro de entrada
            $entrada->save();

            Log::info('Entrada actualizada exitosamente:', ['entrada' => $entrada]);
        } else {
            Log::warning('El pago no fue aprobado:', ['status' => $status]);
        }

        // Enviar correo al comprador
        try {
            Mail::to($entrada->comprador_email)->send(new EntradaMail($entrada));
            Log::info('Correo enviado exitosamente a:', ['email' => $entrada->comprador_email]);
        } catch (Exception $e) {
            Log::error('Error enviando email:', ['error' => $e->getMessage()]);
        }

        // Devolver vista de éxito
        return view('pagos.success', [
            'paymentId' => $paymentId,
            'status' => $status,
        ]);

    } catch (Exception $e) {
        Log::error('Error procesando el pago:', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'Ocurrió un error al procesar el pago.'], 500);
    }
}


}