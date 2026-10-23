<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use \App\Models\CredencialTipo;

class CredencialController extends Controller {

    public function panel() {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $viewData = [
                'credencial_tipo' => CredencialTipo::all()
            ];

            //$messageBag->add('success', '');

            return view('App.Credencial.panel', ['viewData' => $viewData]);
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
                $item = CredencialTipo::find($request->id);
            } else {
                $item = new CredencialTipo();
            }

            $item->descripcion = $request->descripcion;
            $item->save();

            $messageBag->add('success', $request->id ? 'Tipo de Credencial actualizada con éxito' : 'Tipo de credencial creada con éxito');

            return back();

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}