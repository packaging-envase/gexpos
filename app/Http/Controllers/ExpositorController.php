<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use App\Mail\RestaurarPasswordEmail;
use App\Models\ExposicionArchivo;
use App\Models\Expositor;
use App\Models\Exposicion;
use App\Models\ModuloCatalogo;
use App\Models\ModuloElectricidad;
use App\Models\ModuloElementosOpcionales;
use App\Models\ModuloInvitacion;
use App\Models\ModuloSalaConferenciaTexto;
use App\Models\Variables;

class ExpositorController extends Controller
{
    public function panelExpositor(Request $request)
    {

        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $idExposicion = Auth::guard('expositor')->user()->id_exposicion;
            $idEmpresa = Auth::guard('expositor')->user()->id_empresa;

            $exposicion = \App\Models\Exposicion::find($idExposicion);
            $empresa = \App\Models\Empresa::find($idEmpresa);

            $vencimientos = [];

            $catalogo = ModuloCatalogo::where('id_exposicion', '=', $idExposicion)->first();
            if ($catalogo) {
                $vencimientos['catalogo'] = \DateTime::createFromFormat('Y-m-d', $catalogo->cierre)->format('d/m/Y');
            } else {
                $vencimientos['catalogo'] = '';
            }

            $credenciales = Variables::where('id_exposicion', '=', $idExposicion)->where('descripcion', '=', 'credencial_vencimiento')->first();
            if ($credenciales) {
                $vencimientos['credenciales'] = \DateTime::createFromFormat('Y-m-d', $credenciales['value_date'])->format('d/m/Y');
            } else {
                $vencimientos['credenciales'] = '';
            }

            $elementosOpcionales = ModuloElementosOpcionales::where('id_exposicion','=',$idExposicion)->first();
            if ($elementosOpcionales) {
                $vencimientos['elementos'] = \DateTime::createFromFormat('Y-m-d', $elementosOpcionales['cierre'])->format('d/m/Y');
            } else {
                $vencimientos['elementos'] = '';
            }

            $electricidad = ModuloElectricidad::where('id_exposicion','=',$idExposicion)->first();
            if ($electricidad) {
                $vencimientos['electricidad'] = \DateTime::createFromFormat('Y-m-d', $electricidad['cierre'])->format('d/m/Y');
            } else {
                $vencimientos['electricidad'] = '';
            }

            $invitaciones = ModuloInvitacion::where('id_exposicion','=',$idExposicion)->first();
            if ($invitaciones) {
                $vencimientos['invitaciones'] = \DateTime::createFromFormat('Y-m-d', $invitaciones['cierre'])->format('d/m/Y');
            } else {
                $vencimientos['invitaciones'] = '';
            }

            $salaConferencia = ModuloSalaConferenciaTexto::where('id_exposicion','=',$idExposicion)->first();
            if ($salaConferencia) {
                $vencimientos['salaConferencia'] = \DateTime::createFromFormat('Y-m-d', $salaConferencia['cierre'])->format('d/m/Y');
            } else {
                $vencimientos['salaConferencia'] = '';
            }

            $viewData = [
                'exposicion' => $exposicion,
                'empresa' => $empresa,
                'vencimientos' => $vencimientos
            ];

            return view('App.Expositor.panelExpositor', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function changePasswordView(Request $request)
    {
        try {
            return view('App.Expositor.cambiarPassword');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function changePassword(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $expositor = Expositor::find(Auth::guard('expositor')->user()->id);
            $expositor->password = $request->password;
            $expositor->cambio_password = true;
            $expositor->save();

            $messageBag->add('success', 'Contraseña modificada con éxito');

            return redirect()->intended('expositor/panel-expositor');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function resetPassword(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $token = bin2hex(random_bytes(50 / 2));

            $expositor = Expositor::where('usuario', '=', $request->usuario)->first();
            if (!$expositor) {
                $messageBag->add('danger', 'El email ingresado no se encuentra registrado en el sistema');
                return redirect()->route('login');
            }
            $expositor->reset_password_token = $token;
            $expositor->save();

            $details = [
                'title' => 'Restauración de Contraseña',
                'body' => 'Por favor, ingrese al siguiente link para restaurar su contraseña',
                'token' => $token
            ];

            Mail::to($expositor->usuario)->send(new RestaurarPasswordEmail($details));

            $messageBag->add('success', 'Le llegará un email para continuar con la restauración de contraseña');

            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function resetPasswordToken($token)
    {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $expositor = Expositor::where('reset_password_token', '=', $token)->first();

            if (!$expositor) {
                $messageBag->add('success', 'Bad Request');
                return back();
            }

            $viewData = [
                'token' => $token
            ];

            return view('App.Expositor.cambiarPassword', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function resetPasswordConfirm(Request $request)
    {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $expositor = Expositor::where('reset_password_token', '=', $request->token)->first();

            if (!$expositor) {
                $messageBag->add('success', 'Bad Request');
                return back();
            }

            $expositor->reset_password_token = null;
            $expositor->password = $request->password;
            $expositor->cambio_password = true;
            $expositor->save();

            $messageBag->add('success', 'Contraseña restaurada con éxito!');

            return redirect()->route('login');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function notificacionesPanel(Request $request)
    {
        try {

            $notificaciones = \App\Models\NotificacionExpositor::leftJoin('notificacion', 'notificacion.id', '=', 'notificacion_expositor.id_notificacion')
                ->select(
                    'notificacion.id',
                    'notificacion.descripcion',
                    'notificacion.created_at'
                )
                ->groupBy('notificacion.id', 'notificacion.descripcion', 'notificacion.created_at')
                ->where('notificacion.deleted_at', '=', null)
                ->where('id_expositor', '=', Auth::guard('expositor')->user()->id)
                ->orderBy('notificacion.created_at', 'desc')
                ->get();

            //dd(Auth::guard('expositor')->user()->id);

            $exposicion = \App\Models\Exposicion::find(Auth::guard('expositor')->user()->id_exposicion);

            $viewData = [
                'notificaciones' => $notificaciones,
                'exposicion' => $exposicion
            ];

            return view('App.Expositor.notificacionesExpositor', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function documentosImportantesPanel()
    {
        try {

            $idExposicion = Auth::guard('expositor')->user()->id_exposicion;
            $idEmpresa = Auth::guard('expositor')->user()->id_empresa;

            $empresa = \App\Models\Empresa::find($idEmpresa);
            $exposicion = \App\Models\Exposicion::with(['exposicionArchivo' => function ($query) {
                $query->where('clase', '=', 'importante');
                $query->where('pertenece_envase','=',(!Auth::guard('expositor')->user()->pertenece_etif));
                $query->orderBy('importante', 'desc');
                $query->orderBy('nivel', 'desc');
                $query->orderBy('tipo', 'asc');

            }])->find($idExposicion);


            // Modificar el path de los archivos
            if ($exposicion && $exposicion->exposicionArchivo) {
                foreach ($exposicion->exposicionArchivo as $archivo) {
                    $archivo->path = asset($archivo->path); // Generar la URL completa
                }
            }

            $viewData = [
                'exposicion' => $exposicion,
                'empresa' => $empresa,
            ];

            return view('App.Expositor.documentosImportantes', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function terminosCondiciones(Request $request)
    {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $idExposicion = Auth::guard('expositor')->user()->id_exposicion;

            $exposicion = \App\Models\Exposicion::with('exposicionArchivo')->find($idExposicion);

            $viewData = [
                'exposicion' => $exposicion,
            ];

            $messageBag->add('success', "Al hacer clic en 'Aceptar', confirma que ha leído y acepta los términos y condiciones.");

            return view('App.Expositor.terminosCondiciones', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function moduloMaterialDifusionPanel()
    {
        try {
            $idExposicion = Auth::guard('expositor')->user()->id_exposicion;

            $idEmpresa = Auth::guard('expositor')->user()->id_empresa;
            $empresa = \App\Models\Empresa::find($idEmpresa);

            $difusiones = ExposicionArchivo::where('clase', '=', 'difusion')
                ->where('id_exposicion', '=', $idExposicion)
                ->where('pertenece_envase','=',(!Auth::guard('expositor')->user()->pertenece_etif))
                ->get();

            $viewData = [
                'difusiones' => $difusiones,
                'exposicion' => Exposicion::select('nombre')->where('id', '=', $idExposicion)->first(),
                'empresa' => $empresa
            ];

            return view('App.ModuloExpositor.difusion', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function listExposiciones(Request $request) {
        try {

            $expositor = Auth::guard('expositor')->user();

            $expositores = Expositor::where('usuario','=',$expositor->usuario)
                ->get()
                ->toArray();

            //dd($expositor->usuario);
            //dd($expositores);

            $exposiciones = [];
            
            for ($i=0; $i < count($expositores); $i++) { 
                $exposicion = Exposicion::
                    where('visible','=',true)->where('id','=',$expositores[$i]['id_exposicion'])->first();

                if ($exposicion) {
                    $exposiciones[] = $exposicion;
                }
                
            }

            $viewData = [
                'exposiciones' => $exposiciones
            ];
            
            return view('App.Expositor.listExposiciones', ['viewData' => $viewData] );

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function expositorLogin(Request $request) {
        try {
            
            /*
            $expositor = \App\Models\Expositor::where('usuario', $request->email)
                ->where('id_exposicion','=',$request->idExposicion)
                ->first();
            */

            $expositor = Auth::guard('expositor')->user();

            $expositor = \App\Models\Expositor::where('usuario', $expositor->usuario)
                ->where('id_exposicion','=',$request->idExposicion)
                ->first();

            //dd($expositor);

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
                    return redirect()->intended('dashboard');
                    //return redirect()->route('expositor.list-exposiciones');
                }

                return redirect()->intended('expositor/cambiar-contraseña');
            }

        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
