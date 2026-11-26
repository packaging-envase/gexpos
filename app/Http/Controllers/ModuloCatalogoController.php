<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;



use App\Exports\ExpositoresExport;

use App\Models\Expositor;
use App\Models\Stand;

class ModuloCatalogoController extends Controller
{

    public function create(Request $request)
    {

        try {
            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::with('catalogo')->find($request->id);
            }

            $viewData = [
                "exposicion" => $exposicion
            ];

            //dd($viewData);

            return view('App.Modulo.catalogo', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateStore(Request $request)
    {

        try {

            //dd($request->all());

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            if (!$request->idExposicion) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $catalogo = \App\Models\ModuloCatalogo::where('id_exposicion', '=', $request->idExposicion)->first();

                if (!$catalogo) {
                    $catalogo = new \App\Models\ModuloCatalogo();
                    $catalogo->id_exposicion = $request->idExposicion;
                }
            }

            $exposicion = \App\Models\Exposicion::find($request->idExposicion);

            $catalogo->max_file_size = $request->max_file_size;
            $catalogo->max_quantity_productos = $request->max_quantity_productos;
            $catalogo->max_caracteres_producto_descripcion = $request->max_caracteres_producto_descripcion;
            $catalogo->max_caracteres_empresa = $request->max_caracteres_empresa;
            $catalogo->cierre = $request->cierre ? $request->cierre : $exposicion->fecha_fin;
            $catalogo->save();

            $messageBag->add('success', 'Modulo Catalogo guardada con éxito!');

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function panelRequerimientos(Request $request)
    {
        try {

            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::with('catalogo')->find($request->id);
            }

            $expositores = \App\Models\Expositor::with('empresa')->where('id_exposicion', '=', $exposicion->id)->get();

            //dd($expositores);

            $viewData = [
                "exposicion" => $exposicion,
                "expositores" => $expositores
            ];

            return view('App.ModuloRequerimientos.catalogo', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function switchExpositor(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $expositor = \App\Models\Expositor::find($request->id_expositor);

            $stand = Stand::where('id_expositor', '=', $request->id_expositor)->first();

            if (!$stand) {
                $messageBag->add('warning', "El expositor debe tener al menos un stand");
                return back();
            }

            $expositor->catalogo = $request->switch == 'on';
            $expositor->save();

            $messageBag->add(($expositor->catalogo ? 'success' : 'warning'), 'Expositor ' . $expositor->usuario . ($expositor->catalogo ? ' en catalogo' : ' fuera de catalogo'));

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function switchExpositorEtif(Request $request)
    {
        try {

            $expositor = \App\Models\Expositor::find($request->id_expositor);
            $expositor->etif = $request->switch == 'on';
            $expositor->save();

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function exportacionExcel(Request $request)
    {
        try {

            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::with('catalogo')->find($request->id);
            }

            if ($request->catalogo == 'etif') {
                $expositores = Expositor::with(['stand', 'empresa'])
                    ->where('etif', '=', true)
                    ->where('id_exposicion', '=', $exposicion->id)
                    ->get();
            } else 
            if ($request->catalogo == 'envase') {
                $expositores = Expositor::with(['stand', 'empresa'])
                    ->where('catalogo', '=', true)
                    ->where('id_exposicion', '=', $exposicion->id)
                    ->get();
            } else {
                $expositores = Expositor::with(['stand', 'empresa'])
                    ->where(function ($query) {
                        $query->where('catalogo', '=', true);
                        $query->orWhere('etif', '=', true);
                    })
                    ->where('id_exposicion', '=', $exposicion->id)
                    ->get();
            }

            return Excel::download(new ExpositoresExport($expositores), 'expositores.xlsx');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function exportacionWordDetalle(Request $request)
    {
        try {
            if (!$request->id) {
                return redirect()->route('home');
            }

            $exposicion = \App\Models\Exposicion::with('catalogo')->find($request->id);

            $expositores = Expositor::with(['stand', 'empresa', 'expositorModuloCatalogo'])
                ->where('id_exposicion', '=', $exposicion->id);

            if ($request->catalogo == 'etif') {
                $expositores = $expositores->where('expositor.etif', '=', true);
            } else 
                if ($request->catalogo == 'envase') {
                $expositores = $expositores->where('expositor.catalogo', '=', true);
            } else {
                $expositores = $expositores->where(function ($query) {
                    $query->where('expositor.etif', '=', true);
                    $query->orWhere('expositor.catalogo', '=', true);
                });
            }
            $expositores = $expositores->get();

            $expositores = $expositores->sortBy(function ($expositor) {

                if (isset($expositor->stand[0])) {
                    $nombreEmpresa = $expositor->stand[0]->nombre_exposicion
                        ? $expositor->stand[0]->nombre_exposicion
                        : 'razon_social';

                    $empresa = $expositor->empresa[$nombreEmpresa] ?? '';
                } else {
                    $empresa = $expositor->empresa['razon_social'] ?? '';
                }

                return mb_strtolower(trim($empresa), 'UTF-8');
            })->values();

            // Renderizar la vista Blade como HTML
            $html = view('App.ModuloRequerimientos.catalogo-exportacion-word', compact('expositores'))->render();

            $html = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

            //$html = mb_convert_encoding($html, 'ISO-8859-1', 'UTF-8');

            $html = '
            <html xmlns:o="urn:schemas-microsoft-com:office:office"
                xmlns:w="urn:schemas-microsoft-com:office:word"
                xmlns="http://www.w3.org/TR/REC-html40">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <title>Exportación</title>
                <style>
                    body { font-family: Tahoma; }
                </style>
            </head>
            <body>' . $html . '</body></html>';

            // Devolver el archivo como descarga
            return response()->streamDownload(function () use ($html) {
                echo $html;
            }, 'documento.doc', [
                'Content-Type' => 'application/msword; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="catalogo.doc"',
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function exportacionWordRubros(Request $request)
    {
        try {
            if (!$request->id) {
                return redirect()->route('home');
            }

            $exposicion = \App\Models\Exposicion::with('catalogo')->find($request->id);

            $rubros = \App\Models\CategoriasEmpresa::where('nivel', '=', 1)
                ->orderBy('nombre')
                ->get()
                ->map(function ($item) {
                    $item->nombre = mb_convert_case(mb_strtolower($item->nombre, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                    return $item;
                })
                ->toArray();

            foreach ($rubros as $keyRubro => $valueRubro) {
                $padre = \App\Models\CategoriasEmpresa::find($valueRubro['id_padre']);

                if (!$padre || $padre['deleted_at']) {
                    unset($rubros[$keyRubro]);
                    continue;
                }

                $rubros[$keyRubro]['subRubros'] = \App\Models\CategoriasEmpresa::where('nivel', '=', 2)
                    ->where('id_padre', '=', $valueRubro['id'])
                    ->orderBy('nombre')
                    ->get()
                    ->map(function ($item) {
                        $item->nombre = mb_convert_case(mb_strtolower($item->nombre, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                        return $item;
                    })
                    ->toArray();

                $expositores = \App\Models\CategoriaEmpresa::leftJoin('empresa', 'empresa.id', '=', 'categoria_empresa.id_empresa')
                    ->leftJoin('expositor', 'expositor.id_empresa', '=', 'empresa.id')
                    ->leftJoin('stand', 'stand.id_expositor', '=', 'expositor.id')
                    ->select(
                        'expositor.id',
                        'expositor.id_exposicion',
                        'expositor.etif',
                        'expositor.catalogo',
                        'empresa.razon_social',
                        'empresa.nombre_fantasia',
                        'expositor.pertenece_etif',
                        DB::raw("IFNULL(GROUP_CONCAT(DISTINCT stand.numero SEPARATOR ', '), '') AS stands"),
                        DB::raw("
                            CASE 
                                WHEN MIN(stand.nombre_exposicion) = empresa.razon_social 
                                THEN empresa.razon_social 
                                ELSE empresa.nombre_fantasia 
                            END AS nombre
                        ")
                    )
                    ->where('categoria_empresa.id_categorias_empresa', '=', $valueRubro['id'])
                    ->where('expositor.id_exposicion', '=', $exposicion['id'])
                    ->having('stands', '!=', '')
                    ->groupBy(
                        'expositor.id',
                        'expositor.id_exposicion',
                        'expositor.etif',
                        'expositor.catalogo',
                        'empresa.razon_social',
                        'empresa.nombre_fantasia',
                        'expositor.pertenece_etif',
                    );

                if ($request->catalogo == 'etif') {
                    $expositores = $expositores->where('etif', '=', true);
                } else 
                if ($request->catalogo == 'envase') {
                    $expositores = $expositores->where('catalogo', '=', true);
                } else {
                    $expositores = $expositores->where(function ($query) {
                        $query->where('etif', '=', true);
                        $query->orWhere('catalogo', '=', true);
                    });
                }
                $expositores = $expositores->get()->toArray();

                usort($expositores, function ($a, $b) {
                    return strcmp($a['nombre'], $b['nombre']);
                });

                // Si no hay expositores, eliminamos el subrubro
                if (!empty($expositores)) {
                    $rubros[$keyRubro]['expositores'] = $expositores;
                } else {
                    $rubros[$keyRubro]['expositores'] = [];
                }

                foreach ($rubros[$keyRubro]['subRubros'] as $keySubRubro => $valueSubRubro) {
                    $padre = \App\Models\CategoriasEmpresa::find($valueSubRubro['id_padre']);

                    if (!$padre || $padre['deleted_at']) {
                        unset($rubros[$keyRubro]['subRubros'][$keySubRubro]);
                        continue;
                    }

                    $expositores = \App\Models\CategoriaEmpresa::leftJoin('empresa', 'empresa.id', '=', 'categoria_empresa.id_empresa')
                        ->leftJoin('expositor', 'expositor.id_empresa', '=', 'empresa.id')
                        ->leftJoin('stand', 'stand.id_expositor', '=', 'expositor.id')
                        ->select(
                            'expositor.id',
                            'expositor.id_exposicion',
                            'expositor.etif',
                            'expositor.catalogo',
                            'empresa.razon_social',
                            'empresa.nombre_fantasia',
                            'expositor.pertenece_etif',
                            DB::raw("IFNULL(GROUP_CONCAT(DISTINCT stand.numero SEPARATOR ', '), '') AS stands"),
                            DB::raw("
                                CASE 
                                    WHEN MIN(stand.nombre_exposicion) = empresa.razon_social 
                                    THEN empresa.razon_social 
                                    ELSE empresa.nombre_fantasia 
                                END AS nombre
                            ")
                        )
                        ->where('categoria_empresa.id_categorias_empresa', '=', $valueSubRubro['id'])
                        ->where('expositor.id_exposicion', '=', $exposicion['id'])
                        ->having('stands', '!=', '')
                        ->groupBy(
                            'expositor.id',
                            'expositor.id_exposicion',
                            'expositor.etif',
                            'expositor.catalogo',
                            'empresa.razon_social',
                            'empresa.nombre_fantasia',
                            'expositor.pertenece_etif',
                        );

                    if ($request->catalogo == 'etif') {
                        $expositores = $expositores->where('etif', '=', true);
                    } else 
                    if ($request->catalogo == 'envase') {
                        $expositores = $expositores->where('catalogo', '=', true);
                    } else {
                        $expositores = $expositores->where(function ($query) {
                            $query->where('etif', '=', true);
                            $query->orWhere('catalogo', '=', true);
                        });
                    }
                    $expositores = $expositores->get()->toArray();

                    usort($expositores, function ($a, $b) {
                        return strcmp($a['nombre'], $b['nombre']);
                    });

                    // Si no hay expositores, eliminamos el subrubro
                    if (empty($expositores)) {
                        unset($rubros[$keyRubro]['subRubros'][$keySubRubro]);
                    } else {
                        $rubros[$keyRubro]['subRubros'][$keySubRubro]['expositores'] = $expositores;
                    }
                }

                // Si después de limpiar los subrubros, el rubro no tiene subrubros, lo eliminamos
                if (
                    (
                        (empty($rubros[$keyRubro]['subRubros']) || !isset($rubros[$keyRubro]['subRubros'])) && (empty($rubros[$keyRubro]['expositores']) || !isset($rubros[$keyRubro]['expositores']))
                    ) || count($rubros[$keyRubro]) == 8
                ) {
                    unset($rubros[$keyRubro]);
                }
            }

            // Reindexar los arrays para evitar claves desordenadas
            $rubros = array_values($rubros);

            //dd($rubros);

            // Renderizar la vista Blade como HTML
            //$html = view('App.ModuloRequerimientos.catalogo-exportacion-word-rubros', compact('rubros'))->render();
            //$html = mb_convert_encoding($html, 'ISO-8859-1', 'UTF-8');

            $html = view('App.ModuloRequerimientos.catalogo-exportacion-word-rubros', compact('rubros'))->render();

            $html = '
            <html xmlns:o="urn:schemas-microsoft-com:office:office"
                xmlns:w="urn:schemas-microsoft-com:office:word"
                xmlns="http://www.w3.org/TR/REC-html40">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <title>Exportación</title>
                <style>
                    body { font-family: Tahoma; }
                </style>
            </head>
            <body>' . $html . '</body></html>';


            // Devolver el archivo como descarga
            return response()->streamDownload(function () use ($html) {
                echo $html;
            }, 'rubros.doc', [
                'Content-Type' => 'application/msword',
                'Content-Disposition' => 'attachment; filename="rubros.doc"',
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
