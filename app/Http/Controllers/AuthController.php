<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Variables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Session\TokenMismatchException;

class AuthController extends Controller
{
    public function showLoginForm()
    {

        if (Auth::guard('expositor')->user()) {
            $expositor = Auth::guard('expositor')->user();

            if ($expositor->cambio_password) {
                return redirect()->intended('dashboard');
            }
            //return redirect()->intended('expositor/panel-expositor');
            return redirect()->intended('expositor/cambiar-contraseña');
        }

        if (Auth::guard()->user()) {
            if (Auth::guard()->user()->grupo == 3) {
                return redirect()->route('expositor.panel-administracion', ['id' => \App\Models\Exposicion::orderBy('created_at', 'desc')->first()['id']]);
            }
            return redirect()->intended('dashboard');
        }

        $viewData = [
                'logo_login' => Variables::where('descripcion','=','logo_login')->first()['value_string']
            ];

        return view('auth.login', ['viewData' => $viewData]);
    }

    public function login(Request $request)
    {
        try {
            // Verificar la dirección IP
            if ($request->ip() !== '127.0.0.1') {
                $request->validate([
                    'g-recaptcha-response' => 'required|captcha',
                ]);
            }

            $credentials = $request->only('email', 'password');

            $expositor = \App\Models\Expositor::where('usuario', $request->email)
                ->where('password', $request->password)
                ->first();

            if ($expositor) {
                Auth::guard('expositor')->login($expositor);

                $notificaciones = \App\Models\NotificacionExpositor::leftJoin('notificacion', 'notificacion.id', '=', 'notificacion_expositor.id_notificacion')
                    ->select('notificacion.descripcion')
                    ->where('id_expositor', $expositor->id)
                    ->where('leido', false)
                    ->get();

                \App\Models\NotificacionExpositor::where('id_expositor', $expositor->id)
                    ->where('leido', false)
                    ->update(['leido' => true]);

                if (count($notificaciones)) {
                    $viewData = ['notificaciones' => $notificaciones];
                    return view('App.Expositor.notificacionExpositor', ['viewData' => $viewData]);
                }

                if ($expositor->cambio_password) {
                    //return redirect()->intended('dashboard');
                    return redirect()->route('expositor.list-exposiciones');
                }

                return redirect()->intended('expositor/cambiar-contraseña');
            }

            if (Auth::attempt($credentials)) {
                if (Auth::guard()->user()->grupo == 3) {
                    //$idEtifExposicion = Variables::where('descripcion','=','etif_exposicion')->first()['value_string'];
                    //$idExposcion = \App\Models\Exposicion::where('descripcion','=',)->orderBy('created_at', 'desc')->first()['id'];

                    return redirect()->intended('exposicion/panel');
                }
                return redirect()->intended('dashboard');
            }

            return redirect()->back()->withErrors(['email' => 'These credentials do not match our records.']);
        } catch (TokenMismatchException $e) {
            // Captura error 419 y redirige a login con flag para recargar
            return redirect()->route('login')->with('token_expired', true);
        }
    }

    

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validation logic here...

        // Create a new user...

        // Sign in the user...

        return redirect()->intended('dashboard');
    }

    public function logout()
    {
        Auth::logout();

        Auth::guard('expositor')->logout();

        return redirect('/login');
    }
}
