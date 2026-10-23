<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Models\Notificacion;
use App\Models\NotificacionExpositor;
use App\Models\Expositor;

class ModuloNotificacionController extends Controller
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
                $exposicion = \App\Models\Exposicion::with('invitacion')->find($request->id);
            }

            $viewData = [
                "exposicion" => $exposicion,
                "notificaciones" => Notificacion::where('id_exposicion', '=', $exposicion->id)->orderBy('created_at', 'desc')->get()
            ];

            //dd($viewData);
            //$messageBag->add('success', '');

            return view('App.Modulo.notificacion', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

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
                $exposicion = \App\Models\Exposicion::with('invitacion')->find($request->id);
            }

            $notificacion = null;

            if ($request->idNotificacion) {
                $notificacion = Notificacion::find($request->idNotificacion);
            }

            $viewData = [
                "exposicion" => $exposicion,
                "notificacion" => $notificacion
            ];

            //dd($viewData);
            //$messageBag->add('success', '');

            return view('App.Modulo.notificacion-create', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function store(Request $request)
    {
        try {

            //dd($request->all());

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            if ($request->idNotificacion) {
                $notificacion = Notificacion::find($request->idNotificacion);
            } else {
                $notificacion = new Notificacion();
            }

            $notificacion->id_exposicion = $request->idExposicion;
            $notificacion->descripcion = $request->descripcion;
            $notificacion->save();

            $expositores = Expositor::where('id_exposicion', '=', $request->idExposicion)->get();

            if (!$request->idNotificacion) {
                foreach ($expositores as $key => $expositor) {
                    $notificacionExspositor = new NotificacionExpositor();
                    $notificacionExspositor->id_notificacion = $notificacion->id;
                    $notificacionExspositor->id_expositor = $expositor->id;
                    $notificacionExspositor->leido = false;
                    $notificacionExspositor->save();
                }
            }

            $messageBag->add('success', 'Notificación ' . ($request->idNotificacion ? 'actualizada' : 'creada') . ' con éxito');

            return redirect()->route('modulo.notificacion.panel', ['id' => $request->idExposicion]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function delete(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $notificacion = Notificacion::find($request->idNotificacion);
            $notificacion->delete();

            NotificacionExpositor::where('id_notificacion', '=', $request->idNotificacion)->delete();

            $messageBag->add('success', 'Notificación eliminada con éxito');

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
