<?php

namespace App\Http\Controllers;

use App\Models\CategoriasEmpresa;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;


class CategoriaController extends Controller
{

    public function panel(Request $request)
    {

        $categorias0 = \App\Models\CategoriasEmpresa::where('nivel', '=', 0);
        if (Auth::guard()->user()->grupo == 3) {
            $categorias0->where('etif', '=', true);
        } else {
            $categorias0->where('etif', '=', false);
        }
        $categorias0 = $categorias0->get();


        $categorias1 = \App\Models\CategoriasEmpresa::where('nivel', '=', 1);
        if (Auth::guard()->user()->grupo == 3) {
            $categorias1->where('etif', '=', true);
        } else {
            $categorias1->where('etif', '=', false);
        }
        $categorias1 = $categorias1->get();

        $categoria0 = \App\Models\CategoriasEmpresa::where('nivel', '=', 0);
        if (Auth::guard()->user()->grupo == 3) {
            $categoria0->where('etif', '=', true);
        } else {
            $categoria0 = $categoria0->where('etif', '=', false);
        }
        $categoria0 = $categoria0->orderBy('nombre')->get()->map(function ($item) {
            $item->nombre = mb_convert_case(mb_strtolower($item->nombre, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
            return $item;
        });

        for ($i = 0; $i < count($categoria0); $i++) {
            $categoria0[$i]['categoria1'] = \App\Models\CategoriasEmpresa::where('nivel', '=', 1)
                ->where('id_padre', '=', $categoria0[$i]->id)
                ->orderBy('nombre')
                ->get()
                ->map(function ($item) {
                    $item->nombre = mb_convert_case(mb_strtolower($item->nombre, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                    return $item;
                });

            for ($j = 0; $j < count($categoria0[$i]['categoria1']); $j++) {
                $categoria0[$i]['categoria1'][$j]['categoria2'] = \App\Models\CategoriasEmpresa::where('nivel', '=', 2)
                    ->where('id_padre', '=', $categoria0[$i]['categoria1'][$j]->id)
                    ->orderBy('nombre')
                    ->get()
                    ->map(function ($item) {
                        $item->nombre = mb_convert_case(mb_strtolower($item->nombre, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                        return $item;
                    });
            }
        }

        //dd($categorias0);

        $viewData = [
            'categorias' => $categoria0,
            'categorias0' => $categorias0,
            'categorias1' => $categorias1,
            'exposicion' => \App\Models\Exposicion::find($request->id)
        ];

        return view('App.Categoria.panel', ['viewData' => $viewData]);
    }

    public function updateStore(Request $request)
    {

        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);


            $newCategoria = null;
            if ($request->id) {
                $newCategoria = \App\Models\CategoriasEmpresa::find($request->id);
            } else {
                $newCategoria = new \App\Models\CategoriasEmpresa();
                if (Auth::guard()->user()->grupo == 3) {
                    $newCategoria->etif = true;
                }
                $newCategoria->id_padre = $request->id_padre;
                $newCategoria->nivel = $request->nivel;
            }

            $newCategoria->nombre = $request->nombre;
            $newCategoria->save();

            $messageBag->add('success', 'Rubro ' . ($request->id ? 'actualizada' : 'creada') . ' con éxito');

            return back();
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

            $categoria = \App\Models\CategoriasEmpresa::find($request->id);
            $categoria->delete();

            $messageBag->add('success', 'Categoría eliminada con éxito');

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function excel()
    {
        try {
            $data = [];

            $nivel0 = CategoriasEmpresa::where('nivel', 0)->orderBy('nombre');

            if (Auth::guard()->user()->grupo == 3) {
                $nivel0->where('etif', '=', true);
            } else {
                $nivel0->where('etif', '=', false);
            }

            $nivel0 = $nivel0->get();

            foreach ($nivel0 as $n0) {
                $nivel1 = CategoriasEmpresa::where('nivel', 1)
                    ->where('id_padre', '=', $n0['id'])
                    ->orderBy('nombre')->get();

                foreach ($nivel1 as $n1) {
                    $nivel2 = CategoriasEmpresa::where('nivel', 2)
                        ->where('id_padre', '=', $n1['id'])
                        ->orderBy('nombre')->get();

                    foreach ($nivel2 as $n2) {
                        $data[] = [
                            $n0->nombre,
                            $n1->nombre,
                            $n2->nombre,
                        ];
                    }
                }
            }

            array_unshift($data, ['Nivel 0', 'Nivel 1', 'Nivel 2']);

            $export = new class($data) implements FromArray, WithEvents {
                protected $data;

                public function __construct(array $data)
                {
                    $this->data = $data;
                }

                public function array(): array
                {
                    return $this->data;
                }

                public function registerEvents(): array
                {
                    return [
                        AfterSheet::class => function (AfterSheet $event) {
                            $sheet = $event->sheet->getDelegate();

                            foreach (range('A', 'C') as $col) {
                                $sheet->getColumnDimension($col)->setAutoSize(true);
                            }
                        },
                    ];
                }
            };

            return Excel::download($export, 'rubros.xlsx');
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
