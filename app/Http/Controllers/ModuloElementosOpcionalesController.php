<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\ModuloElementosOpcionales;
use App\Models\ExpositorModuloElementosOpcionales;
use App\Models\ModuloServiciosOptativos;

class ModuloElementosOpcionalesController extends Controller
{

    public function panel(Request $request)
    {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            //$messageBag->add('success', '');

            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::with('electricidad')->find($request->id);
            }

            $elementos = ModuloElementosOpcionales::where('id_exposicion', '=', $request->id)->get();

            $viewData = [
                "exposicion" => $exposicion,
                "elementos" => $elementos
            ];

            //dd($viewData);

            return view('App.Modulo.elementosOpcionales', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function create(Request $request)
    {
        try {

            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::with('electricidad')->find($request->id);
            }

            if ($request->idElemento) {
                $elemento = ModuloElementosOpcionales::find($request->idElemento);
            } else {
                $elemento = null;
            }

            $viewData = [
                "exposicion" => $exposicion,
                "elemento" => $elemento
            ];

            return view('App.Modulo.elementosOpcionalesCreate', ['viewData' => $viewData]);
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

            if ($request->idElemento) {
                $elementoOpcional = ModuloElementosOpcionales::find($request->idElemento);
            } else {
                $elementoOpcional = new ModuloElementosOpcionales();
            }

            $elementoOpcional->id_exposicion = $request->id_exposicion;
            $elementoOpcional->titulo = $request->titulo;
            $elementoOpcional->descripcion = $request->descripcion;
            $elementoOpcional->save();

            $messageBag->add('success', 'Elemento opcional ' . ($request->idElemento ? 'actualizado' : 'creado') . ' con éxito');

            return redirect()->route('modulo.elementos-opcionales.panel', ['id' => $request->id_exposicion]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function panelRequerimientos(Request $request)
    {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            //$messageBag->add('success', '');

            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::with('electricidad')->find($request->id);
            }

            $elementos = ExpositorModuloElementosOpcionales::
                leftJoin('modulo_elementos_opcionales', 'modulo_elementos_opcionales.id', '=', 'expositor_modulo_elementos_opcionales.id_modulo_elem_op')
                ->leftJoin('expositor', 'expositor.id', '=', 'expositor_modulo_elementos_opcionales.id_expositor')
                ->leftJoin('empresa', 'empresa.id', '=', 'expositor.id_empresa')
                ->leftJoin('stand', 'stand.id_expositor', '=', 'expositor.id')
                ->select(
                    'modulo_elementos_opcionales.titulo',
                    'empresa.razon_social',
                    'expositor_modulo_elementos_opcionales.observacion',
                    DB::raw("GROUP_CONCAT(stand.numero SEPARATOR ', ') as numeros_stand"),
                    'expositor_modulo_elementos_opcionales.created_at',
                    'expositor_modulo_elementos_opcionales.rechazado'
                )
                ->where('modulo_elementos_opcionales.id_exposicion', '=', $request->id)
                //->where('expositor_modulo_elementos_opcionales.rechazado','=',true)
                ->groupBy(
                    'modulo_elementos_opcionales.titulo',
                    'empresa.razon_social',
                    'expositor_modulo_elementos_opcionales.observacion',
                    'expositor_modulo_elementos_opcionales.created_at',
                    'expositor_modulo_elementos_opcionales.rechazado'
                )
                ->orderBy('empresa.razon_social','asc')
                ->get()
                ->toArray();

            

            $viewData = [
                "exposicion" => $exposicion,
                "elementos" => $elementos,
            ];

            //dd($viewData);

            return view('App.ModuloRequerimientos.elementosOpcionales', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function delete(Request $request) {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);
            
            $elemento = ModuloElementosOpcionales::find($request->idElemento);
            $elemento->delete();

            $messageBag->add('success', 'Elemento Opcional eliminado exitosamente');

            return back();

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateCierre(Request $request) {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::with('electricidad')->find($request->id);
            }
            
            $elementos = ModuloElementosOpcionales::where('id_exposicion','=',$exposicion->id)->get();
            foreach ($elementos as $key => $elemento) {
                $elemento->cierre = $request->cierre ? $request->cierre : $exposicion->fecha_fin;
                $elemento->save();
            }            

            $messageBag->add('success', 'Elemento Opcional actualizado correctamente');

            return back();

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function publicView(Request $request) {
        try {
            
            $optativos = ModuloServiciosOptativos::with('moduloServiciosOptativosFile')
                ->where('id_exposicion','=',$request->idExposicion)
                ->where('active','=',true)
                ->get();

            $viewData = [
                'optativos' => $optativos
            ];

            return view('App.Public.proveedoresOptativos', ['viewData' => $viewData]);

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
