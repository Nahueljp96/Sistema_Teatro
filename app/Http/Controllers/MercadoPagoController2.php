<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\CursoVentaMail; // Asegúrate de crear esta clase de correo
use App\Models\CursoVenta; // Modelo para guardar información del ticket (si es necesario)
use Exception;

use Symfony\Component\Console\Input\Input;

use function Laravel\Prompts\error;

//este es para procesar los pagos de los cursos!!
class MercadoPagoController2 extends Controller
{
    
    public function createPaymentPreference2(Request $request)

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
            $cursoVenta = new CursoVenta();
            $cursoVenta->curso_id = $product[0]['id']; // Ajusta esto según la estructura del producto (ID del curso)
            $cursoVenta->telefono = $payer['phone']['number'] ?? null; // Teléfono del comprador (opcional)
            $cursoVenta->comprador_email = $payer['email'] ?? null; // Email del comprador
            $cursoVenta->nombre_comprador = $payer['name'] ?? null; // Nombre del comprador
            $cursoVenta->preference_id = $preference->id ?? null; // ID único de preferencia (Mercado Pago)
            $cursoVenta->estado_pago = 'pendiente'; // Estado inicial del pago
            $cursoVenta->cantidad = $product[0]['cantidad'] ?? 1; // Cantidad comprada (default: 1)
            $cursoVenta->save(); // Guarda el registro en la base de datos

            
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
            "excluded_payment_types" => [
                'id' => 'ticket',
                'id' => 'credit_card',
                ] ,
            "auto_return" => 'approved',
        ];
    }

    public function handlePaymentSuccess2(Request $request)
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
            // Buscar la venta asociada por el preference_id
            $cursoVenta = CursoVenta::where('preference_id', $preferenceId)->first();
             
            if (!$cursoVenta) {
                Log::error('No se encontró la venta asociada al preference_id:', ['preference_id' => $preferenceId]);
                return response()->json(['error' => 'Cursoventa no encontrado.'], 404);
            }

            // Actualizar el estado de la entrada
            if ($status === 'approved') {
                $cursoVenta->estado_pago = 'pagado';
                $cursoVenta->preference_id = $paymentId; // Asociar el payment_id al registro de entrada
                $cursoVenta->save();

                Log::info('Curso actualizado exitosamente:', ['cursoVenta' => $cursoVenta]);
            } else {
                Log::warning('El pago no fue aprobado:', ['status' => $status]);
            }

            // Enviar correo al comprador
            try {
                Mail::to($cursoVenta->comprador_email)->send(new CursoVentaMail($cursoVenta));
                Log::info('Correo enviado exitosamente a:', ['email' => $cursoVenta->comprador_email]);
            } catch (Exception $e) {
                Log::error('Error enviando email:', ['error' => $e->getMessage()]);
            }

            // Redirigir a la página de inicio
            return redirect('/');

        } catch (Exception $e) {
            Log::error('Error procesando el pago:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Ocurrió un error al procesar el pago.'], 500);
        }
    }


}