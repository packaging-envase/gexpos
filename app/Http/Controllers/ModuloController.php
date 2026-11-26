<?php

namespace App\Http\Controllers;

use App\Jobs\EnviarRecordatorioModulosJob;
use App\Models\CredencialDetalle;
use App\Models\Expositor;
use App\Models\ExpositorModuloCatalogo;
use App\Models\ExpositorModuloDepositoGarantia;
use App\Models\ExpositorModuloElectricidad;
use App\Models\ExpositorModuloElementosOpcionales;
use App\Models\ExpositorModuloInvitacion;
use App\Models\ExpositorModuloPlanoStand;
use App\Models\ExpositorModuloSalaConferencia;
use App\Models\Stand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;

use Illuminate\Support\Facades\Mail;

use App\Models\EmailPredefinido;
use App\Models\Exposicion;

use App\Mail\EnviarEmail;
use App\Mail\EnviarEmailPredefinido;

class ModuloController extends Controller
{

    public function invitaciones(Request $request)
    {

        try {
            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::find($request->id);
            }

            $viewData = [
                "exposicion" => $exposicion
            ];

            return view('App.Modulo.invitaciones', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function panelControl(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::with('electricidad')->find($request->id);
            }

            $expositores = Expositor::leftJoin('empresa', 'empresa.id', '=', 'expositor.id_empresa')
                ->select(
                    'expositor.id',
                    'empresa.razon_social',
                    'usuario'
                )
                ->where('expositor.id_exposicion', '=', $exposicion['id'])
                ->get()
                ->toArray();

            //expositor_modulo_catalogo
            //expositor_modulo_deposito_garantia
            //expositor_modulo_electricidad
            //expositor_modulo_elementos_opcionales
            //expositor_modulo_invitacion
            //expositor_modulo_plano_stand
            //expositor_modulo_sala_conferencia
            //credencial_detalle

            $expositoresConPendientes = [];

            foreach ($expositores as $keyExpositor => $expositor) {

                $catalogo = ExpositorModuloCatalogo::where('id_expositor', '=', $expositor['id'])->first();
                if ($catalogo) {
                    $expositores[$keyExpositor]['catalogo'] = $catalogo->updated_at_expositor ? \DateTime::createFromFormat('Y-m-d', $catalogo->updated_at_expositor)->format('d/m/Y') : '';
                } else {
                    $expositores[$keyExpositor]['catalogo'] = '';
                }

                $deposito = ExpositorModuloDepositoGarantia::where('id_expositor', '=', $expositor['id'])->first();
                if ($deposito) {
                    $expositores[$keyExpositor]['deposito'] = $deposito->updated_at_expositor ? \DateTime::createFromFormat('Y-m-d', $deposito->updated_at_expositor)->format('d/m/Y') : '';
                } else {
                    $expositores[$keyExpositor]['deposito'] = '';
                }

                $electricidad = ExpositorModuloElectricidad::where('id_expositor', '=', $expositor['id'])->first();
                if ($electricidad) {
                    $expositores[$keyExpositor]['electricidad'] = $electricidad->updated_at_expositor ? \DateTime::createFromFormat('Y-m-d', $electricidad->updated_at_expositor)->format('d/m/Y') : '';
                } else {
                    $expositores[$keyExpositor]['electricidad'] = '';
                }

                $elementos = ExpositorModuloElementosOpcionales::where('id_expositor', '=', $expositor['id'])->first();
                if ($elementos) {
                    $expositores[$keyExpositor]['elementos'] = $elementos->updated_at_expositor ? \DateTime::createFromFormat('Y-m-d', $elementos->updated_at_expositor)->format('d/m/Y') : '';
                } else {
                    $expositores[$keyExpositor]['elementos'] = '';
                }

                $invitacion = ExpositorModuloInvitacion::where('id_expositor', '=', $expositor['id'])->first();
                if ($invitacion) {
                    $expositores[$keyExpositor]['invitacion'] = $invitacion->updated_at_expositor ? \DateTime::createFromFormat('Y-m-d', $invitacion->updated_at_expositor)->format('d/m/Y') : '';
                } else {
                    $expositores[$keyExpositor]['invitacion'] = '';
                }

                /*
                $plano = ExpositorModuloPlanoStand::where('id_expositor','=',$expositor['id'])->first();
                if ($plano) {
                    $expositores[$keyExpositor]['plano'] = $plano->updated_at_expositor ? \DateTime::createFromFormat('Y-m-d', $plano->updated_at_expositor)->format('d/m/Y') : '';
                } else {
                    $expositores[$keyExpositor]['plano'] = '';
                }
                */

                $conferencia = ExpositorModuloSalaConferencia::where('id_expositor', '=', $expositor['id'])->first();
                if ($conferencia) {
                    $expositores[$keyExpositor]['conferencia'] = $conferencia->updated_at_expositor ? \DateTime::createFromFormat('Y-m-d', $conferencia->updated_at_expositor)->format('d/m/Y') : '';
                } else {
                    $expositores[$keyExpositor]['conferencia'] = '';
                }

                $credencial = CredencialDetalle::where('id_expositor', '=', $expositor['id'])->orderBy('updated_at_expositor', 'desc')->first();
                if ($credencial) {
                    $expositores[$keyExpositor]['credencial'] = $credencial->updated_at_expositor ? \DateTime::createFromFormat('Y-m-d', $credencial->updated_at_expositor)->format('d/m/Y') : '';
                    if ($credencial->updated_at_expositor) {
                        //dd($expositores[$keyExpositor]);
                    }
                } else {
                    $expositores[$keyExpositor]['credencial'] = '';
                }

                if ($request->emailRecordatorio && $expositor['usuario'] == 'marcelo.ismael.mdp@gmail.com') {
                    $pendientes = [];

                    if (!$catalogo || !$catalogo->updated_at_expositor) {
                        $pendientes[] = [
                            'modulo' => 'Catálogo',
                            'fecha_limite' => '10/08/2025'
                        ];
                    }

                    if (!$deposito || !$deposito->updated_at_expositor) {
                        $pendientes[] = [
                            'modulo' => 'Depósito de garantía',
                            'fecha_limite' => '15/08/2025'
                        ];
                    }

                    if (!$electricidad || !$electricidad->updated_at_expositor) {
                        $pendientes[] = [
                            'modulo' => 'Electricidad',
                            'fecha_limite' => '12/08/2025'
                        ];
                    }

                    if (!$elementos || !$elementos->updated_at_expositor) {
                        $pendientes[] = [
                            'modulo' => 'Elementos opcionales',
                            'fecha_limite' => '18/08/2025'
                        ];
                    }

                    if (!$invitacion || !$invitacion->updated_at_expositor) {
                        $pendientes[] = [
                            'modulo' => 'Invitaciones',
                            'fecha_limite' => '20/08/2025'
                        ];
                    }

                    if (!$conferencia || !$conferencia->updated_at_expositor) {
                        $pendientes[] = [
                            'modulo' => 'Sala de conferencia',
                            'fecha_limite' => '22/08/2025'
                        ];
                    }

                    if (!$credencial || !$credencial->updated_at_expositor) {
                        $pendientes[] = [
                            'modulo' => 'Credenciales',
                            'fecha_limite' => '25/08/2025'
                        ];
                    }

                    if (count($pendientes) > 0 && ( $expositor['usuario'] == 'administracion@envase.org')) {
                        $expositoresConPendientes[] = [
                            'expositor' => $expositor,
                            'pendientes' => $pendientes
                        ];
                    }                    
                }

                $stands = Stand::where('id_expositor', '=', $expositor['id'])->get();
                foreach ($stands as $keyStand => $stand) {
                    if (!isset($expositores[$keyExpositor]['stand'])) {
                        $expositores[$keyExpositor]['stand'] = '';
                    }
                    $expositores[$keyExpositor]['stand'] = $expositores[$keyExpositor]['stand'] . ' ' . $stand['numero'];
                }
            }

            if ($request->emailRecordatorio) {
                EnviarRecordatorioModulosJob::dispatch($expositoresConPendientes);
                return redirect()->route('modulo.panel-control', ['id' => $exposicion['id']])->with('success', 'Emails recordatorio enviandose con éxito.');
            }            

            $viewData = [
                'expositores' => $expositores,
                'exposicion' => $exposicion
            ];

            return view('App.Modulo.panel-control', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
