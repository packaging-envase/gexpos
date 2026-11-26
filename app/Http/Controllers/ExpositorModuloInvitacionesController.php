<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use App\Mail\EnviarEmailPredefinido;
use App\Models\Exposicion;
use \App\Models\ExpositorModuloInvitacion;

class ExpositorModuloInvitacionesController extends Controller
{
    public function index(Request $request)
    {
        try {
            $idExposicion = Auth::guard('expositor')->user()->id_exposicion;
            $idExpositor = Auth::guard('expositor')->user()->id;

            $metrosCuadradosTotal = \App\Models\Stand::where('id_expositor', '=', Auth::guard('expositor')->user()->id)
                ->get()
                ->sum(function ($stand) {
                    return (int) $stand->metros_cuadrados; // Convertir el string a entero
                });

            $metrosCuadradosTotal = $metrosCuadradosTotal ? $metrosCuadradosTotal : 1;

            $viewData = [
                'exposicion' => \App\Models\Exposicion::find($idExposicion),
                'invitacion' => \App\Models\ModuloInvitacion::where('id_exposicion', '=', $idExposicion)->first(),
                'empresa' => \App\Models\Empresa::find(Auth::guard('expositor')->user()->id_empresa),
                'maxSolicitud' => $metrosCuadradosTotal * 6,
                'expositorModuloInvitacion' => ExpositorModuloInvitacion::where('id_expositor','=',$idExpositor)->first()
            ];

            return view('App.ModuloExpositor.invitaciones', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateStore(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $idExpositor = Auth::guard('expositor')->user()->id;
            $idExposicion = Auth::guard('expositor')->user()->id_exposicion;
            $exposicion = Exposicion::find($idExposicion);

            $expositorModuloInvitacion =  ExpositorModuloInvitacion::where('id_expositor','=',$idExpositor)->first();

            if (!$expositorModuloInvitacion) {
                $expositorModuloInvitacion = new ExpositorModuloInvitacion();
                $expositorModuloInvitacion->id_expositor = $idExpositor;
            }

            if (Auth::guard()->check() || Auth::guard('expositor')->check()) {
                $expositorModuloInvitacion->updated_at_expositor = date('Y-m-d');
            }

            $expositorModuloInvitacion->cantidad = $request->cantidad;
            $expositorModuloInvitacion->save();

            $expositor = \App\Models\Expositor::find(Auth::guard('expositor')->user()->id);
            $stands = \App\Models\Stand::where('id_expositor', '=', $expositor['id'])->get();
            
            if (count($stands)) {
                $stands = $stands->toArray();
            } else {
                $stands = [];
            }

            $standsString = implode(', ', array_column($stands, 'numero'));
            $empresa = \App\Models\Empresa::find($expositor['id_empresa']);

            //$standsString = '999';

            $empresaString = '';
            if (count($stands) && $stands[0]['nombre_exposicion']) {
                $empresaString = $empresa[$stands[0]['nombre_exposicion']];
            } else {
                $empresaString = $empresa['razon_social'];
            }

            $details = [
                'idExposicion' => $idExposicion,
                'title' => 'Solicitud de invitaciones',
                'body' => "
                <strong>" . $empresaString . "</strong><br/>
                " . ($standsString ? "Stands: " . $standsString : '') . "
                <p>Gracias por completar el formulario de solicitud de invitaciones para ".$exposicion['nombre'].".</p>
                
                <p>A continuación, se detallan los datos ingresados:</p>

                <p><strong>Invitaciones solicitadas: </strong>".$request->cantidad."</p>
        
                <p>Lo/a esperamos en el evento.</p>

                <p>
                    No responda a este email. Contáctese a través de los canales habituales: <br/>
                    ".nl2br($exposicion['contacto_emails'])."
                </p>
            "
            ];

            $mail = new EnviarEmailPredefinido($details);
            Mail::to($expositor->usuario)->send($mail->from('envase.etif2025@gmail.com', $exposicion['nombre']));
            //Mail::to('marcelo.ismael.mdp@gmail.com')->send($mail->from('envase.etif2025@gmail.com', 'Novedades'));
            Mail::to('admin.ventas@envase.org')->send($mail->from('envase.etif2025@gmail.com', $exposicion['nombre']));

            $messageBag->add('success', 'Credenciales solicitadas con éxito!');

            return back();
            
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
