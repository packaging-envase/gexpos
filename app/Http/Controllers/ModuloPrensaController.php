<?php

namespace App\Http\Controllers;

use App\Models\Exposicion;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Models\ModuloPrensa;
use App\Models\ModuloPrensaArchivo;
use App\Models\ModuloPrensaImagen;
use App\Models\PrensaAcreditacion;

class ModuloPrensaController extends Controller
{

    public function create(Request $request)
    {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::with('planoStand')->find($request->id);
            }

            if ($request->idPrensa) {
                $prensa = ModuloPrensa::with(['moduloImagen', 'moduloArchivo'])->find($request->idPrensa);
            } else {
                $prensa = null;
            }

            $viewData = [
                "exposicion" => $exposicion,
                "prensa" => $prensa,
                "view" => $request->view ? true : false
            ];

            //dd($viewData);

            if ($viewData['view']) {
                return view('App.Modulo.prensaView', ['viewData' => $viewData]);
            } else {
                return view('App.Modulo.prensa', ['viewData' => $viewData]);
            }

            //$messageBag->add('success', '');
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

            if ($request->id) {
                $moduloPrensa = ModuloPrensa::find($request->id);
            } else {
                $moduloPrensa = new ModuloPrensa();
                $moduloPrensa->id_exposicion = $request->id_exposicion;
            }

            $moduloPrensa->titulo = $request->titulo;
            $moduloPrensa->texto = $request->texto;
            $moduloPrensa->contacto = $request->contacto;
            $moduloPrensa->save();

            if ($request->archivo) {
                $archivo = new ModuloPrensaArchivo();

                $archivo->id_modulo_prensa = $moduloPrensa->id;
                $archivo->path = $request->archivo->store("public/prensa/archivo");
                $archivo->path = str_replace("public/prensa/archivo/", '', $archivo->path);
                $archivo->save();
            }

            if ($request->imagenes) foreach ($request->imagenes as $key => $value) {
                $imagen = new ModuloPrensaImagen();

                $imagen->id_modulo_prensa = $moduloPrensa->id;
                $imagen->path = $request->imagenes[$key]->store("public/prensa/imagen");
                $imagen->path = str_replace("public/prensa/imagen/", '', $imagen->path);
                $imagen->save();
            }

            $messageBag->add('success', 'Prensa creada con éxito');

            return redirect()->route('modulo.prensa.create', ['id' => $request->id_exposicion, 'idPrensa' => $moduloPrensa['id'], 'view' => 'true']);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function listado(Request $request)
    {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::with('planoStand')->find($request->id);
            }

            $viewData = [
                "exposicion" => $exposicion,
                "prensas" => ModuloPrensa::where('id_exposicion', '=', $exposicion['id'])->orderBy('id', 'desc')->get()
            ];

            //dd($viewData);

            return view('App.Modulo.prensaListado', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteImage(Request $request)
    {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $image = ModuloPrensaImagen::find($request->id);
            $image->delete();

            $messageBag->add('success', 'Imágen eliminada con éxito!');

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteArchivo(Request $request)
    {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $image = ModuloPrensaArchivo::find($request->id);
            $image->delete();

            $messageBag->add('success', 'Archivo eliminada con éxito!');

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function activar(Request $request)
    {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $prensa = ModuloPrensa::find($request->idPrensa);
            $prensa->aprobado = true;
            $prensa->save();

            $messageBag->add('success', 'Prensa activada con éxito!');

            return redirect()->route('modulo.prensa.create', ['id' => $request->id, 'idPrensa' => $request->idPrensa, 'view' => 'true']);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function desactivar(Request $request)
    {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $prensa = ModuloPrensa::find($request->idPrensa);
            $prensa->aprobado = false;
            $prensa->save();

            $messageBag->add('success', 'Prensa desactivada con éxito!');

            return redirect()->route('modulo.prensa.create', ['id' => $request->id, 'idPrensa' => $request->idPrensa, 'view' => 'true']);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function iframe(Request $request)
    {
        try {

            $prensas = ModuloPrensa::with(['moduloImagen', 'moduloArchivo'])
                ->where('aprobado', '=', true)
                ->where('id_exposicion', '=', $request->id)
                ->orderBy('id', 'desc')
                ->get();

            $viewData = [
                'prensas' => $prensas
            ];

            return view('App.Public.prensa', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function iframeAcreditacion(Request $request) {
        try {

            $exposicion = Exposicion::find($request->id);

            $viewData = [
                'id_exposicion' => $exposicion->id
            ];
            
            return view('App.Public.acreditacion', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function acreditacionStore(Request $request)
    {
        try {
            
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            // Creación del registro en la base de datos
            $item = new PrensaAcreditacion;
            $item->id_exposicion = $request->id_exposicion;
            $item->dni = $request->dni;
            $item->apellido_nombre = $request->apellido_nombre;
            $item->whatsapp = $request->whatsapp;
            $item->medio = $request->medio;
            $item->razon_social = $request->razon_social;
            $item->save();

            $messageBag->add('success', 'Solicitud de Acreditación enviada con éxito!');

            // Redirección con mensaje de éxito
            return redirect()->back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function acreditacionPanel(Request $request) {
        try {

            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::find($request->id);
            }
            
            $items = PrensaAcreditacion::where('id_exposicion','=',$exposicion->id)->get();

            $viewData = [
                'acreditaciones' => $items,
                'exposicion' => $exposicion
            ];

            return view('App.ModuloRequerimientos.prensa', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
