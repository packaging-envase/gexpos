<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReporteExport implements FromArray, WithHeadings
{
    protected $idExposicion;

    public function __construct($idExposicion)
    {
        $this->idExposicion = $idExposicion;
    }

    public function array(): array
    {
        try {
            $items = \App\Models\ExpositorModuloSalaConferencia::leftJoin('expositor', function ($query) {
                $query->on('expositor.id', '=', 'expositor_modulo_sala_conferencia.id_expositor');
                $query->where('expositor.id_exposicion', '=', $this->idExposicion);
            })
            ->select('expositor_modulo_sala_conferencia.*', 'expositor.id as id_expositor')
            ->where('expositor.id','!=',null)
            ->get();

            $data = [];

            // Encabezado de items
            $data[] = [
                'Sala',
                'Fecha',
                'Empresa',
                'Stands',
                'Status',
                'Título charla',
                'Temario',
                'Responsable',
                'Responsable Cargo',
                'Responsable Email',
                'Responsable Teléfono',
                'Disertante Nombre',
                'Disertante Email',
                'Disertante Teléfono',
                'Disertante Facebook',
                'Disertante Instagram',
                'Disertante LinkedIn',
                'Disertante YouTube'
            ];

            // Items
            foreach ($items as $item) {
                $expositor = \App\Models\Expositor::withTrashed()->find($item['id_expositor']);
                if (!$expositor) {
                    dd($item);
                }
                $stands = \App\Models\Stand::where('id_expositor', '=', $expositor['id'])->get();

                if (count($stands)) {
                    $stands = $stands->toArray();
                } else {
                    $stands = [];
                }

                $standsString = implode(', ', array_column($stands, 'numero'));
                $empresa = \App\Models\Empresa::find($expositor['id_empresa']);

                //$standsString = '999';

                $empresaString = '';
                if (count($stands) && $stands[0]['nombre_exposicion']) {
                    $empresaString = $empresa[$stands[0]['nombre_exposicion']];
                } else {
                    $empresaString = $empresa['razon_social'];
                }

                $dishertantesRaw = \App\Models\ExpositorModuloSalaConferenciaDisertante::where('id_expositor_modulo_sala_conferencia', '=', $item['id'])->get();

                if (count($dishertantesRaw)) {
                    $disertante = $dishertantesRaw[0];
                } else {
                    $disertante = null;
                }

                $sala = \App\Models\ModuloSalaConferencia::find($item['id_modulo_sala_conferencia']);

                $data[] = [
                    $sala ? $sala['sala'] : '',
                    $sala ? $sala['fecha'] : '',
                    $empresaString,
                    $standsString,
                    $item['status'],
                    $item['titulo_charla'],
                    $item['temario'],
                    $item['responsable_apellido_nombre'],
                    $item['responsable_cargo'],
                    $item['responsable_email'],
                    $item['responsable_telefono'],
                    $disertante ? $disertante['apellido_nombre'] : '',
                    $disertante ? $disertante['email'] : '',
                    $disertante ? $disertante['telefono'] : '',
                    $disertante ? $disertante['facebook'] : '',
                    $disertante ? $disertante['instagram'] : '',
                    $disertante ? $disertante['linkedin'] : '',
                    $disertante ? $disertante['youtube'] : '',
                ];

                if (count($dishertantesRaw) > 1) {
                    for ($i = 1; $i < count($dishertantesRaw); $i++) {
                        $disertante = $dishertantesRaw[$i];
                        $data[] = [
                            $sala ? $sala['sala'] : '',
                            $sala ? $sala['fecha'] : '',
                            $empresaString,
                            $standsString,
                            $item['status'],
                            $item['titulo_charla'],
                            $item['temario'],
                            $item['responsable_apellido_nombre'],
                            $item['responsable_cargo'],
                            $item['responsable_email'],
                            $item['responsable_telefono'],
                            $disertante['apellido_nombre'],
                            $disertante['email'],
                            $disertante['telefono'],
                            $disertante['facebook'],
                            $disertante['instagram'],
                            $disertante['linkedin'],
                            $disertante['youtube'],
                        ];
                    }
                }
            }

            return $data;
        } catch (\Throwable $th) {
            //dd($item);
            throw $th;
        }
    }

    public function headings(): array
    {
        return [];
    }
}
