<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Mail\EnviarEmailPredefinido;
use App\Models\Exposicion;
use App\Models\Expositor;
use App\Models\ExpositorModuloSalaConferencia;
use App\Models\ModuloSalaConferencia;

class ExpositorModuloSalaConferenciaController extends Controller {

    public function index(Request $request) {

        try {

            if (Auth::guard()->check()) {
                $idExposicion = $request->idExposicion;
                $idExpositor = $request->idExpositor;
            } else {
                $idExposicion = Auth::guard('expositor')->user()->id_exposicion;
                $idExpositor = Auth::guard('expositor')->user()->id;
            }
            
            $item = null;

            $salaConferencia = \App\Models\ModuloSalaConferencia::
                where('id_exposicion','=',$idExposicion)
                ->where('status','=','libre')
                ->orderBy('fecha','asc');

            if (Expositor::find($idExpositor)->pertenece_etif) {
                $salaConferencia = $salaConferencia->where('etif','=',true);
            } else {
                $salaConferencia = $salaConferencia->where('etif','=',false);
            }
            $salaConferencia = $salaConferencia->get()->toArray();

            $salaConferenciaGroup = [];

            foreach ($salaConferencia as $key => $value) {
                if (!isset($salaConferenciaGroup[$value['sala']])) {
                    $salaConferenciaGroup[$value['sala']] = [];
                }
                $salaConferenciaGroup[$value['sala']][] = $value;
            }

            if ($request->id) {
                $item = ExpositorModuloSalaConferencia::
                    with(['moduloSalaConferencia', 'expositorModuloSalaConferenciaDisertante'])
                    ->where('id','=',$request->id)
                    ->first();
            }

            $viewData = [
                'exposicion' => \App\Models\Exposicion::find($idExposicion),
                'sala_conferencia_pedidos' => ExpositorModuloSalaConferencia::
                    whereHas('moduloSalaConferencia', function ($query) {
                        $query->whereNull('deleted_at');
                    })
                    ->where('id_expositor','=',$idExpositor)
                    ->get(),
                'sala_conferencia' => $salaConferenciaGroup,
                'sala_conferencia_texto' => \App\Models\ModuloSalaConferenciaTexto::where('id_exposicion','=',$idExposicion)->first(),
                'item' => $item,
                'empresa' => Auth::guard()->check() ? null : \App\Models\Empresa::find(Auth::guard('expositor')->user()->id_empresa),
                'idExpositor' => $idExpositor
            ];

            return view('App.ModuloExpositor.salaConferencia', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }        
    }

    public function updateStore(Request $request) {

        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            if ($request->idItem) {
                $idExposicion = $request->idExposicion;
                $idExpositor = $request->idExpositor;
            } else {
                $idExposicion = Auth::guard('expositor')->user()->id_exposicion;
                $idExpositor = Auth::guard('expositor')->user()->id;
            }
            
            if ($request->id) {
                $item = ExpositorModuloSalaConferencia::find($request->id);
                $item->status = $request->status;
                $item->save();
            } else {
                if ($request->idItem) {
                    $item = ExpositorModuloSalaConferencia::find($request->idItem);
                    $modulo = \App\Models\ModuloSalaConferencia::find($item['id_modulo_sala_conferencia']);
                    $modulo->status = 'libre';
                    $modulo->save();
                    $item->delete();
                }

                $item = new ExpositorModuloSalaConferencia();
                $item->id_exposicion = $idExposicion;
                $exposicion = Exposicion::find($idExposicion);

                $item->id_expositor = $idExpositor;
                if ($request->idItem) {
                    $item->status = 'asignado';
                } else {
                    $item->status = 'pendiente';
                }
                
                $item->temario = $request->temario;
                $item->id_modulo_sala_conferencia = $request->id_sala;
                $item->responsable_apellido_nombre = $request->responsable_apellido_nombre;
                $item->responsable_cargo = $request->responsable_cargo;
                $item->responsable_email = $request->responsable_email;
                $item->responsable_telefono = $request->responsable_telefono;
                $item->titulo_charla = $request->titulo_charla;
                $item->save();

                if ($request->apellido_nombre && count($request->apellido_nombre)) {
                    foreach ($request->apellido_nombre as $key => $value) {
                        $disertante = new \App\Models\ExpositorModuloSalaConferenciaDisertante();
                        $disertante->id_expositor_modulo_sala_conferencia = $item->id;
                        $disertante->apellido_nombre = $request->apellido_nombre[$key];
                        $disertante->email = $request->email[$key];
                        $disertante->telefono = $request->telefono[$key];
                        $disertante->facebook = $request->facebook[$key];
                        $disertante->instagram = $request->instagram[$key];
                        $disertante->linkedin = $request->linkedin[$key];
                        $disertante->youtube = $request->youtube[$key];
                        $disertante->save();
                    }
                }
            }

            if (Auth::guard()->check() || Auth::guard('expositor')->check()) {
                $item->updated_at_expositor = date('Y-m-d');
            }

            $sala = ModuloSalaConferencia::find($request->id_sala);
            if ($request->idItem) {
                $sala->status = 'asignado';
                $sala->save();
            }

            $expositor = \App\Models\Expositor::find($idExpositor);
            $stands = \App\Models\Stand::where('id_expositor', '=', $expositor['id'])->get();
            Log::debug('$expositor[id]: '.$expositor['id']);
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

            $disertantes = '';
            if ($request->apellido_nombre && count($request->apellido_nombre)) {
                foreach ($request->apellido_nombre as $key => $value) {
                    $disertantes .="<hr/>
                        <ul>
                            <li>Apellido y Nombre: ".$request->apellido_nombre[$key]."</li>
                            <li>Email ".$request->email[$key]."</li>
                            <li>Teléfono: ".$request->telefono[$key]."</li>
                            <li>Facebook: ".$request->facebook[$key]."</li>
                            <li>Instagram: ".$request->instagram[$key]."</li>
                            <li>Linkedin: ".$request->linkedin[$key]."</li>
                            <li>YouTube: ".$request->youtube[$key]."</li>
                        </ul>
                    ";
                }
            }

            $details = [
                'idExposicion' => $idExposicion,
                'title' => 'Solicitud de Sala de Conferencia',
                'body' => "
                    <strong>" . $empresaString . "</strong><br/>
                    " . ($standsString ? "Stands: " . $standsString : '') . "
                    
                    <p>Gracias por completar el formulario de sala de conferencia para ".$exposicion['nombre'].".</p>

                    <p>Hemos recibido su pedido. En breve recibirá un mensaje confirmando (o no) la asignación de la sala y horarios solicitados.</p>
                    
                    <p>A continuación, se detallan los datos ingresados:</p>

                    <hr/>

                    <ul>
                        <li>Sala: ".$sala['sala']."</li>
                        <li>Fecha: ".(new \DateTime($sala['fecha']))->format('d/m/Y H:i:s')."</li>
                    </ul>

                    <hr/>
            
                    <ul>
                        <li>Responsable: ".$item->responsable_apellido_nombre."</li>
                        <li>Cargo: ".$item->responsable_cargo."</li>
                        <li>Email: ".$item->responsable_email."</li>
                        <li>Telefono: ".$item->responsable_telefono."</li>
                    </ul>

                    <hr/>
                    
                    <label>Titulo de Conferencia: ".$item->titulo_charla."</label><br/>
                    <p>Temario: <br/>".$item->temario."</p>

                    <br/>
                    <b>Disertantes:</b>
                    <br/>

                    ".$disertantes."
            
                    <p>Lo/a esperamos en el evento.</p>

                    <p>
                        No responda a este email. Contáctese a través de los canales habituales: <br/>
                        ".nl2br($exposicion['contacto_emails'])."
                    </p>
                "
            ];

            
            $mail = new EnviarEmailPredefinido($details);
            Mail::to('capacitacion@envase.org')->send($mail->from('envase.etif2025@gmail.com', $exposicion['nombre']));
            Mail::to($expositor->usuario)->send($mail->from('envase.etif2025@gmail.com', $exposicion['nombre']));
            try {
                Mail::to($item->responsable_email)->send($mail->from('envase.etif2025@gmail.com', $exposicion['nombre']));
            } catch (\Throwable $th) {
                //throw $th;
            }
            
            //Mail::to('hrauch@gmail.com')->send($mail->from('envase.etif2025@gmail.com', 'Novedades'));

            //Mail::to('marcelo.ismael.mdp@gmail.com')->send($mail->from('envase.etif2025@gmail.com', 'Novedades'));

            $messageBag->add('success', 'Pedido de sala de conferencia ' . ($request->id ? 'actualizado' : 'enviado') . ' con éxito!');

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function cancelar(Request $request) {

        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $item = ExpositorModuloSalaConferencia::find($request->id);
            $item->status = 'cancelado';
            $item->save();

            $itemSala = \App\Models\ModuloSalaConferencia::find($item['id_modulo_sala_conferencia']);
            $itemSala->status = 'libre';
            $itemSala->save();

            $messageBag->add('success', 'Pedido de sala de conferencia cancelado con éxito!');

            return back();

        } catch (\Throwable $th) {
            throw $th;
        }

    }
}