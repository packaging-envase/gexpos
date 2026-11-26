<?php

namespace App\Http\Controllers;

use App\Models\Exposicion;
use App\Models\Expositor;
use App\Models\ExpositorModuloDepositoGarantia;
use App\Models\ModuloDepositoGarantia;
use App\Models\Stand;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ModuloDepositoGarantiaController extends Controller
{

    public function create(Request $request)
    {
        try {
            // Create a new MessageBag instance flashed to the session.
            //$messageBag = session()->get('messageBag') ?? new MessageBag;
            //request()->session()->flash('messageBag', $messageBag);

            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::find($request->id);
            }

            $moduloDepositoGarantia = ModuloDepositoGarantia::where('id_exposicion', '=', $request->id)->first();

            $viewData = [
                "exposicion" => $exposicion,
                "moduloDepositoGarantia" => $moduloDepositoGarantia
            ];

            //$messageBag->add('success', '');

            return view('App.Modulo.depositoGarantia', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateStore(Request $request)
    {
        try {

            //dd($request->all());

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            if (!$request->idExposicion) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $moduloDepositoGarantia = ModuloDepositoGarantia::where('id_exposicion', '=', $request->idExposicion)->first();

                if (!$moduloDepositoGarantia) {
                    $moduloDepositoGarantia = new \App\Models\ModuloDepositoGarantia();
                    $moduloDepositoGarantia->id_exposicion = $request->idExposicion;
                }
            }

            /*
            if ($request->file('archivo')) {
                $archivo = $request->file('archivo');
                $nombreArchivo = uniqid() . '.' . $archivo->getClientOriginalExtension();
                $ruta = $archivo->storeAs('public/depositoGarantia', $nombreArchivo);

                $moduloDepositoGarantia->path = $ruta;
            }
            */

            $exposicion = \App\Models\Exposicion::find($request->idExposicion);

            $moduloDepositoGarantia->descripcion = $request->descripcion;
            $moduloDepositoGarantia->porcentaje = $request->porcentaje;
            $moduloDepositoGarantia->fecha_cierre = $request->fecha_cierre;
            $moduloDepositoGarantia->fecha = $request->fecha;
            $moduloDepositoGarantia->save();

            $messageBag->add('success', 'Modulo Deposito en Garantia guardado con éxito!');

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function downloadFile(Request $request)
    {
        try {

            $rutaCompleta = storage_path('app/' . $request->path);

            if (!file_exists($rutaCompleta)) {
                return response()->json(['error' => 'El archivo no existe en el servidor.'], 404);
            }


            $extension = pathinfo($rutaCompleta, PATHINFO_EXTENSION);
            $nombreDescarga = 'deposito_garantia.' . $extension;

            return response()->download($rutaCompleta, $nombreDescarga);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function switchAprobado(Request $request)
    {
        try {

            $expositorModuloDepositoGarantia = ExpositorModuloDepositoGarantia::where('id_expositor', '=', $request->idExpositor);

            if (!$expositorModuloDepositoGarantia) {
                $expositorModuloDepositoGarantia = new ExpositorModuloDepositoGarantia();
                $expositorModuloDepositoGarantia->save();
            }

            $expositorModuloDepositoGarantia->cobrado = !$expositorModuloDepositoGarantia->cobrado;
            $expositorModuloDepositoGarantia->save();

            return back();
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
                $exposicion = Exposicion::find($request->id);
            }
            
            $expositorModuloDepositoGarantia = ExpositorModuloDepositoGarantia::where('id_exposicion','=',$exposicion->id)->get();

            $expositores = Expositor::with(['depositoGarantia', 'stand', 'empresa'])
                ->select(
                    'expositor.id',
                    'expositor.id_empresa',
                )
                ->where('id_exposicion','=',$exposicion['id'])
                ->get();

            //dd($expositorModuloDepositoGarantia);ç

            //dd($expositores[0]);

            $viewData = [
                'expositores' => $expositores,
                'expositorModuloDepositoGarantia' => $expositorModuloDepositoGarantia,
                'exposicion' => $exposicion
            ];

            return view('App.ModuloRequerimientos.depositoGarantia', ['viewData' => $viewData]);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateEntregado(Request $request) {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            //dd($request->all());
            
            $expositorModuloDepositoGarantia = ExpositorModuloDepositoGarantia::where('id_expositor','=',$request->id_expositor)->first();

            if (!$expositorModuloDepositoGarantia) {
                $expositorModuloDepositoGarantia = new ExpositorModuloDepositoGarantia();
                $expositorModuloDepositoGarantia->id_exposicion = $request->id_exposicion;
                $expositorModuloDepositoGarantia->id_expositor = $request->id_expositor;
            }

            if (isset($request->entregado)) {
                $expositorModuloDepositoGarantia->entregado = true;
            } else {
                $expositorModuloDepositoGarantia->entregado = false;
            }

            if (isset($request->fecha_entregado)) {
                $expositorModuloDepositoGarantia->fecha_entregado = $request->fecha_entregado;
            }

            if (isset($request->devuelto)) {
                $expositorModuloDepositoGarantia->devuelto = true;
            } else {
                $expositorModuloDepositoGarantia->devuelto = false;
            }

            if (isset($request->fecha_devuelto)) {
                $expositorModuloDepositoGarantia->fecha_devuelto = $request->fecha_devuelto;
            }
            
            $expositorModuloDepositoGarantia->save();

            $messageBag->add('success', 'Deposito en Garantia actualizado con éxito!');

            return back();

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateFile(Request $request) {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);
            
            $expositorModuloDepositoGarantia = ExpositorModuloDepositoGarantia::where('id_expositor','=',$request->id_expositor)->first();

            if (!$expositorModuloDepositoGarantia) {
                $expositorModuloDepositoGarantia = new ExpositorModuloDepositoGarantia();
                $expositorModuloDepositoGarantia->id_exposicion = $request->id_exposicion;
                $expositorModuloDepositoGarantia->id_expositor = $request->id_expositor;
            }

            if ($request->file('archivo')) {
                $archivo = $request->file('archivo');
                $nombreArchivo = uniqid() . '.' . $archivo->getClientOriginalExtension();
                $ruta = $archivo->storeAs('public/depositoGarantia', $nombreArchivo);

                $expositorModuloDepositoGarantia->path = $ruta;
                $expositorModuloDepositoGarantia->save();
            }

            $messageBag->add('success', 'Deposito en Garantia actualizado con éxito!');

            return back();

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
