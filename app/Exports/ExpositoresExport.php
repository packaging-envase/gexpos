<?php

namespace App\Exports;

use App\Models\Expositor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents; // Corregido: Usamos WithEvents
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;

class ExpositoresExport implements FromCollection, WithHeadings, WithEvents, WithStyles
{
    protected $expositores;

    public function __construct($expositores)
    {
        $this->expositores = $expositores;
    }

    public function collection()
    {
        $data = [];

        foreach ($this->expositores as $expositor) {
            foreach ($expositor->stand as $stand) {
                $expositorData = [
                    'expositor' => $expositor['usuario'], // o el nombre que deseas mostrar
                    'empresa' => $expositor->empresa['razon_social'], // o el nombre de la empresa
                    'fantasia' => $expositor->empresa['nombre_fantasia'], // o el nombre de la empresa
                    'numeroStand' => $stand['numero'],
                ];

                $data[] = $expositorData;
            }
        }

        return collect($data);
    }

    public function headings(): array
    {
        return ['Expositor', 'Razón Social', 'Fantasía', 'Número de Stand'];
    }

    public function styles($sheet)
    {
        $sheet->getStyle('A1:C1')->getFont()->setBold(true); // Poner los encabezados en negrita
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $highestColumn = $sheet->getHighestColumn(); // Obtener la última columna utilizada
                $highestRow = $sheet->getHighestRow(); // Obtener la última fila utilizada

                // Ajustar el ancho de cada columna según el contenido más largo
                foreach (range('A', $highestColumn) as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true); // Autoajustar el ancho de cada columna
                }
            },
        ];
    }
}
