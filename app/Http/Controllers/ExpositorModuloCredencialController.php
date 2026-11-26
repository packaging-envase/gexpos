<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Mail\EnviarEmailPredefinido;

use App\Models\CredencialTipoExposicion;
use App\Models\CredencialTipoExpositor;
use App\Models\CredencialDetalle;
use App\Models\CredencialTipo;
use App\Models\Exposicion;
use App\Models\Stand;

class ExpositorModuloCredencialController extends Controller
{

    public function index(Request $request)
    {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $idExpositor = Auth::guard('expositor')->user()->id;
            $idExposicion = Auth::guard('expositor')->user()->id_exposicion;

            $credencialTipoExposicion = CredencialTipoExposicion::with('credencialTipo')
                ->select(
                    'credencial_tipo_exposicion.*'
                )
                ->where('id_exposicion', '=', $idExposicion)
                ->where('activa', '=', true)
                ->get();

            $credencialesSolicitadas = CredencialDetalle::leftJoin('credencial_tipo', 'credencial_tipo.id', '=', 'credencial_detalle.id_credencial_tipo')
                ->select(
                    'credencial_detalle.id',
                    'credencial_detalle.nombre',
                    'credencial_detalle.apellido',
                    'credencial_detalle.empresa',
                    'credencial_detalle.cargo',
                    'credencial_detalle.email',
                    'credencial_detalle.telefono',
                    'credencial_tipo.descripcion'
                )
                ->where('id_expositor', '=', $idExpositor)
                ->orderBy('credencial_tipo.descripcion')
                ->get()
                ->toArray();

            $credencialesPedidasQuantity = count($credencialesSolicitadas);

            $credencialesSolicitadasFinal = [];

            foreach ($credencialTipoExposicion as $key => $value) {
                $credencialesSolicitadasFinal[$credencialTipoExposicion[$key]['credencialTipo']['descripcion']] = [];
            }

            foreach ($credencialesSolicitadas as $key => $value) {
                if (!isset($credencialesSolicitadasFinal[$value['descripcion']])) {
                    $credencialesSolicitadasFinal[$value['descripcion']] = [];
                }
                $credencialesSolicitadasFinal[$value['descripcion']][] = $credencialesSolicitadas[$key];
            }

            //dd($credencialesSolicitadasFinal);

            $credencial = null;
            if ($request->idCredencial) {
                $credencial = CredencialDetalle::find($request->idCredencial);
                if ($credencial['id_expositor'] != $idExpositor) {
                    return response()->json(['message' => 'Unauthorised'], 401);
                }
            }

            
            // Calculo credencials por m2 por stand
            $stands = Stand::where('id_expositor','=',$idExpositor)->get();
            $maximo = 0;
            $metros = 0;
            foreach ($stands as $key => $stand) {
                $metros += floatval($stand->metros_cuadrados);
            }

            foreach ($credencialTipoExposicion as $key => $value) {
                $credencialTipoExposicion[$key]->permitido = floor($metros / $value->metros);

                if ($credencialTipoExposicion[$key]->permitido > ($value->maximo * count($stands))) {
                    $credencialTipoExposicion[$key]->permitido = $value->maximo;
                }
            }

            //dd($credencialTipoExposicion);

            $viewData = [
                'exposicion' => \App\Models\Exposicion::find($idExposicion),
                'credencial_tipo_exposicion' => $credencialTipoExposicion,
                'empresa' => \App\Models\Empresa::find(Auth::guard('expositor')->user()->id_empresa),
                "credencial_vencimiento" => \App\Models\Variables::where('id_exposicion', '=', $idExposicion)->where('descripcion', '=', 'credencial_vencimiento')->first(),
                "credencialesSolicitadas" => $credencialesSolicitadasFinal,
                "credencialesPedidasQuantity" => $credencialesPedidasQuantity,
                "credencial" => $credencial
            ];

            //dd($viewData);

            return view('App.ModuloExpositor.credencial', ['viewData' => $viewData]);

            //$messageBag->add('success', '');
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

            //dd($request->all());

            if ($request->idCredencial) {
                $credencialPedida = CredencialDetalle::find($request->idCredencial);
                $credencialPedida->id_expositor = $idExpositor;
                $credencialPedida->id_credencial_tipo = $request->id_credencial_tipo;

                $credencialPedida->nombre = $request->nombre;
                $credencialPedida->apellido = $request->apellido;
                $credencialPedida->empresa = $request->empresa;
                $credencialPedida->cargo = $request->cargo;
                $credencialPedida->email = $request->email;
                $credencialPedida->telefono = $request->telefono;
                $credencialPedida->save();
                if (Auth::guard()->check() || Auth::guard('expositor')->check()) {
                    $credencialPedida->updated_at_expositor = date('Y-m-d');
                    $credencialPedida->save();
                }

                $messageBag->add('success', 'Credencial actualizada con éxito');

                return redirect()->route('expositor.modulo.credencial.index');
            } else {
                foreach ($request->nombre as $key => $value) {

                    $credencialPedida = new CredencialDetalle();
                    $credencialPedida->id_expositor = $idExpositor;
                    $credencialPedida->id_credencial_tipo = $request->id_credencial_tipo;

                    $credencialPedida->nombre = $request->nombre[$key];
                    $credencialPedida->apellido = $request->apellido[$key];
                    $credencialPedida->empresa = $request->empresa[$key];
                    $credencialPedida->cargo = $request->cargo[$key];
                    $credencialPedida->email = $request->email[$key];
                    $credencialPedida->telefono = $request->telefono[$key];
                    $credencialPedida->save();

                    if (Auth::guard()->check() || Auth::guard('expositor')->check()) {
                        $credencialPedida->updated_at_expositor = date('Y-m-d');
                        $credencialPedida->save();
                    }
                }
                $messageBag->add('success', 'Credenciales solicitadas con éxito');
            }

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

            $elementosString = '';
            foreach ($request->nombre as $key => $value) {
                $elementosString .= '<ul>';

                $elementosString .= '<li><strong>Nombre: </strong> ' . $request->nombre[$key] . '</li>';
                $elementosString .= '<li><strong>Empresa: </strong> ' . $request->empresa[$key] . '</li>';
                $elementosString .= '<li><strong>Cargo: </strong> ' . $request->cargo[$key] . '</li>';
                $elementosString .= '<li><strong>Email: </strong> ' . $request->email[$key] . '</li>';
                $elementosString .= '<li><strong>Teléfono: </strong> ' . $request->telefono[$key] . '</li>';

                $elementosString .= '</ul>';
            }

            $credencialTipo = \App\Models\CredencialTipo::find($request->id_credencial_tipo);

            $details = [
                'idExposicion' => $idExposicion,
                'title' => 'Solicitud de credenciales',
                'body' => "
                <strong>" . $empresaString . "</strong><br/>
                " . ($standsString ? "Stands: " . $standsString : '') . "
                <p>Gracias por completar el formulario de credenciales para ".$exposicion['nombre'].".</p>
                
                <p>A continuación, se detallan los datos ingresados:</p>

                <strong>Credencial solicitada: </strong>" . $credencialTipo['descripcion'] . "
        
                " . $elementosString . "
        
                <p>Lo/a esperamos en el evento.</p>

                <p>
                    No responda a este email. Contáctese a través de los canales habituales: <br/>
                    ".nl2br($exposicion['contacto_emails'])."
                </p>
            "
            ];

            $mail = new EnviarEmailPredefinido($details);
            //Mail::to($expositor->usuario)->send($mail->from('envaseiae.contacto@gmail.com', 'Novedades'));
            //Mail::to('marcelo.ismael.mdp@gmail.com')->send($mail->from('envaseiae.contacto@gmail.com', 'Novedades'));
            //Mail::to('hrauch@gmail.com')->send($mail->from('envaseiae.contacto@gmail.com', 'Novedades'));
            //Mail::to('admin.ventas@envase.org')->send($mail->from('envaseiae.contacto@gmail.com', 'Novedades'));

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function delete(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $idExpositor = Auth::guard('expositor')->user()->id;

            $credencial = CredencialDetalle::find($request->idCredencial);
            if ($credencial['id_expositor'] != $idExpositor) {
                return response()->json(['message' => 'Unauthorised'], 401);
            }

            $credencial->delete();

            $messageBag->add('success', 'Solicitud de credencial eliminada con éxito');

            return back();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    
}
