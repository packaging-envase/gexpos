<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;

class ModuloPlanoStandController extends Controller {

    public function create(Request $request) {

        try {
            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::with('planoStand')->find($request->id);
            }
    
            $viewData = [
                "exposicion" => $exposicion
            ];

            //dd($viewData);

            return view('App.Modulo.planoStand', ['viewData' => $viewData]);
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
                $plano = \App\Models\ModuloPlanoStand::where('id_exposicion','=',$request->idExposicion)->first();

                if (!$plano) {
                    $plano = new \App\Models\ModuloPlanoStand();
                    $plano->id_exposicion = $request->idExposicion;
                }
            }

            $exposicion = \App\Models\Exposicion::find($request->idExposicion);

            $plano->descripcion = $request->descripcion;
            $plano->cierre = $request->cierre ? $request->cierre : $exposicion->fecha_fin;
            $plano->save();

            $messageBag->add('success', 'Modulo Plano Stand guardado con éxito!');
    
            return back();

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function panelRequerimientos(Request $request) {

        try {

            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::with('planoStand')->find($request->id);
            }

            $pedidos = \App\Models\ExpositorModuloPlanoStand::
                with(['expositorModuloPlanoStandFile' ,'moduloPlanoStandComentario','expositor.empresa'])
                /*
                with(['expositorModuloPlanoStandFile' ,'moduloPlanoStandComentario' => function ($query) {
                    $query->orderBy('created_at', 'desc')->take(1);
                },'expositor.empresa'])
                */
                ->orderBy('updated_at','desc')
                ->get();
            
            $viewData = [
                'exposicion' => $exposicion,
                //'pedidos' => $pedidos,
            ];

            return view('App.ModuloRequerimientos.planoStand', ['viewData' => $viewData]);

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}