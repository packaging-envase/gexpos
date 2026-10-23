<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\EmailPredefinido;
use Illuminate\Support\Facades\Mail;
use App\Models\EmailPredefinidoToExpositor;
use App\Models\Exposicion;
use Illuminate\Support\Facades\Log;

class EnviarRecordatorioModulosJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $expositores;

    public function __construct($expositores)
    {
        $this->expositores = $expositores;
    }

    public function handle()
    {

        try {

        foreach ($this->expositores as $data) {

            $expositor = $data['expositor'];
            $pendientes = $data['pendientes'];

            if (count($pendientes) == 0) {
                continue;
            }

            $rows = '';

            foreach ($pendientes as $pendiente) {

                $rows .= '
                <tr>
                    <td style="padding:10px;border-bottom:1px solid #ddd;">' . $pendiente['modulo'] . '</td>
                    <td style="padding:10px;border-bottom:1px solid #ddd;color:#c0392b;font-weight:bold;">' . $pendiente['fecha_limite'] . '</td>
                </tr>';
            }

            $html = '
                <!DOCTYPE html>
                <html>

                <head>
                    <meta charset="utf-8">
                </head>

                <body style="font-family:Arial, Helvetica, sans-serif;background:#f4f4f4;padding:20px;">
                    <div style="max-width:700px;margin:auto;background:white;padding:30px;border-radius:6px;">
                        <div style="background:#1a3d7c;color:white;padding:15px;font-size:20px;font-weight:bold;"> Expo Envase - ETIF
                            2025 </div>
                        <div style="margin-top:20px;font-size:14px;"> Estimado, <br><br> Detectamos que aún no se han completado algunos
                            módulos obligatorios del sistema de expositores. <br><br> Le solicitamos completar los siguientes módulos
                            antes de su fecha límite: </div>
                        <table style="width:100%;border-collapse:collapse;margin-top:20px;">
                            <tr style="background:#1a3d7c;color:white;">
                                <th style="padding:10px;text-align:left;">Módulo</th>
                                <th style="padding:10px;text-align:left;">Fecha límite</th>
                            </tr> '
                            . $rows .
                            '
                        </table>
                        <div style="margin-top:25px;text-align:center;"> <a href="https://gexpos.com/"
                                style=" background:#1a3d7c; color:white; padding:12px 20px; text-decoration:none; border-radius:4px; display:inline-block; font-weight:bold; ">
                                Ingresar al sistema de expositores </a> </div>
                        <div style="margin-top:25px;font-size:14px;"> Ante cualquier consulta puede comunicarse con la organización.
                            <br><br> Muchas gracias. </div>
                        <div style="margin-top:30px;font-size:12px;color:#777;"> Expo Envase & ETIF 2025<br> Sistema de gestión de
                            expositores </div>
                    </div>
                </body>
                </html>'; 


            $exposicion = Exposicion::find($expositor['id_exposicionº']);
            Mail::html($html, function ($message) use ($expositor) {
                $message->to($expositor['usuario'])
                    ->subject('Módulos pendientes')
                    ->from('envase.etif2025@gmail.com', $exposicion['nombre']);
            });

            $emailPredefinido = EmailPredefinido::where('descripcion', '#Recordatorio Modulos')->first();

            if (!$emailPredefinido) {
                $emailPredefinido = new EmailPredefinido();
                $emailPredefinido->descripcion = "#Recordatorio Modulos";
                $emailPredefinido->save();
            }

            $newEmail = new EmailPredefinidoToExpositor();
            $newEmail->id_email_predefinido = $emailPredefinido->id;
            $newEmail->id_expositor = $expositor['id'];
            $newEmail->save();

        }
        } catch (\Exception $ex) {
            Log::error($ex);
        }
    }
}