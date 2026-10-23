<?php

namespace App\Http\Controllers;

use App\Models\ExpositorModuloElectricidad;
use App\Models\ExpositorModuloSalaConferencia;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ModuloElectricidadController extends Controller {

    public function create(Request $request) {

        try {
            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::with('electricidad')->find($request->id);
            }
    
            $viewData = [
                "exposicion" => $exposicion
            ];

            //dd($viewData);

            return view('App.Modulo.electricidad', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateStore(Request $request) {

        try {

            //dd($request->all());

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);
            
            if (!$request->idExposicion) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $electricidad = \App\Models\ModuloElectricidad::where('id_exposicion','=',$request->idExposicion)->first();

                if (!$electricidad) {
                    $electricidad = new \App\Models\ModuloElectricidad();
                    $electricidad->id_exposicion = $request->idExposicion;
                }
            }

            $exposicion = \App\Models\Exposicion::find($request->idExposicion);

            $electricidad->descripcion = $request->descripcion;
            $electricidad->cierre = $request->cierre ? $request->cierre : $exposicion->fecha_fin;
            $electricidad->save();

            $messageBag->add('success', 'Modulo Invitación guardada con éxito!');
    
            return back();

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function panelRequerimientos(Request $request) {

        try {
            
            if (!$request->id) {
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::with('electricidad')->find($request->id);
            }

            $requerimientosElectricidad = \App\Models\ExpositorModuloElectricidad::
                leftJoin('expositor','expositor.id','=','expositor_modulo_electricidad.id_expositor')
                ->leftJoin('empresa','empresa.id','=','expositor.id_empresa')
                ->leftJoin('stand', function($query) use($request) {
                    $query->on('stand.id_expositor','=','expositor_modulo_electricidad.id_expositor');
                    $query->where('stand.id_exposicion','=',$request->id);
                })
                ->select(
                    'expositor_modulo_electricidad.*', 
                    'empresa.razon_social',
                    'empresa.nombre_fantasia',
                    'stand.numero'
                )
                ->where('expositor_modulo_electricidad.id_exposicion','=',$request->id)
                ->get();

            //dd($requerimientosElectricidad);
    
            $viewData = [
                "exposicion" => $exposicion,
                'requerimientosElectricidad' => $requerimientosElectricidad
            ];

            //dd($viewData);

            return view('App.ModuloRequerimientos.electricidad', ['viewData' => $viewData]);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function delete(Request $request) {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);
            
            $item = ExpositorModuloElectricidad::find($request->id);
            $item->delete();

            $messageBag->add('success', 'Pedido de energia electrica eliminado');

            return back();

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}