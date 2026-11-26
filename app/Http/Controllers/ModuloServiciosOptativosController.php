<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;

class ModuloServiciosOptativosController extends Controller {

    public function panel(Request $request) {
        
        try {
            
            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::with(['serviciosOptativos'])->find($request->id);
            }

            /*
            $item = null;
            if ($request->itemId) {
                $item = \App\Models\ModuloServiciosOptativos::find($request->itemId);
            }
            */

            $viewData = [
                "exposicion" => $exposicion,
                //"item" => $item
            ];

            //dd($viewData);

            return view('App.Modulo.serviciosOptativos', ['viewData' => $viewData]);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function create(Request $request) {

        try {

            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::with(['serviciosOptativos'])->find($request->id);
            }

            $item = null;

            if ($request->idItem) {
                $item = \App\Models\ModuloServiciosOptativos::
                    with('moduloServiciosOptativosFile')
                    ->find($request->idItem);
            }

            $viewData = [
                'exposicion' => $exposicion,
                'item' => $item
            ];
            
            return view('App.Modulo.serviciosOptativosCreate', ['viewData' => $viewData]);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateStore(Request $request) {

        try {
            
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $item = null;
            if ($request->id) {
                $item = \App\Models\ModuloServiciosOptativos::find($request->id);
            } else {
                $item = new \App\Models\ModuloServiciosOptativos();
            }

            $item->id_exposicion = $request->idExposicion;
            $item->nombre = $request->nombre;
            $item->descripcion = $request->descripcion;
            $item->active = $request->active == 'on' ? true : false;

            if ($request->hasFile('logo')) {

                // Obtener el archivo cargado
                $file = $request->file('logo');
            
                // Guardar el archivo y obtener la ruta almacenada
                $path = $file->store('public/serviciosOptativos/logos/');
            
                // Extraer el nombre del archivo para almacenarlo en la base de datos
                $filename = basename($path);
            
                // Asignar la ruta del archivo al modelo
                $item->logo = $filename;
            }
            $item->save();

            if (isset($request->archivos) && count($request->archivos)) {
                foreach ($request->archivos as $key => $file) {

                    $newFile = new \App\Models\ModuloServiciosOptativosFile();
                    $newFile->id_mod_serv_opt = $item->id;
                    $newFile->name = $file->getClientOriginalName();
                    $newFile->path = $file->store("public/serviciosOptativos/documentos");
                    $newFile->path = str_replace("public/serviciosOptativos/documentos/", '', $newFile->path);
                    $newFile->save();
                }
            }

            $messageBag->add('success', 'Proveedor Optativo ' . ($request->id ? 'actualizado' : 'guardado') . ' con éxito!');

            return redirect()->route('modulo.servicios-optativos.create', ['id' => $request->idExposicion, 'idItem' => $item->id]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteFile(Request $request) {

        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);
            
            $item = \App\Models\ModuloServiciosOptativosFile::find($request->id);
            $item->delete();

            $messageBag->add('success', 'Archivo eliminado con éxito!');

            return back();

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}