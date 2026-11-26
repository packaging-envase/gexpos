<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Models\NovedadesEmpresa;
use App\Models\Empresa;
use App\Models\Expositor;

class NovedadesController extends Controller {

    public function panelEmpresa(Request $request) {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            //$messageBag->add('success', '');

            if ($request->id) {
                $exposicion = \App\Models\Exposicion::with(['catalogo','invitacion', 'planoStand', 'electricidad'])->find($request->id);
            } else {
                return back();
            }

            $novedades = NovedadesEmpresa::
                leftJoin('users','users.id','=','novedades_empresa.id_user')
                ->select('novedades_empresa.*','users.name')
                ->orderBy('created_at','desc')
                ->get()
                ->toArray();

            foreach ($novedades as $keyNovedad => $novedad) {
                $expositor = Expositor::where('id_exposicion','=',$exposicion['id'])->where('id_empresa','=',$novedad['id_empresa'])->first();
                if ($expositor) {
                    $novedades[$keyNovedad]['id_expositor'] = $expositor['id'];
                }
                
            }

            $viewData = [
                'exposicion' => $exposicion,
                'novedades' => $novedades
            ];

            return view('App.Novedades.panel-empresa', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }        
    }

    public function resolveExpositor(Request $request) {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $novedad = NovedadesEmpresa::find($request->id);
            $novedad->status = $request->status == 'true' ? 'aprobado' : 'rechazado';
            $novedad->save();

            if ($request->status == 'true') {
                $empresa = Empresa::find($novedad->id_empresa);
                $empresa->pertenece_etif = true;
                $empresa->save();

                if ($novedad->id_exposicion) {
                    $expositor = Expositor::where('id_exposicion','=',$novedad->id_exposicion)->where('id_empresa','=',$empresa->id)->first();
                    if ($expositor) {
                        $expositor->aprobado = true;
                        $expositor->save();
                    }
                }

                $messageBag->add('success', 'Expositor aprobado exsitosamente');
            } else {
                $empresa = Empresa::find($novedad->id_empresa);
                $empresa->pertenece_etif = false;
                $empresa->save();

                if ($novedad->id_exposicion) {
                    $expositor = Expositor::where('id_exposicion','=',$novedad->id_exposicion)->where('id_empresa','=',$empresa->id)->first();
                    if ($expositor) {
                        $expositor->aprobado = false;
                        $expositor->save();
                    }
                }

                $messageBag->add('danger', 'Expositor rechazado exsitosamente');
            }

            return back();
            
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}