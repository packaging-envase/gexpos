<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ExpositorModuloPlanoStandController extends Controller
{
    public function index(Request $request)
    {

        if (!Auth::guard('expositor')->check()) {
            $idExposicion = $request->idExposicion;
            $idExpositor = $request->idExpositor;
        } else {
            $idExposicion = Auth::guard('expositor')->user()->id_exposicion;
            $idExpositor = Auth::guard('expositor')->user()->id;
        }


        $viewData = [
            'plano_stand' => \App\Models\ModuloPlanoStand::with('expositorModuloPlanoStand')
                ->where('id_exposicion', '=', $idExposicion)
                ->first(),
            'pedido' => \App\Models\ExpositorModuloPlanoStand::with([
                    'expositorModuloPlanoStandFile',
                    'moduloPlanoStandComentario'
                ])
                ->where('id_exposicion', '=', $idExposicion)
                ->where('id_expositor', '=', $idExpositor)
                ->first(),
            'exposicion' => \App\Models\Exposicion::find($idExposicion),
            'empresa' => \App\Models\Empresa::find(Auth::guard('expositor')->user()->id_empresa)
        ];

        //dd($viewData,$idExpositor);

        return view('App.ModuloExpositor.planoStand', ['viewData' => $viewData]);
    }

    public function updateStore(Request $request)
    {

        try {

            //dd($request->all());

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            if (!Auth::guard('expositor')->check()) {
                $idExposicion = $request->idExposicion;
                $idExpositor = $request->idExpositor;
            } else {
                $idExposicion = Auth::guard('expositor')->user()->id_exposicion;
                $idExpositor = Auth::guard('expositor')->user()->id;
            }

            $expositorModuloPlanoStand = \App\Models\ExpositorModuloPlanoStand::where('id_exposicion', '=', $idExposicion)
                ->where('id_expositor', '=', $idExpositor)
                ->first();

            if (!$expositorModuloPlanoStand) {
                $expositorModuloPlanoStand = new \App\Models\ExpositorModuloPlanoStand();
                $expositorModuloPlanoStand->id_expositor = $idExpositor = Auth::guard('expositor')->user()->id;
                $expositorModuloPlanoStand->id_exposicion = $idExposicion = Auth::guard('expositor')->user()->id_exposicion;
                $expositorModuloPlanoStand->save();
                //$expositorModuloPlanoStand->id_modulo_plano_stand
            }

            $comentario = null;
            if ($request->comentario) {
                $comentario = new \App\Models\ModuloPlanoStandComentario();
                $comentario->descripcion = $request->comentario;
                $comentario->id_exp_mod_plano_stand = $expositorModuloPlanoStand->id;
                if (Auth::guard('expositor')->check()) {
                    $comentario->id_expositor = $idExpositor;
                }
                $comentario->save();
            }

            if ($request->status) {
                $expositorModuloPlanoStand->status = $request->status;

                foreach ($request->fileStatus as $key => $file) {
                    // Divide el valor en id y estado
                    list($fileId, $status) = explode(':', $file);

                    // Convierte el estado a booleano (true o false)
                    $aprobado = ($status === 'true') || $status == 1 ? 1 : 0;

                    // Encuentra el registro correspondiente en la base de datos
                    $expositorFile = \App\Models\ExpositorModuloPlanoStandFile::find($fileId);

                    // Si el registro existe, actualiza la columna 'aprobado'
                    if ($expositorFile) {
                        $expositorFile->aprobado = $aprobado;
                        $expositorFile->save();
                    }
                }
            } else {
                $expositorModuloPlanoStand->status = 'presentado';                
            }

            if ($request->nombre) {
                $expositorModuloPlanoStand->nombre = $request->nombre;
            }
            if ($request->apellido) {
                $expositorModuloPlanoStand->apellido = $request->apellido;
            }
            if ($request->telefono) {
                $expositorModuloPlanoStand->telefono = $request->telefono;
            }
            if ($request->email) {
                $expositorModuloPlanoStand->email = $request->email;
            }

            if (Auth::guard()->check() || Auth::guard('expositor')->check()) {
                $expositorModuloPlanoStand->updated_at_expositor = date('Y-m-d');
            }

            $expositorModuloPlanoStand->save();

            $lastNumeroEnvio = \App\Models\ExpositorModuloPlanoStandFile::where('id_exp_mod_plano_stand','=',$expositorModuloPlanoStand->id)->orderBy('numero_envio','desc')->first();
            if ($lastNumeroEnvio) {
                $lastNumeroEnvio = $lastNumeroEnvio->numero_envio + 1;
            } else {
                $lastNumeroEnvio = 1;
            }

            if (isset($request->archivos) && count($request->archivos)) {
                foreach ($request->archivos as $key => $file) {
                    $newFile = new \App\Models\ExpositorModuloPlanoStandFile();
                    $newFile->id_exp_mod_plano_stand = $expositorModuloPlanoStand->id;
                    $newFile->name = $file->getClientOriginalName();
                    $newFile->path = $file->store("public/planoStand/expositor");
                    $newFile->path = str_replace("public/planoStand/expositor/", '', $newFile->path);
                    $newFile->numero_envio = $lastNumeroEnvio;
                    $newFile->save();
                }
            }

            if (Auth::guard('expositor')->check()) {
                $messageBag->add('success', 'Planos enviados con éxito!');
            } else {
                $messageBag->add('success', 'Pedido de planos actualizado con éxito!');
            }
            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function downloadFile(Request $request)
    {

        try {

            $file = \App\Models\ExpositorModuloPlanoStandFile::where('path','=',$request->path)->first();

            return response()->download(
                storage_path('app/public/planoStand/expositor/' . $file->path),
                $file->name
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateToNewRequirement() {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $idExposicion = null;
            $idExpositor = null;
            if (Auth::guard('expositor')->check()) {
                $idExposicion = Auth::guard('expositor')->user()->id_exposicion;
                $idExpositor = Auth::guard('expositor')->user()->id;
            } else {
                return back();
            }            

            $expositorModuloPlanoStand = \App\Models\ExpositorModuloPlanoStand::where('id_exposicion', '=', $idExposicion)
                ->where('id_expositor', '=', $idExpositor)
                ->first();

            if ($expositorModuloPlanoStand) {
                $expositorModuloPlanoStand->update(['status' => 'nuevo requerimiento']);
                $messageBag->add('success', 'Formulario de envio de plano de stand modificado a: Nuevo Requerimiento!');
            } else {
                $messageBag->add('danger', 'No se encontro el formulario! Contacte con el área de sistemas');
            }

            return back();

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
