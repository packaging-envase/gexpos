<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Mail\EnviarEmailPredefinido;
use App\Models\Exposicion;
use App\Models\ModuloElementosOpcionales;
use App\Models\ExpositorModuloElementosOpcionales;

class ExpositorModuloElementosOpcionalesController extends Controller
{

    public function index()
    {

        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            //$messageBag->add('success', '');

            $idExpositor = Auth::guard('expositor')->user()->id;
            $idExposicion = Auth::guard('expositor')->user()->id_exposicion;

            $elementos = ModuloElementosOpcionales::leftJoin('expositor_modulo_elementos_opcionales', function ($query) use ($idExpositor) {
                $query->on('expositor_modulo_elementos_opcionales.id_modulo_elem_op', '=', 'modulo_elementos_opcionales.id');
                $query->where('expositor_modulo_elementos_opcionales.id_expositor', '=', $idExpositor);
                $query->whereNull('expositor_modulo_elementos_opcionales.deleted_at');
            })
                ->select(
                    'modulo_elementos_opcionales.*',
                    'expositor_modulo_elementos_opcionales.observacion',
                    'expositor_modulo_elementos_opcionales.rechazado',
                    'expositor_modulo_elementos_opcionales.id as idElementoExpositor',
                    'expositor_modulo_elementos_opcionales.confirmado'
                )
                ->where('id_exposicion', '=', $idExposicion)
                ->get()
                ->toArray();

            $solicitados = 0;
            $rechazados = 0;
            $pendientes = 0;

            foreach ($elementos as $key => $value) {
                if (isset($value['idElementoExpositor'])) {
                    // Si existe la relación con el expositor
                    if ($value['rechazado'] == false) {
                        // Elemento solicitado
                        $solicitados++;
                    } elseif ($value['rechazado'] == true) {
                        // Elemento rechazado
                        $rechazados++;
                    }
                } else {
                    // Si no existe la relación con el expositor
                    $pendientes++;
                }
            }

            if (!$elementos[0]['confirmado']) {
                $messageBag->add('warning', 'Al completar los pendientes, haga click en confirmar');
            }

            //dd($elementos);

            $viewData = [
                'exposicion' => \App\Models\Exposicion::find($idExposicion),
                'elementos' => $elementos,
                'empresa' => \App\Models\Empresa::find(Auth::guard('expositor')->user()->id_empresa),
                'solicitados' => $solicitados,
                'rechazados' => $rechazados,
                'pendientes' => $pendientes,
            ];

            return view('App.ModuloExpositor.elementosOpcionales', ['viewData' => $viewData]);
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

            if ($request->idElementoExpositor) {
                $expositorModuloElementosOpcionales = ExpositorModuloElementosOpcionales::find($request->idElementoExpositor);
            } else {
                $expositorModuloElementosOpcionales = new ExpositorModuloElementosOpcionales();
                $expositorModuloElementosOpcionales->id_expositor = $idExpositor;
                $expositorModuloElementosOpcionales->id_modulo_elem_op = $request->id_modulo_elem_op;
            }

            if (Auth::guard()->check() || Auth::guard('expositor')->check()) {
                $expositorModuloElementosOpcionales->updated_at_expositor = date('Y-m-d');
            }

            if ($request->action == 'enviar') {
                $expositorModuloElementosOpcionales->observacion = $request->observacion;
            }
            if ($request->action == 'solicitar') {
                $expositorModuloElementosOpcionales->rechazado = false;
            }
            if ($request->action == 'rechazar') {
                $expositorModuloElementosOpcionales->rechazado = true;
            }

            $expositorModuloElementosOpcionales->save();

            $messageBag->add('success', 'Elemento Opcional solicitado con éxito');

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function confirmar(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $idExpositor = Auth::guard('expositor')->user()->id;
            $idExposicion = Auth::guard('expositor')->user()->id_exposicion;
            $exposicion = Exposicion::find($idExposicion);

            $elementos = ModuloElementosOpcionales::where('id_exposicion', '=', $idExposicion)->get();

            $elementosSolicitados = ExpositorModuloElementosOpcionales::where('id_expositor', $idExpositor)->get();

            foreach ($elementosSolicitados as $elemento) {
                $elemento->confirmado = true;
                $elemento->save();
            }

            $elementosSolicitados = ExpositorModuloElementosOpcionales::leftJoin('modulo_elementos_opcionales', 'modulo_elementos_opcionales.id', '=', 'expositor_modulo_elementos_opcionales.id_modulo_elem_op')
                ->select(
                    'modulo_elementos_opcionales.titulo',
                    'expositor_modulo_elementos_opcionales.id_expositor',
                    'expositor_modulo_elementos_opcionales.rechazado'
                )
                ->where('expositor_modulo_elementos_opcionales.id_expositor', '=', $idExpositor)
                ->get();

            //return response()->json([], 200);

            $expositor = \App\Models\Expositor::find(Auth::guard('expositor')->user()->id);
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

            $elementosString = '';
            foreach ($elementosSolicitados as $key => $value) {
                $elementosString .= '<li><strong>' . $value['titulo'] . ':</strong> ' . ($value['rechazado'] ? 'Rechazado' : 'Solicitado') . '</li>';
            }

            $details = [
                'idExposicion' => $idExposicion,
                'title' => 'Solicitud de elementos opcionales',
                'body' => "
                    <strong>" . $empresaString . "</strong><br/>
                    " . ($standsString ? "Stands: " . $standsString : '') . "
                    <p>Gracias por completar el formulario de elementos opcionales para ".$exposicion['nombre'].".</p>
                    
                    <p>A continuación, se detallan los datos ingresados:</p>
            
                    <ul>
                        " . $elementosString . "
                    </ul>
            
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

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
