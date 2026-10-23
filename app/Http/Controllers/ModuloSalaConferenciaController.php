<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use App\Exports\ReporteExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Mail\EnviarEmailPredefinido;
use App\Models\Exposicion;
use App\Models\ExpositorModuloSalaConferencia;
use App\Models\ExpositorModuloSalaConferenciaDisertante;
use App\Models\ModuloSalaConferencia;

class ModuloSalaConferenciaController extends Controller
{

    public function panel(Request $request)
    {

        try {

            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::with([
                    'salaConferencia' => function ($query) {
                        $query->orderBy('sala', 'asc');
                        $query->orderBy('fecha', 'asc');
                    },
                    'salaConferenciaTexto'
                ])->find($request->id);
            }

            $item = null;
            if ($request->itemId) {
                $item = \App\Models\ModuloSalaConferencia::find($request->itemId);
            }

            $viewData = [
                "exposicion" => $exposicion,
                "item" => $item
            ];

            return view('App.Modulo.salaConferencia', ['viewData' => $viewData]);
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

            $item = null;

            if ($request->id) {
                $item = \App\Models\ModuloSalaConferencia::find($request->id);
            } else {
                $item = new \App\Models\ModuloSalaConferencia();
            }

            $item->id_exposicion = $request->id_exposicion;
            $item->sala = $request->sala;
            $item->fecha = $request->fecha;
            $item->duracion = $request->duracion;
            $item->etif = $request->etif == 'on' ? true : false;
            $item->save();

            $messageBag->add('success', 'Item sala de conferencia ' . ($request->id ? 'actualizado' : 'guardado') . ' con éxito!');

            return redirect()->route('modulo.sala-conferencia.panel', ['id' => $request->id_exposicion]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateStoreTexto(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $item = null;

            if ($request->id_exposicion) {
                $item = \App\Models\ModuloSalaConferenciaTexto::find($request->id_exposicion);
            } else {
                $item = new \App\Models\ModuloSalaConferenciaTexto();
            }

            $exposicion = \App\Models\Exposicion::find($request->id_exposicion);

            $item->id_exposicion = $request->id_exposicion;
            $item->descripcion = $request->descripcion;
            $item->cierre = $request->cierre ? $request->cierre : $exposicion->fecha_fin;
            $item->save();

            $messageBag->add('success', 'Texto de sala de conferencia ' . ($request->id ? 'actualizado' : 'guardado') . ' con éxito!');

            return redirect()->route('modulo.sala-conferencia.panel', ['id' => $request->id_exposicion]);
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

            $item = \App\Models\ModuloSalaConferencia::find($request->itemId);
            $item->delete();

            $items = \App\Models\ExpositorModuloSalaConferencia::where('id_modulo_sala_conferencia')->get();
            foreach ($items as $key => $value) {
                $item->delete();
            }

            $messageBag->add('success', 'Item sala de conferencia eliminado con éxito!');

            return redirect()->route('modulo.sala-conferencia.panel', ['id' => $request->id]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function panelRequerimientos(Request $request)
    {

        try {

            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::with([
                    'salaConferencia' => function ($query) {
                        $query->orderBy('sala', 'asc');
                        $query->orderBy('fecha', 'asc');
                    },
                    'salaConferenciaTexto'
                ])->find($request->id);
            }

            $moduloSalaConferencia = \App\Models\ModuloSalaConferencia::with('expositorModuloSalaConferencia.expositor.empresa')
                ->where('id_exposicion', '=', $exposicion->id)
                ->orderBy('fecha', 'asc')
                ->orderBy('sala', 'asc')
                ->get();

            $viewData = [
                'exposicion' => $exposicion,
                'moduloSalaConferencia' => $moduloSalaConferencia
            ];

            return view('App.ModuloRequerimientos.salaConferencia', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function aceptarSala(Request $request)
    {

        try {

            //dd($request->all());

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $pedido = \App\Models\ExpositorModuloSalaConferencia::find($request->id);
            $pedido->status = 'asignado';
            $pedido->save();

            $sala = \App\Models\ModuloSalaConferencia::find($pedido['id_modulo_sala_conferencia']);
            $sala->status = 'asignado';
            $sala->save();

            $expositor = \App\Models\Expositor::find($pedido['id_expositor']);
            $idExposicion = $expositor['id_exposicion'];
            $exposicion = Exposicion::find($idExposicion);

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

            $dishertantesRaw = ExpositorModuloSalaConferenciaDisertante::where('id_expositor_modulo_sala_conferencia', '=', $pedido['id'])->get();

            $disertantes = '';
            foreach ($dishertantesRaw as $key => $value) {
                $disertantes .= "<hr/>
                    <ul>
                        <li>Apellido y Nombre: " . $value['apellido_nombre'] . "</li>
                        <li>Email " . $value['email'] . "</li>
                        <li>Teléfono: " . $value['telefono'] . "</li>
                        <li>Facebook: " . $value['facebook'] . "</li>
                        <li>Instagram: " . $value['instagram'] . "</li>
                        <li>Linkedin: " . $value['linkedin'] . "</li>
                        <li>YouTube: " . $value['youtube'] . "</li>
                    </ul>
                ";
            }

            $details = [
                'idExposicion' => $idExposicion,
                'title' => 'Solicitud de Sala de Conferencia',
                'body' => "
                    <strong>" . $empresaString . "</strong><br/>
                    " . ($standsString ? "Stands: " . $standsString : '') . "
                    
                    <p>Gracias por completar el formulario de sala de conferencia para ".$exposicion['nombre'].".</p>

                    <p>Por duda o consultas, contáctese con capacitacion@envase.org o con su asesor comercial</p>
                    
                    <button style='background-color: #28a745;
                    color: white;
                    border: none;
                    padding: 0.5rem 1rem;
                    border-radius: 0.25rem;
                    cursor: pointer;'>
                        ACEPTADO
                    </button>

                    <p>Su pedido ha sido aceptado y confirmado según el siguiente detalle:</p>

                    <hr/>

                    <ul>
                        <li>Sala: " . $sala['sala'] . "</li>
                        <li>Fecha: " . (new \DateTime($sala['fecha']))->format('d/m/Y H:i:s') . "</li>
                    </ul>

                    <hr/>
            
                    <ul>
                        <li>Responsable: " . $pedido->responsable_apellido_nombre . "</li>
                        <li>Cargo: " . $pedido->responsable_cargo . "</li>
                        <li>Email: " . $pedido->responsable_email . "</li>
                        <li>Telefono: " . $pedido->responsable_telefono . "</li>
                    </ul>

                    <hr/>
                    
                    <label>Titulo de Conferencia: " . $pedido->titulo_charla . "</label><br/>
                    <p>Temario: <br/>" . $pedido->temario . "</p>

                    ". ($disertantes ? '<br/> <b>Disertantes:</b> <br/>' : '') ."

                    " . $disertantes . "
            
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
            Mail::to($pedido->responsable_email)->send($mail->from('envase.etif2025@gmail.com', $exposicion['nombre']));
            //Mail::to('hrauch@gmail.com')->send($mail->from('envase.etif2025@gmail.com', 'Novedades'));

            //Mail::to('marcelo.ismael.mdp@gmail.com')->send($mail->from('envase.etif2025@gmail.com', 'Novedades'));

            $messageBag->add('success', 'Sala de conferencia asignada con éxito!');

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function cancelarSala(Request $request)
    {

        try {

            //dd($request->all());

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $pedido = \App\Models\ExpositorModuloSalaConferencia::find($request->id);
            $pedido->status = 'cancelado';
            $pedido->save();

            $sala = \App\Models\ModuloSalaConferencia::find($pedido['id_modulo_sala_conferencia']);
            $sala->status = 'libre';
            $sala->save();

            \App\Models\ExpositorModuloSalaConferencia::where('id_modulo_sala_conferencia', $sala['id'])
                ->where('status', '=', 'pendiente')
                ->update(['status' => 'cancelado']);

            //TODO: Se debe enviar email a todos los cancelados cuando se acepta a otro?

            $expositor = \App\Models\Expositor::withTrashed()->find($pedido['id_expositor']);
            $exposicion = Exposicion::find($expositor['id_exposicion']);

            $stands = \App\Models\Stand::where('id_expositor', '=', $expositor['id'])->get();
            Log::debug('$expositor[id]: ' . $expositor['id']);
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

            $dishertantesRaw = ExpositorModuloSalaConferenciaDisertante::where('id_expositor_modulo_sala_conferencia', '=', $pedido['id'])->get();

            $disertantes = '';
            foreach ($dishertantesRaw as $key => $value) {
                $disertantes .= "<hr/>
                    <ul>
                        <li>Apellido y Nombre: " . $value['apellido_nombre'] . "</li>
                        <li>Email " . $value['email'] . "</li>
                        <li>Teléfono: " . $value['telefono'] . "</li>
                        <li>Facebook: " . $value['facebook'] . "</li>
                        <li>Instagram: " . $value['instagram'] . "</li>
                        <li>Linkedin: " . $value['linkedin'] . "</li>
                        <li>YouTube: " . $value['youtube'] . "</li>
                    </ul>
                ";
            }

            $details = [
                'idExposicion' => $exposicion['id'],
                'title' => 'Solicitud de Sala de Conferencia',
                'body' => "
                    <strong>" . $empresaString . "</strong><br/>
                    " . ($standsString ? "Stands: " . $standsString : '') . "
                    
                    <p>Gracias por completar el formulario de sala de conferencia para ".$exposicion['nombre'].".</p>

                    <p>Lamentamos no poder asignarle la sala en el día y horario requerido. Por favor, ingrese nuevamente al sistema y realice otra solicitud.</p>
                    <p>Por duda o consultas, contáctese con capacitacion@envase.org o con su asesor comercial</p>
                    
                    <button style='background-color: #dc3545; 
                        color: white; 
                        border: none; 
                        padding: 0.5rem 1rem; 
                        border-radius: 0.25rem; 
                        cursor: pointer;'>
                        CANCELADO
                    </button>

                    <p>A continuación, se detallan los datos ingresados:</p>

                    <hr/>

                    <ul>
                        <li>Sala: " . $sala['sala'] . "</li>
                        <li>Fecha: " . (new \DateTime($sala['fecha']))->format('d/m/Y H:i:s') . "</li>
                    </ul>

                    <hr/>
            
                    <ul>
                        <li>Responsable: " . $pedido->responsable_apellido_nombre . "</li>
                        <li>Cargo: " . $pedido->responsable_cargo . "</li>
                        <li>Email: " . $pedido->responsable_email . "</li>
                        <li>Telefono: " . $pedido->responsable_telefono . "</li>
                    </ul>

                    <hr/>
                    
                    <label>Titulo de Conferencia: " . $pedido->titulo_charla . "</label><br/>
                    <p>Temario: <br/>" . $pedido->temario . "</p>

                    <br/>
                    <b>Disertantes:</b>
                    <br/>

                    " . $disertantes . "
            
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
            Mail::to($pedido->responsable_email)->send($mail->from('envase.etif2025@gmail.com', $exposicion['nombre']));
            //Mail::to('hrauch@gmail.com')->send($mail->from('envase.etif2025@gmail.com', 'Novedades'));

            //Mail::to('marcelo.ismael.mdp@gmail.com')->send($mail->from('envase.etif2025@gmail.com', 'Novedades'));

            $messageBag->add('success', 'Sala de conferencia disponible!');

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function exportarExcel(Request $request)
    {
        try {

            $idExposicion = $request->id;

            return Excel::download(new ReporteExport($idExposicion), 'sala_conferencia.xlsx');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function switchPublicado(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $item = ModuloSalaConferencia::find($request->id);

            $item->publicado = !$item->publicado;
            $item->save();

            $messageBag->add('success', 'Sala de conferencia ' . ($item->publicado ? 'publicada' : 'no publicada'));

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function publicarTodo(Request $request) {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);
            
            ModuloSalaConferencia::where('id_exposicion','=',$request->idExposicion)
                ->where('status','=','asignado')
                ->update(['publicado' => true]);

            $messageBag->add('success', 'Todas las salas asignadas fueron publicadas');

            return back();

        } catch (\Throwable $th) {
            throw $th;
        }
    }


    public function publicarWeb(Request $request) {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            }
            
            ModuloSalaConferencia::where('id_exposicion','=',$request->id)->update(['publicado_final' => false]);

            ModuloSalaConferencia::where('id_exposicion','=',$request->id)->where('publicado','=',true)->update(['publicado_final' => true]);

            $messageBag->add('success', 'Sala de conferencia actualizada en web exitosamente');

            return back();

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
