<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\Exposicion;
use App\Models\Expositor;
use App\Models\WhatsappPredefinido;

class WhatsappController extends Controller
{

    public function updateStorePredefinidoView(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $predefinidos = WhatsappPredefinido::select('id', 'texto', 'descripcion', 'file_name', 'file_path')->get()->toArray();
            $exposicion = Exposicion::select(
                'id',
                'nombre',
                'modulo_catalogo',
                'modulo_plano_stand',
                'modulo_elementos_opcionales',
                'modulo_deposito_garantia',
                'modulo_credenciales',
                'modulo_invitaciones',
                'modulo_sala_conferencia',
                'modulo_solicitar_energia',
                'modulo_servicios_optativos',
            )->find($request->id);

            $viewData = [
                'predefinidos' => $predefinidos,
                'exposicion' => $exposicion,
                'expositores' => Expositor::select('id', 'usuario', 'whatsapp')->where('id_exposicion', '=', $request->id)->get()->toArray()
            ];

            return view('App.Whatsapp.updateStorePredefinido', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateStorePredefinido(Request $request)
    {
        try {

            $whatsappPredefinido = null;
            if ($request->idWhatsappPredefinido) {
                $whatsappPredefinido = WhatsappPredefinido::find($request->idWhatsappPredefinido);
            } else {
                $whatsappPredefinido = new WhatsappPredefinido();
            }

            // Validación de la solicitud
            $request->validate([
                'description' => 'required|string|max:255',
                'texto' => 'required|string',
                'file_path' => 'nullable|file|mimes:jpg,png,pdf,doc,docx|max:2048', // ajusta los tipos de archivo según sea necesario
            ]);

            $whatsappPredefinido->descripcion = $request->description;
            $whatsappPredefinido->texto = $request->texto;

            // Manejo del archivo
            if (!$request->hasFile('file_path') && $request->fileDeleted == 'true') {
                $whatsappPredefinido->file_name = null;
                $whatsappPredefinido->file_path = null;
            } else if ($request->hasFile('file_path')) {
                // Generar un nombre de archivo aleatorio
                $fileName = Str::random(10) . '.' . $request->file('file_path')->getClientOriginalExtension();

                // Almacenar el archivo en la carpeta storage/whatsapp
                $filePath = $request->file('file_path')->storeAs('whatsapp', $fileName, 'public');

                // Guardar la ruta y el nombre del archivo en el modelo
                $whatsappPredefinido->file_name = $request->file('file_path')->getClientOriginalName();
                $whatsappPredefinido->file_path = $filePath;
            }

            // Guardar el modelo en la base de datos
            $whatsappPredefinido->save();

            if ($request->idExpositores && count($request->idExpositores)) {
                Log::debug($whatsappPredefinido->file_path);
                Log::debug($whatsappPredefinido->file_name);
                foreach ($request->idExpositores as $key => $value) {
                    $expositor = Expositor::find($value);
                    $this->sendMessage('549'.$expositor->whatsapp, $whatsappPredefinido->descripcion, 'app/public/'.$whatsappPredefinido->file_path, $whatsappPredefinido->file_name);
                }
            }

            // Redireccionar o retornar respuesta
            return redirect()->back()->with('success', 'Mensaje guardado correctamente.');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function sendPredefinido(Request $request)
    {
        try {

            Log::debug($request->all());

            //$this->sendMessage('5492235031406', '123456', 'app\public\whatsapp\2sZXrkTW3G.jpg', 'imagen.jpg');
            //$this->sendMessage('5492235031406', '123456');

            return response()->json(['Message end'], 200);
        } catch (\Exception $ex) {
            Log::debug($ex);
            //throw $th;
            return response()->json(['ex' => $ex], 500);
        }
    }

    public function sendMessage($telefono, $mensaje, $filePath = null, $fileName = null)
    //public function sendMessage(Request $request)
    {
        try {

            
            //$telefono = $request->telefono;
            //$mensaje = $request->mensaje;
            //$filePath = $request->filePath ? $request->filePath : null;
            //$fileName = $request->fileName ? $request->fileName : null;

            if ($filePath && $fileName) {
                Log::debug('sendMessageFile');

                $url = 'http://127.0.0.1:3000/send-file';

                $data = [
                    'number' => $telefono,
                    'path' => storage_path($filePath)
                ];

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);

                // Manejar errores
                if (!$response) {
                    $error = curl_error($ch);
                    Log::debug($error);
                    curl_close($ch);
                    //throw new \Exception("Error en cURL: $error");
                }

                curl_close($ch);

                /*
                // Procesar la respuesta de la API
                $responseData = json_decode($response, true);
                if (!$responseData['success']) {
                    throw new \Exception("Error al enviar el mensaje: " . $responseData['error']);
                }
                */
            }

            Log::debug('sendMessage');
            $data = [
                'number' => $telefono,
                'message' => $mensaje,
            ];

            $url = 'http://127.0.0.1:3000/send-message';
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);

            // Manejar errores
            if ($response === false) {
                $error = curl_error($ch);
                curl_close($ch);
                //throw new \Exception("Error en cURL: $error");
            }

            curl_close($ch);

            /*
            // Procesar la respuesta de la API
            $responseData = json_decode($response, true);
            if (!$responseData['success']) {
                throw new \Exception("Error al enviar el mensaje: " . $responseData['error']);
            }
            */

            return response()->json([], 200);
        } catch (\Exception $ex) {
            Log::debug($ex);
            //throw $ex;
            return response()->json(['ex' => $ex], 500);
        }
    }
}
