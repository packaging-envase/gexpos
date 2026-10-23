<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ExpositorModuloServiciosOptativosController extends Controller {

    public function index() {

        try {

            $idExposicion = Auth::guard('expositor')->user()->id_exposicion;

            $viewData = [
                'exposicion' => \App\Models\Exposicion::find($idExposicion),
                'optativos' => \App\Models\ModuloServiciosOptativos::
                    with('moduloServiciosOptativosFile')
                    ->where('id_exposicion','=',$idExposicion)
                    ->where('active','=',true)
                    ->orderBy('nombre')
                    ->get(),
                'empresa' => \App\Models\Empresa::find(Auth::guard('expositor')->user()->id_empresa)
            ];

            return view('App.ModuloExpositor.serviciosOptativos', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }        
    }

    public function downloadFile(Request $request)
    {

        try {

            $file = \App\Models\ModuloServiciosOptativosFile::where('path','=',$request->path)->first();

            return response()->download(
                storage_path('app/public/serviciosOptativos/documentos/' . $file->path),
                $file->name
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}