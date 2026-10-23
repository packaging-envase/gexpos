<?php

namespace App\Http\Controllers;

use App\Models\Variables;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ConfiguracionController extends Controller
{

    public function index(Request $request)
    {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            //$messageBag->add('success', '');

            $viewData = [
                'logo_login' => Variables::where('descripcion','=','logo_login')->first()['value_string']
            ];

            return view('App.Configuracion.index', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update(Request $request)
    {
        try {

            $path = null;

            if ($request->hasFile('logo_login')) {

                // Guardar archivo en storage/app/public/logos
                $file = $request->file('logo_login');

                $filename = uniqid() . '.' . $file->getClientOriginalExtension();

                $path = $file->storeAs('logos', $filename, 'public');

                $variable = Variables::where('descripcion', '=', 'logo_login')->first();
                $variable->value_string = $path;
                $variable->save();
            }

            return redirect()
                ->back()
                ->with('messageBag', tap(new MessageBag, function ($bag) {
                    $bag->add('success', 'Configuración actualizada correctamente');
                }));
        } catch (\Throwable $th) {

            Log::error('Error al actualizar configuración: ' . $th->getMessage());

            return redirect()
                ->back()
                ->with('messageBag', tap(new MessageBag, function ($bag) {
                    $bag->add('error', 'Ocurrió un error al guardar la configuración');
                }));
        }
    }
}
