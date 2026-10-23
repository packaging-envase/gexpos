<?php

namespace App\Http\Controllers;

use App\Mail\EnviarEmailPredefinido;
use App\Models\Empresa;
use App\Models\Exposicion;
use App\Models\Expositor;
use App\Models\ExpositorModuloDepositoGarantia;
use App\Models\ModuloDepositoGarantia;
use App\Models\Stand;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;


class ExpositorModuloDepositoGarantiaController extends Controller
{

    public function index(Request $request)
    {
        try {

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

            $expositorModuloDepositoGarantia = ExpositorModuloDepositoGarantia::where('id_expositor', '=', $idExpositor)->first();
            $moduloDepositoGarantia = ModuloDepositoGarantia::where('id_exposicion', '=', $idExposicion)->first();

            $expositor = Expositor::find($idExpositor);

            if (!$moduloDepositoGarantia) {
                $messageBag->add('warning', 'El modulo de Deposito en Garantia está inhabilitado');
                return back();
            }

            $empresa = \App\Models\Empresa::find($expositor['id_empresa']);

            $stands = Stand::where('id_expositor', '=', $expositor['id'])->get();

            $montoUsd = 0;
            $montoArs = 0;

            foreach ($stands as $keyStand => $stand) {
                if ($stand->moneda == 1) {
                    $montoUsd += $stand->valor;
                }
                if ($stand->moneda == 2) {
                    $montoArs += $stand->valor;
                }
            }

            $montoUsd = $montoUsd * ($moduloDepositoGarantia->porcentaje / 100);
            $montoArs = $montoArs * ($moduloDepositoGarantia->porcentaje / 100);

            $viewData = [
                'exposicion' => \App\Models\Exposicion::find($idExposicion),
                'moduloDepositoGarantia' => $moduloDepositoGarantia,
                'expositorModuloDepositoGarantia' => $expositorModuloDepositoGarantia,
                'empresa' => $empresa,
                'montoUsd' => $montoUsd,
                'montoArs' => $montoArs
            ];

            return view('App.ModuloExpositor.depositoGarantia', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateStore(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $idExpositor = Auth::guard('expositor')->user()->id;
            $idExposicion = Auth::guard('expositor')->user()->id_exposicion;
            $exposicion = Exposicion::find($idExposicion);

            $moduloDepositoGarantia = ModuloDepositoGarantia::where('id_exposicion', '=', $idExposicion)->first();

            $item = null;
            if ($request->id) {
                $item = \App\Models\ExpositorModuloDepositoGarantia::find($request->id);
            } else {
                $item = new \App\Models\ExpositorModuloDepositoGarantia();
                $item->id_exposicion = $idExposicion;
                $item->id_expositor = $idExpositor;
            }

            if (Auth::guard()->check() || Auth::guard('expositor')->check()) {
                $item->updated_at_expositor = date('Y-m-d');
            }

            $item->apellido_nombre = $request->apellido_nombre;
            $item->numero_documento = $request->numero_documento;
            $item->direccion = $request->direccion;

            /*
            if ($request->file('resopnsable_path')) {
                $archivo = $request->file('resopnsable_path');
                $nombreArchivo = uniqid() . '.' . $archivo->getClientOriginalExtension();
                $ruta = $archivo->storeAs('public/depositoGarantia', $nombreArchivo);

                $item->path = $ruta;
            }
            */

            $item->save();

            /*
            $textoPagare = "<div>Yo, [apellido_nombre], DNI [numero_documento], domiciliado en [direccion], en representación de la empresa [razon_social] CUIT [cuit] me obligo a pagar al Instituto Argentino del Envase, CUIT 30547166961, la suma de U$/D [monto] ([monto_letras]), el día 19 de octubre de 2025.
            <br/><br/>
            En caso de no cumplir con el pago, declaro que no se realizará protesto alguno.
            <br/><br/><br/><br/>
            Firma y Aclaración<span style='margin-left: 20mm;'>Número Documento</span>
            ";
            */

            $stringUsd = 'U$/D [monto] ([monto_letras])';
            $stringArs = 'ARS $ [monto] ([monto_letras])';

            $textoPagare = "<div>
                Yo, [apellido_nombre], DNI [numero_documento] , domiciliado en [direccion], en representación de la <br/>
                empresa [razon_social] CUIT [cuit] me obligo a pagar al Instituto Argentino del Envase, CUIT 30547166961, la suma de [string_usd][string_ars], el día [fecha] en caso de que se registre algún daño por parte de la empresa expositora, personal de la misma, proveedores directos o indirectos. De no existir estos gastos, el documento le será devuelto al expositor en la Sede Comercial de la Exposición, contra la presentación de la carta recibo entregada al momento de la recepción del documento. Los Documentos de Garantía no retirados dentro de los 60 días de su vencimiento serán destruidos.
                <br/>
                En caso de no cumplir con el pago, declaro que no se realizará protesto alguno.
                <br/>
                <br/>
                <br/>
                <br/>
                <br/>
                Firma y Aclaración<span style='margin-left: 20mm;'>Número Documento</span>
            </div>";

            //dd($textoPagare);
            $textoPagare = str_replace('[apellido_nombre]', $item->apellido_nombre, $textoPagare);
            $textoPagare = str_replace('[numero_documento]', $item->numero_documento, $textoPagare);
            $textoPagare = str_replace('[direccion]', $item->direccion, $textoPagare);

            $expositor = Expositor::find(Auth::guard('expositor')->user()->id);
            $empresa = Empresa::find($expositor['id_empresa']);

            $textoPagare = str_replace('[razon_social]', $empresa->razon_social, $textoPagare);
            $textoPagare = str_replace('[cuit]', $empresa->cuit, $textoPagare);

            $fecha = \Carbon\Carbon::parse($moduloDepositoGarantia->fecha)
                ->locale('es')
                ->translatedFormat('j \d\e F \d\e Y');

            $textoPagare = str_replace('[fecha]', $fecha, $textoPagare);

            $stands = Stand::where('id_expositor', '=', $expositor['id'])->get();

            $montoUsd = 0;
            $montoArs = 0;

            foreach ($stands as $keyStand => $stand) {
                if ($stand->moneda == 1) {
                    $montoUsd += $stand->valor ? $stand->valor : 0;
                }
                if ($stand->moneda == 2) {
                    $montoArs += $stand->valor ? $stand->valor : 0;
                }
            }

            $montoUsd = $montoUsd * ($moduloDepositoGarantia->porcentaje / 100);
            $montoArs = $montoArs * ($moduloDepositoGarantia->porcentaje / 100);

            if ($montoUsd) {
                $stringUsd = str_replace('[monto]', number_format($montoUsd, 2, ',', '.'), $stringUsd);
                $stringUsd = str_replace('[monto_letras]', $this->numeroALetras($montoUsd), $stringUsd);
                $textoPagare = str_replace('[string_usd]', $stringUsd, $textoPagare);
            } else {
                $textoPagare = str_replace('[string_usd]', '', $textoPagare);
            }
            if ($montoArs) {
                $stringArs = str_replace('[monto]', number_format($montoArs, 2, ',', '.'), $stringArs);
                $stringArs = str_replace('[monto_letras]', $this->numeroALetras($montoArs), $stringArs);
                $textoPagare = str_replace('[string_ars]', ' ' . $stringArs, $textoPagare);
            } else {
                $textoPagare = str_replace('[string_ars]', '', $textoPagare);
            }


            //Log::debug('$expositor[id]: '.$expositor['id']);
            if (count($stands)) {
                $stands = $stands->toArray();
            } else {
                $stands = [];
            }

            $standsString = implode(', ', array_column($stands, 'numero'));


            //$standsString = '999';

            $empresaString = '';
            if (count($stands) && $stands[0]['nombre_exposicion']) {
                $empresaString = $empresa[$stands[0]['nombre_exposicion']];
            } else {
                $empresaString = $empresa['razon_social'];
            }

            $details = [
                'idExposicion' => $idExposicion,
                'title' => 'Deposito en Garantia',
                'body' => "
                    <strong>" . $empresaString . "</strong><br/>
                    " . ($standsString ? "Stands: " . $standsString : '') . "
                    <p>Estimado/a <strong>{$item->apellido_nombre}</strong>,</p>
                    <p>Gracias por completar el formulario de deposito en garantia para ".$exposicion['nombre'].".</p>
                    
                    <p>A continuación, se detallan los datos ingresados:</p>
            
                    " . $textoPagare . "
            
                    <p>Lo/a esperamos en el evento.</p>

                    <p>
                        No responda a este email. Contáctese a través de los canales habituales: <br/>
                        ".nl2br($exposicion['contacto_emails'])."
                    </p>
                "
            ];

            //$mail = new EnviarEmailPredefinido($details);
            //Mail::to($expositor->usuario)->send($mail->from('envase.etif2025@gmail.com', 'Novedades'));
            //Mail::to('marcelo.ismael.mdp@gmail.com')->send($mail->from('envase.etif2025@gmail.com', 'Novedades'));

            //Mail::to('admin.ventas@envase.org')->send($mail->from('envase.etif2025@gmail.com', 'Novedades'));
            //Mail::to('tecnica@envase.org')->send($mail->from('envase.etif2025@gmail.com', 'Novedades'));
            //Mail::to('facturacion@envase.org')->send($mail->from('envase.etif2025@gmail.com', 'Novedades'));

            $item->save();

            $messageBag->add('success', 'Deposito en garantía con éxito!');

            //dd($textoPagare);

            // Generar el PDF desde HTML
            $pdf = Pdf::loadHTML($textoPagare);

            // Guardar temporalmente
            $path = storage_path('app/temp_pagare.pdf');
            file_put_contents($path, $pdf->output());

            // Descargar usando response()->download
            return response()->download($path, 'pagare.pdf')->deleteFileAfterSend(true);

            //return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function numeroALetras($numero)
    {
        $unidades = ['', 'uno', 'dos', 'tres', 'cuatro', 'cinco', 'seis', 'siete', 'ocho', 'nueve'];
        $decenas = ['', 'diez', 'veinte', 'treinta', 'cuarenta', 'cincuenta', 'sesenta', 'setenta', 'ochenta', 'noventa'];
        $especiales = [
            11 => 'once',
            12 => 'doce',
            13 => 'trece',
            14 => 'catorce',
            15 => 'quince',
            16 => 'dieciséis',
            17 => 'diecisiete',
            18 => 'dieciocho',
            19 => 'diecinueve',
            21 => 'veintiuno',
            22 => 'veintidós',
            23 => 'veintitrés',
            24 => 'veinticuatro',
            25 => 'veinticinco',
            26 => 'veintiséis',
            27 => 'veintisiete',
            28 => 'veintiocho',
            29 => 'veintinueve'
        ];

        $convertirDecenas = function ($n, $conY = true) use ($unidades, $decenas, $especiales) {
            if ($n == 0) return '';
            if (isset($especiales[$n])) return $especiales[$n];
            $d = (int)($n / 10);
            $u = $n % 10;
            if ($u == 0) return $decenas[$d];
            return $decenas[$d] . ($conY ? ' y ' : ' ') . $unidades[$u];
        };

        $convertirCientos = function ($n) use (&$convertirDecenas) {
            $centenas = [
                '',
                'ciento',
                'doscientos',
                'trescientos',
                'cuatrocientos',
                'quinientos',
                'seiscientos',
                'setecientos',
                'ochocientos',
                'novecientos'
            ];

            if ($n == 0) return '';
            if ($n == 100) return 'cien';

            $c = (int)($n / 100);
            $resto = $n % 100;

            $texto = '';
            if ($c > 0) {
                $texto .= $centenas[$c];
                if ($resto > 0) $texto .= ' ';
            }
            if ($resto > 0) $texto .= $convertirDecenas($resto, true);
            return $texto;
        };

        $convertirSecciones = function ($n) use (&$convertirCientos) {
            $millones = (int)($n / 1000000);
            $n %= 1000000;

            $miles = (int)($n / 1000);
            $n %= 1000;

            $texto = '';

            if ($millones > 0) {
                $texto .= ($millones == 1 ? 'un millón' : $convertirCientos($millones) . ' millones');
            }

            if ($miles > 0) {
                if ($texto != '') $texto .= ' ';
                $texto .= ($miles == 1 ? 'mil' : $convertirCientos($miles) . ' mil');
            }

            if ($n > 0) {
                if ($texto != '') $texto .= ' ';
                $texto .= $convertirCientos($n);
            }

            if ($texto == '') $texto = 'cero';

            return $texto;
        };

        $partes = explode('.', number_format($numero, 2, '.', ''));

        $entero = (int)$partes[0];
        $decimales = (int)$partes[1];

        $textoEntero = $convertirSecciones($entero);

        if ($decimales == 0) {
            return ucfirst($textoEntero) . ' con cero centavos';
        } else {
            $textoDecimales = $convertirDecenas($decimales, false);
            return ucfirst($textoEntero) . ' con ' . $textoDecimales . ' centavos';
        }
    }
}
