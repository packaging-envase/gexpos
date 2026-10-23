<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use App\Mail\EnviarEmailPredefinido;
use App\Models\Expositor;
use App\Models\Empresa;
use App\Models\Exposicion;
use App\Models\Stand;

class ExpositorModuloElectricidadController extends Controller
{
    public function index(Request $request)
    {

        $idExpositor = Auth::guard('expositor')->user()->id;
        $idExposicion = Auth::guard('expositor')->user()->id_exposicion;

        $viewData = [
            'exposicion' => \App\Models\Exposicion::find($idExposicion),
            'electricidad' => \App\Models\ModuloElectricidad::where('id_exposicion', '=', $idExposicion)->first(),
            'expositorModuloElectricidad' => \App\Models\ExpositorModuloElectricidad::where('id_exposicion', '=', $idExposicion)->where('id_expositor', '=', $idExpositor)->first(),
            'empresa' => \App\Models\Empresa::find(Auth::guard('expositor')->user()->id_empresa)
        ];

        //dd($viewData);

        return view('App.ModuloExpositor.electricidad', ['viewData' => $viewData]);
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

            $item = null;
            if ($request->id) {
                $item = \App\Models\ExpositorModuloElectricidad::find($request->id);
            } else {
                $item = new \App\Models\ExpositorModuloElectricidad();
                $item->id_exposicion = $idExposicion;
                $item->id_expositor = $idExpositor;
            }

            if (Auth::guard()->check() || Auth::guard('expositor')->check()) {
                $item->updated_at_expositor = date('Y-m-d');
            }

            $item->cantidad = $request->cantidad;
            $item->tiene_maquinas = $request->tiene_maquinas == '1' ? true : false;
            $item->peso_medida = $request->peso_medida;
            $item->funcionan = $request->funcionan == '1' ? true : false;
            $item->monofasica = $request->monofasica;
            $item->trifasica = $request->trifasica;
            $item->apellido_nombre = $request->apellido_nombre;
            $item->cargo = $request->cargo;
            $item->telefono = $request->telefono;
            $item->celular = $request->celular;
            $item->email = $request->email;

            $expositor = Expositor::find(Auth::guard('expositor')->user()->id);
            $stands = Stand::where('id_expositor', '=', $expositor['id'])->get();
            //Log::debug('$expositor[id]: '.$expositor['id']);
            if (count($stands)) {
                $stands = $stands->toArray();
            } else {
                $stands = [];
            }
            
            $standsString = implode(', ', array_column($stands, 'numero'));
            $empresa = Empresa::find($expositor['id_empresa']);

            //$standsString = '999';

            $empresaString = '';
            if (count($stands) && $stands[0]['nombre_exposicion']) {
                $empresaString = $empresa[$stands[0]['nombre_exposicion']];
            } else {
                $empresaString = $empresa['razon_social'];
            }

            $details = [
                'idExposicion' => $idExposicion,
                'title' => 'Solicitud de energía eléctrica adicional',
                'body' => "
                    <strong>" . $empresaString . "</strong><br/>
                    " . ($standsString ? "Stands: " . $standsString : '') . "
                    <p>Estimado/a <strong>{$item->apellido_nombre}</strong>,</p>
                    <p>Gracias por completar el formulario de energía y equipamiento para ".$exposicion['nombre'].".</p>
                    
                    <p>A continuación, se detallan los datos ingresados:</p>
            
                    <ul>                        
                        <li><strong>¿Posee máquinas?:</strong> " . ($item->tiene_maquinas ? 'Sí' : 'No') . "</li>
                    " . ($item->tiene_maquinas ? "
                        <li><strong>Cantidad de equipos:</strong> {$item->cantidad}</li>
                        <li><strong>Peso estimado:</strong> {$item->peso_medida}</li>
                        <li><strong>¿Funcionan?:</strong> " . ($item->funcionan ? 'Sí' : 'No') . "</li>
                    " : '') . "
                        <li><strong>Monofásica:</strong> {$item->monofasica}</li>
                        <li><strong>Trifásica:</strong> {$item->trifasica}</li>
                        <li><strong>Responsable:</strong> {$item->apellido_nombre} ({$item->cargo})</li>
                        <li><strong>Teléfono:</strong> {$item->telefono}</li>
                        <li><strong>Celular:</strong> {$item->celular}</li>
                        <li><strong>Email:</strong> {$item->email}</li>
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
            //Mail::to('tecnica@envase.org')->send($mail->from('envase.etif2025@gmail.com', 'Novedades'));
            Mail::to('facturacion@envase.org')->send($mail->from('envase.etif2025@gmail.com', $exposicion['nombre']));

            $item->save();

            $messageBag->add('success', 'Pedido de electricad enviada con éxito!');

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
