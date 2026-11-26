<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use \App\Models\CredencialTipo;
use \App\Models\CredencialTipoExposicion;
use \App\Models\CredencialDetalle;

class ModuloCredencialController extends Controller
{

    public function panel(Request $request)
    {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::find($request->id);
            }

            $credencialTipo = CredencialTipo::all();

            foreach ($credencialTipo as $key => $value) {
                if (!CredencialTipoExposicion::where('id_exposicion', '=', $exposicion['id'])->where('id_credencial_tipo', '=', $value['id'])->first()) {
                    $newItem = new CredencialTipoExposicion();
                    $newItem->id_exposicion = $exposicion->id;
                    $newItem->id_credencial_tipo = $value['id'];
                    $newItem->save();
                }
            }

            $viewData = [
                "exposicion" => $exposicion,
                "credencial_tipo_exposicion" => CredencialTipoExposicion::with('credencialTipo')->where('id_exposicion','=',$request->id)->get(),
                "credencial_vencimiento" => \App\Models\Variables::where('id_exposicion', '=', $exposicion['id'])->where('descripcion', '=', 'credencial_vencimiento')->first()
            ];

            //$messageBag->add('success', '');

            return view('App.Modulo.credencial', ['viewData' => $viewData]);
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

            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::with('electricidad')->find($request->id);
            }

            $credencialesSolicitadas = CredencialTipoExposicion::leftJoin('credencial_tipo', 'credencial_tipo.id', '=', 'credencial_tipo_exposicion.id_credencial_tipo')
                ->select(
                    'credencial_tipo_exposicion.id_credencial_tipo',
                    'credencial_tipo.descripcion'
                )
                ->where('credencial_tipo_exposicion.activa', '=', true)
                ->get()
                ->toArray();

            $credencialesPedidasQuantity = 0;

            $credencialesSolicitadasFinal = [];
            foreach ($credencialesSolicitadas as $key => $value) {

                $credencialesSolicitadasFinal[$credencialesSolicitadas[$key]['descripcion']] = CredencialDetalle::leftJoin('expositor', 'expositor.id', '=', 'credencial_detalle.id_expositor')
                    ->leftJoin('empresa', 'empresa.id', '=', 'expositor.id_empresa')
                    ->leftJoin('stand', 'stand.id_expositor', '=', 'credencial_detalle.id_expositor')
                    ->select(
                        'credencial_detalle.*',
                        'empresa.razon_social',
                        DB::raw("GROUP_CONCAT(stand.numero SEPARATOR ' - ') as stands"),
                        DB::raw("'{$credencialesSolicitadas[$key]['descripcion']}' as descripcion")
                    )
                    ->where('expositor.id_exposicion', '=', $exposicion['id'])
                    ->where('credencial_detalle.id_credencial_tipo', '=', $credencialesSolicitadas[$key]['id_credencial_tipo'])
                    ->groupBy('credencial_detalle.id')
                    ->get()
                    ->toArray();

                $credencialesPedidasQuantity += count($credencialesSolicitadasFinal[$credencialesSolicitadas[$key]['descripcion']]);
            }



            //dd($credencialesPedidas);

            $viewData = [
                "exposicion" => $exposicion,
                'credencialesSolicitadas' => $credencialesSolicitadasFinal,
                'credencialesPedidasQuantity' => $credencialesPedidasQuantity,
                "credencial_vencimiento" => \App\Models\Variables::where('id_exposicion', '=', $exposicion['id'])->where('descripcion', '=', 'credencial_vencimiento')->first()
            ];

            return view('App.ModuloRequerimientos.credencial', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            //dd($request->all());

            $item = CredencialTipoExposicion::with(['credencialTipo'])->find($request->idCredencial);
            $item->activa = $request->activa == 'on' ? true : false;
            $item->save();

            if ($request->activa == 'on') {
                $messageBag->add('success', 'Credencial activada con éxito');
            } else {
                $messageBag->add('warning', 'Credencial desactivada con éxito');
            }

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateMaximo(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $item = CredencialTipoExposicion::where('id_exposicion', '=', $request->id)
                ->where('id', '=', $request->idCredencial)
                ->first();

            if (!$item) {
                //$item = new CredencialTipoExposicion();
                //$item->id_exposicion = $request->id;
                //$item->id_credencial_tipo = $request->idCredencial;
            }

            if ($request->maximo) {
                $item->maximo = $request->maximo;
            }
            
            if ($request->metros) {
                $item->metros = $request->metros;
            }

            $item->save();

            $messageBag->add('success', 'Credencial actualizada con éxito');

            return redirect()->route('modulo.credencial.panel', ['id' => $request->id]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
