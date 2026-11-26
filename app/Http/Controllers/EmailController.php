<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\EmailPredefinido;
use App\Models\Exposicion;
use App\Models\Expositor;

use App\Mail\EnviarEmail;
use App\Mail\EnviarEmailPredefinido;
use App\Models\Stand;

class EmailController extends Controller
{
    public function sendUserPass(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $expositor = Expositor::find($request->id);

            if (!str_contains($expositor->usuario, '@')) {
                $messageBag->add('success', 'El nombre de usuario no es un email');
                return back();
            }

            $details = [
                'title' => 'Bienvenido Expositor',
                'body' => 'Se encuentra activo tu usuario expositor. Para ingresar tu usuario es ' . $expositor->usuario . ' y tu contraseña es ' . $expositor->password
            ];

            Mail::to($expositor->usuario)->send(new EnviarEmail($details));

            $expositor->usuario_password_enviado = true;
            $expositor->save();

            $messageBag->add('success', 'Contraseña enviada al expositor ' . $expositor->usuario . ' con éxito');

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateStorePredefinidoView(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $predefinidos = EmailPredefinido::select('id', 'title', 'texto', 'descripcion', 'file_name', 'file_path')->get()->toArray();

            $predefinido = null;
            if ($request->email) {
                $predefinido = EmailPredefinido::find($request->email);
            }


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

            if ($predefinido) {
                $expositores = Expositor::leftJoin('email_predefinido_to_expositor', function ($join) use ($predefinido) {
                        $join->on('email_predefinido_to_expositor.id_expositor', '=', 'expositor.id')
                            ->where('email_predefinido_to_expositor.id_email_predefinido', '=', $predefinido->id);
                    })
                    ->leftJoin('empresa', 'empresa.id', '=', 'expositor.id_empresa')
                    ->select(
                        'expositor.id',
                        'expositor.usuario',
                        'expositor.whatsapp',
                        DB::raw('count(email_predefinido_to_expositor.id) as email_count'),
                        'empresa.razon_social',
                        'expositor.created_at',
                        'expositor.acepto_terminos_condiciones',
                        'expositor.pertenece_etif',
                        DB::raw('MAX(email_predefinido_to_expositor.created_at) as ultimo_email'),
                    )
                    ->where('id_exposicion', '=', $request->id)
                    ->groupBy('expositor.id', 'expositor.usuario', 'expositor.whatsapp')
                    ->get()
                    ->toArray();
            } else {
                $expositores = Expositor::leftJoin('empresa', 'empresa.id', '=', 'expositor.id_empresa')
                    ->select(
                        'expositor.id',
                        'expositor.usuario',
                        'expositor.whatsapp',
                        DB::raw('"0" as email_count'),
                        'empresa.razon_social',
                        'expositor.created_at',
                        'expositor.acepto_terminos_condiciones',
                        'expositor.pertenece_etif',
                        DB::raw('"" as ultimo_email'),
                        //DB::raw('MAX(email_predefinido_to_expositor.created_at) as ultimo_email'),
                    )
                    ->where('id_exposicion', '=', $request->id)
                    ->groupBy('expositor.id', 'expositor.usuario', 'expositor.whatsapp')
                    ->get()
                    ->toArray();
            }

            foreach ($expositores as $keyExpositor => $expositor) {
                $stands = Stand::where('id_expositor', '=', $expositor['id'])->get()->toArray();
                $standString = '';

                for ($i = 0; $i < count($stands); $i++) {
                    $stand = $stands[$i];
                    $standString .= $stand['numero'];
                    if ($i < (count($stands) - 1)) {
                        $standString .= ' - ';
                    }
                }

                $expositores[$keyExpositor]['stands'] = $standString;
            }

            $expositorSinEmail = Expositor::leftJoin('email_predefinido_to_expositor','email_predefinido_to_expositor.id_expositor','=','expositor.id')
                ->select('expositor.id')
                ->where('id_exposicion','=',$exposicion['id'])
                ->whereNull('email_predefinido_to_expositor.id')
                ->get();

            //dd($expositorSinEmail);

            $viewData = [
                'predefinidos' => $predefinidos,
                'exposicion' => $exposicion,
                'expositores' => $expositores,
                'predefinido' => $predefinido,
                'expositorSinEmail' => $expositorSinEmail
            ];

            return view('App.Email.updateStorePredefinido', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateStorePredefinido(Request $request)
    {
        try {

            $emailPredefinido = null;
            if ($request->idEmailPredefinido) {
                $emailPredefinido = EmailPredefinido::find($request->idEmailPredefinido);
            } else {
                $emailPredefinido = new EmailPredefinido();
            }

            // Validación de la solicitud
            $request->validate([
                'description' => 'required|string|max:255',
                'texto' => 'required|string',
                'file_path' => 'nullable|file|mimes:jpg,png,pdf,doc,docx|max:2048', // ajusta los tipos de archivo según sea necesario
            ]);

            $emailPredefinido->title = $request->title;
            $emailPredefinido->descripcion = $request->description;
            $emailPredefinido->texto = $request->texto;

            // Manejo del archivo
            if (!$request->hasFile('file_path') && $request->fileDeleted == 'true') {
                $emailPredefinido->file_name = null;
                $emailPredefinido->file_path = null;
            } else if ($request->hasFile('file_path')) {
                // Generar un nombre de archivo aleatorio
                $fileName = Str::random(10) . '.' . $request->file('file_path')->getClientOriginalExtension();

                // Almacenar el archivo en la carpeta storage/whatsapp
                $filePath = $request->file('file_path')->storeAs('email', $fileName, 'public');

                // Guardar la ruta y el nombre del archivo en el modelo
                $emailPredefinido->file_name = $request->file('file_path')->getClientOriginalName();
                $emailPredefinido->file_path = $filePath;
            }

            // Guardar el modelo en la base de datos
            $emailPredefinido->save();

            if ($request->idExpositores && count($request->idExpositores)) {
                //Log::debug($emailPredefinido->file_path);
                //Log::debug($emailPredefinido->file_name);
                foreach ($request->idExpositores as $key => $value) {
                    $expositor = Expositor::find($value);

                    $body = $emailPredefinido->texto;
                    $title = $emailPredefinido->title;

                    if ($emailPredefinido->descripcion == 'Envio de Usuario y Contraseña') {
                        $body = str_replace('#usuario#', $expositor->usuario, $body);
                        $body = str_replace('#clave#', $expositor->password, $body);
                    }

                    $details = [
                        'title' => $title,
                        'body' => $body,
                        'idExposicion' => $expositor['id_exposicion']
                    ];

                    //return new EnviarEmailPredefinido($details);                    

                    //return view('emails.templatePredefinido', ['details' => $details]);

                    $mail = new EnviarEmailPredefinido($details);

                    if ($emailPredefinido->file_path) {
                        // Ruta completa al archivo
                        $filePath = storage_path('app/public/' . $emailPredefinido->file_path);

                        // Determinar el tipo MIME del archivo
                        $mimeType = mime_content_type($filePath);

                        // Adjuntar el archivo con el nombre y MIME dinámico
                        $mail->attach($filePath, [
                            'as' => $emailPredefinido->file_name, // Nombre del archivo
                            'mime' => $mimeType, // Tipo MIME dinámico
                        ]);
                    }

                    Mail::to($expositor->usuario)->send($mail->from('envase.etif2025@gmail.com', 'Novedades'));

                    $newEmailPredefinidoToExpositor = new \App\Models\EmailPredefinidoToExpositor();
                    $newEmailPredefinidoToExpositor->id_email_predefinido = $emailPredefinido->id;
                    $newEmailPredefinidoToExpositor->id_expositor = $expositor->id;
                    $newEmailPredefinidoToExpositor->save();
                }
            }

            // Redireccionar o retornar respuesta
            return redirect()->back()->with('success', 'Mensaje guardado correctamente.');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
