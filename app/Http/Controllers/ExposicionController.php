<?php

namespace App\Http\Controllers;

use App\Models\Exposicion;
use App\Models\ExposicionArchivo;
use App\Models\StandTipo;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ExposicionController extends Controller
{
    public function panel(Request $request)
    {

        try {
            $exposiciones = \App\Models\Exposicion::select('id', 'nombre', 'mapa', 'etif', 'visible');


            if ($request->archivadas) {
                $exposiciones = $exposiciones->where('archivada', '=', 1);
            } else {
                $exposiciones = $exposiciones->where('archivada', '=', 0);
            }

            $exposiciones = $exposiciones->get()->map(function ($exposicion) {
                $exposicion->mapa = !empty($exposicion->mapa);
                return $exposicion;
            });


            $web = null;
            try {
                $web = DB::connection('external_db')->select("SELECT id_selected_expo, etif, envase FROM expo_id LIMIT 1");
            } catch (\Throwable $th) {
                //throw $th;
            }

            //dd($web);

            $viewData = [
                'exposiciones' => $exposiciones,
                'web' => $web,
                'archivadas' => $request->archivadas
            ];

            return view('App.Exposicion.panel', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function create(Request $request)
    {

        try {

            //dd($request->all());

            $exposicion = null;
            if (!$request->id) {

                $exposicion = new \App\Models\Exposicion();

                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();

            } else {
                $exposicion = \App\Models\Exposicion::with('exposicionArchivo')->find($request->id);

                // Modificar el path de los archivos
                if ($exposicion && $exposicion->exposicionArchivo) {
                    foreach ($exposicion->exposicionArchivo as $archivo) {
                        $archivo->path = asset($archivo->path); // Generar la URL completa
                    }
                }
            }

            $viewData = [
                'exposicion' => $exposicion
            ];

            return view('App.Exposicion.create', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store(Request $request)
    {
        try {

            //dd($request->all());

            //dd($request->exposicion_archivo_pertence);
            //dd($request->archivo_nivel);

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            if ($request->id) {
                $exposicion = \App\Models\Exposicion::with(['catalogo', 'invitacion', 'planoStand', 'electricidad'])->find($request->id);
            } else {
                $exposicion = new \App\Models\Exposicion();
            }

            $exposicion->nombre = $request->nombre;
            $exposicion->fecha_inicio = $request->fecha_inicio;
            $exposicion->fecha_fin = $request->fecha_fin;
            $exposicion->fecha_inicio = $request->fecha_inicio;
            //$exposicion->email_expositor = $request->email_expositor;
            $exposicion->email_administrador_gexpo = $request->email_administrador_gexpo;
            $exposicion->active = true;

            if ($request->hasFile('logo_exposicion')) {
                // 🔥 borrar anterior si existe
                if (!empty($exposicion->logo_exposicion)) {
                    Storage::disk('public')->delete($exposicion->logo_exposicion);
                }
                $file = $request->file('logo_exposicion'); // Generar nombre único
                $filename = Str::uuid() . '.' . $file->getClientOriginalExtension(); // Guardar en storage/app/public/logos_exposiciones
                $path = $file->storeAs('logos_exposiciones', $filename, 'public'); // Guardar en DB
                $exposicion->logo_exposicion = $path;
            }

            $exposicion->contacto_emails = $request->contacto_emails;
            $exposicion->modulo_catalogo = $request->modulo_catalogo == 'on' ? true : false;
            $exposicion->modulo_plano_stand = $request->modulo_plano_stand == 'on' ? true : false;
            $exposicion->modulo_elementos_opcionales = $request->modulo_elementos_opcionales == 'on' ? true : false;
            $exposicion->modulo_deposito_garantia = $request->modulo_deposito_garantia == 'on' ? true : false;
            $exposicion->modulo_credenciales = $request->modulo_credenciales == 'on' ? true : false;
            $exposicion->modulo_invitaciones = $request->modulo_invitaciones == 'on' ? true : false;
            $exposicion->modulo_sala_conferencia = $request->modulo_sala_conferencia == 'on' ? true : false;
            $exposicion->modulo_solicitar_energia = $request->modulo_solicitar_energia == 'on' ? true : false;
            $exposicion->modulo_servicios_optativos = $request->modulo_servicios_optativos == 'on' ? true : false;
            $exposicion->save();

            if ($exposicion->modulo_catalogo && !$exposicion->catalogo) {
                \App\Models\ModuloCatalogo::create(['id_exposicion' => $exposicion->id]);
            }
            if ($exposicion->modulo_invitaciones && !$exposicion->invitacion) {
                \App\Models\ModuloInvitacion::create(['id_exposicion' => $exposicion->id]);
            }
            if ($exposicion->modulo_plano_stand && !$exposicion->planoStand) {
                \App\Models\ModuloPlanoStand::create(['id_exposicion' => $exposicion->id]);
            }
            if ($exposicion->modulo_solicitar_energia && !$exposicion->electricidad) {
                \App\Models\ModuloElectricidad::create(['id_exposicion' => $exposicion->id]);
            }

            if ($request->hasFile('fileMapa')) {
                $archivo = $request->file('fileMapa');
                $url = Storage::putFile('public/mapa', $archivo);
                $exposicion->url_mapa = asset(Storage::url($url));
            }

            if ($request->hasFile('exposicion_archivo')) {
                $archivo = $request->file('exposicion_archivo');
                $nombreArchivo = uniqid() . '.' . $archivo->getClientOriginalExtension();
                $ruta = $archivo->storeAs('public/archivos', $nombreArchivo);

                \App\Models\ExposicionArchivo::create([
                    'id_exposicion' => $exposicion->id,
                    'original_name' => $archivo->getClientOriginalName(),
                    'tipo' => $request->exposicion_archivo_tipo,
                    'clase' => 'importante',
                    'path' => str_replace('public/', 'storage/', $ruta),
                    'pertenece_envase' => $request->exposicion_archivo_pertence == '1' ? true : false,
                    'importante' => $request->archivo_importante ? true : false,
                    'nivel' => (int) $request->archivo_nivel
                ]);
            }

            if ($request->hasFile('exposicion_archivo_difusion') && $request->hasFile('exposicion_archivo_difusion_muestra')) {
                $archivo = $request->file('exposicion_archivo_difusion');
                $nombreArchivo = uniqid() . '.' . $archivo->getClientOriginalExtension();
                $ruta = $archivo->storeAs('public/archivos', $nombreArchivo);

                $archivoMuestra = $request->file('exposicion_archivo_difusion_muestra');
                $nombreArchivoMuestra = uniqid() . '.' . $archivoMuestra->getClientOriginalExtension();
                $rutaMuestra = $archivoMuestra->storeAs('public/archivos', $nombreArchivoMuestra);

                \App\Models\ExposicionArchivo::create([
                    'id_exposicion' => $exposicion->id,
                    'original_name' => $archivo->getClientOriginalName(),
                    'tipo' => $request->exposicion_archivo_tipo_muestra,
                    'clase' => 'difusion',
                    'path_muestra' => str_replace('public/', 'storage/', $rutaMuestra),
                    'path' => str_replace('public/', 'storage/', $ruta),
                    'pertenece_envase' => $request->difusion_pertence == '1' ? true : false,
                ]);
            }

            if ($request->hasFile('terminos_condiciones')) {
                $archivo = $request->file('terminos_condiciones');
                $nombreArchivo = uniqid() . '.' . $archivo->getClientOriginalExtension();
                $ruta = $archivo->storeAs('public/archivos', $exposicion->id . '_terminos_condiciones' . $nombreArchivo);

                $exposicion->path_terminos_condiciones = str_replace('public/', 'storage/', $ruta);
            }

            if ($request->hasFile('terms_conditions')) {
                $archivo = $request->file('terms_conditions');
                $nombreArchivo = uniqid() . '.' . $archivo->getClientOriginalExtension();
                $ruta = $archivo->storeAs('public/archivos', $exposicion->id . '_terms_conditions' . $nombreArchivo);

                $exposicion->path_terms_conditions = str_replace('public/', 'storage/', $ruta);
            }

            $exposicion->save();

            $messageBag->add('success', 'Exposición guardada exitosamente');

            return back();

            //return redirect()->route('exposicion.create-2', ['id' => $exposicion->id]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function create2(Request $request)
    {

        // Create a new MessageBag instance flashed to the session.
        $messageBag = session()->get('messageBag') ?? new MessageBag;
        request()->session()->flash('messageBag', $messageBag);

        if (!$request->id) {
            //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
            return redirect()->route('home');
        } else {
            $exposicion = \App\Models\Exposicion::find($request->id);
        }

        $viewData = [
            "exposicion" => $exposicion
        ];

        $messageBag->add('warning', 'Importante: al crear un stand, luego de guardar, "Ingresar Nro" (Numero del Stand) y guardar nuevamente');
        $messageBag->add('warning', 'Esto es necesario para que el modulo de Asignacion de Stands detecte los LIBRES');
        $messageBag->add('warning', 'Puede crear primero todos los reacuadros de los stands, guardar, y luego ingresar todos los números y guardar');
        $messageBag->add('danger', 'Aquellos stands que no se hayan creado bajo el procedimiento indicado, no podrán ser utilizados por el sistema correctamente (ejemplo: stands importados)');

        return view('App.Exposicion.create2', ['viewData' => $viewData]);
    }

    public function create3(Request $request)
    {

        try {

            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::find($request->id);
                $list = [
                    'libre' => \App\Models\Stand::where('id_exposicion', '=', $exposicion->id)->where('status', '=', 'libre')->get(),
                    'reservado' => \App\Models\Stand::where('id_exposicion', '=', $exposicion->id)->where('status', '=', 'reservado')->get(),
                    'solicitud_pendiente' => \App\Models\Stand::where('id_exposicion', '=', $exposicion->id)->where('status', '=', 'solicitud_pendiente')->get(),
                    'pagado' => \App\Models\Stand::where('id_exposicion', '=', $exposicion->id)->where('status', '=', 'pagado')->get(),
                    'todos' => \App\Models\Stand::where('id_exposicion', '=', $exposicion->id)->get(),
                    'sumValue' => \App\Models\Stand::where('id_exposicion', '=', $exposicion->id)->sum('valor'),
                ];

                $status = [
                    'libre' => $list['libre']->count(),
                    'reservado' => $list['reservado']->count(),
                    'solicitud_pendiente' => $list['solicitud_pendiente']->count(),
                    'pagado' => $list['pagado']->count()
                ];
            }

            $viewData = [
                "exposicion" => $exposicion,
                "list" => $list,
                "status" => $status,
                "standTipo" => StandTipo::all()
            ];

            return view('App.Exposicion.create3', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateWeb(Request $request)
    {
        try {
            if ($request->entidad == 'etif') {
                DB::connection('external_db')->table('expo_id')
                    ->update(['etif' => DB::raw('NOT etif')]);
            }
            if ($request->entidad == 'envase') {
                DB::connection('external_db')->table('expo_id')
                    ->update(['envase' => DB::raw('NOT envase')]);
            }

            DB::connection('external_db')->table('expo_id')
                ->update(['id_selected_expo' => $request->id]);

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function switchArchivar(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $exposicion = Exposicion::find($request->id);
            $exposicion->archivada = $exposicion->archivada ? 0 : 1;
            $exposicion->save();

            $messageBag->add('success', 'Exposicion ' . ($exposicion->archivada ? 'archivada' : 'desarchivada') . ' con éxito.');

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteDocumentoImportante(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $item = \App\Models\ExposicionArchivo::find($request->id);
            $item->delete();

            $messageBag->add('success', 'Documento Importante eliminado con éxito');

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function switchEtif(Request $request) {
        try {
            
            $item = Exposicion::find($request->id);
            $item->etif = !$item->etif;
            $item->save();

            return response()->json([], 200);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function switchVisible(Request $request) {
        try {

            $exposicion = Exposicion::find($request->idExposicion);
            $exposicion->visible = !$exposicion->visible;
            $exposicion->save();

            return redirect()->route('exposicion.panel');

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
