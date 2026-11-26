<?php

namespace App\Http\Controllers;

use App\Models\ExpositorModuloCatalogo;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;

use \App\Models\ExpositorModuloInvitacion;

class ModuloInvitacionController extends Controller
{

    public function create(Request $request)
    {

        try {
            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::with('invitacion')->find($request->id);
            }

            $viewData = [
                "exposicion" => $exposicion
            ];

            //dd($viewData);

            return view('App.Modulo.invitaciones', ['viewData' => $viewData]);
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
                $invitacion = \App\Models\ModuloInvitacion::where('id_exposicion', '=', $request->idExposicion)->first();

                if (!$invitacion) {
                    $invitacion = new \App\Models\ModuloInvitacion();
                    $invitacion->id_exposicion = $request->idExposicion;
                }
            }

            $exposicion = \App\Models\Exposicion::find($request->idExposicion);

            $invitacion->descripcion = $request->descripcion;
            $invitacion->cierre = $request->cierre ? $request->cierre : $exposicion->fecha_fin;;
            $invitacion->save();

            $messageBag->add('success', 'Modulo Invitación guardada con éxito!');

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
                $exposicion = \App\Models\Exposicion::with('invitacion')->find($request->id);
            }

            $invitaciones = ExpositorModuloInvitacion::leftJoin('expositor', 'expositor.id', '=', 'expositor_modulo_invitacion.id_expositor')
                ->leftJoin('empresa', 'empresa.id', '=', 'expositor.id_empresa')
                ->where('expositor.id_exposicion', '=', $exposicion->id)
                ->select('expositor_modulo_invitacion.*', 'empresa.razon_social', 'empresa.nombre_fantasia')
                ->get()
                ->toArray();

            foreach ($invitaciones as $key => $value) {
                $invitaciones[$key]['maxima'] = \App\Models\Stand::where('id_expositor', '=', $value['id_expositor'])
                    ->get()
                    ->sum(function ($stand) {
                        return (int) $stand->metros_cuadrados * 6; // Convertir el string a entero
                    });

                $stands = \App\Models\Stand::where('id_expositor', '=', $value['id_expositor'])->get()->toArray();

                $stringStands = '';                

                foreach ($stands as $keyStand => $stand) {                    
                    $stringStands .= $stand['numero'];
                    if (count($stands) != $keyStand + 1) {
                        $stringStands .= ' - ';
                    }
                }

                $invitaciones[$key]['stands'] = $stringStands;

                $expositorModuloCatalogo = ExpositorModuloCatalogo::where('id_expositor', '=', $value['id_expositor'])->first();

                if ($expositorModuloCatalogo) {
                    $invitaciones[$key]['direccion'] = ($expositorModuloCatalogo['pais'] ? $expositorModuloCatalogo['pais'] . ' - ' : '') .
                    ($expositorModuloCatalogo['provincia'] ? $expositorModuloCatalogo['provincia'] . '-' : '') . 
                    ($expositorModuloCatalogo['localidad'] && $expositorModuloCatalogo['localidad'] != $expositorModuloCatalogo['provincia'] ? $expositorModuloCatalogo['localidad'] . ' - ' : '') .
                    ($expositorModuloCatalogo['direccion'] ? $expositorModuloCatalogo['direccion'] : '');

                    
                } else {
                    $invitaciones[$key]['direccion'] = '';
                }
                
            }

            $viewData = [
                "exposicion" => $exposicion,
                'invitaciones' => $invitaciones
            ];

            return view('App.ModuloRequerimientos.invitaciones', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
