<?php

namespace App\Http\Controllers;

use App\Models\CategoriaEmpresa;
use App\Models\CategoriasEmpresa;
use App\Models\Stand;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{

    public function catalogoView(Request $request)
    {

        $selectedExpo = null;
        if (!$request->exposicion) {
            $selectedExpo = DB::connection('external_db')
                ->table('expo_id')
                ->select('id_selected_expo', 'etif', 'envase')
                ->first();
            $idExposicion = $selectedExpo->id_selected_expo;
        } else {
            $idExposicion = $request->exposicion;
            $selectedExpo = new \stdClass();
            $selectedExpo->envase = $request->entidad == 'envase' || $request->entidad == 'envasealimentek' ? true : false;
            $selectedExpo->etif = $request->entidad == 'etif' ? true : false;
        }

        $exposicion = \App\Models\Exposicion::find($idExposicion);
        $expositores = \App\Models\Expositor::where('expositor.id_exposicion', '=', $idExposicion);

        if ($request->entidad == 'envase' || $request->entidad == 'envasealimentek') {
            if (!$selectedExpo->envase) {
                return '';
            }
            //$expositores->where('expositor.catalogo', '=', true);
            if ($request->filtro == 'envase') {
                $expositores->where('expositor.catalogo', '=', true);
            }
            if ($request->filtro == 'etif') {
                $expositores->where('expositor.etif', '=', true);
            }
            if (!$request->filtro) {
                $expositores->where(function ($query) {
                    $query->where('expositor.catalogo', '=', true);
                    $query->orWhere('expositor.etif', '=', true);
                });
            }
        } else
        if ($request->entidad == 'etif') {
            if (!$selectedExpo->etif) {
                return '';
            }
            $expositores->where('expositor.etif', '=', true);
        } else {
            return back();
        }

        $expositores = $expositores->get()->toArray();

        foreach ($expositores as $key => $value) {
            $stands = \App\Models\Stand::where('id_expositor', '=', $value['id'])->orderBy('numero')->get()->toArray();
            $expositores[$key]['stand'] = $stands;
            $expositores[$key]['empresa'] = \App\Models\Empresa::find($value['id_empresa']);
        }

        $subRubros = [];
        if ($request->categoria) {
            $expositoresFinal = [];
            foreach ($expositores as $key => $expositor) {

                $empresaId = $expositor['id_empresa'];
                $categoriaId = $request->categoria;
                $subRubroId = $request->subRubro;

                // Buscar coincidencia con categoría principal
                $matchCategoria = \App\Models\CategoriaEmpresa::where('id_empresa', $empresaId)
                    ->where('id_categorias_empresa', $categoriaId)
                    ->first();

                if (!$matchCategoria) {
                    $subRubrosEmpresa = CategoriaEmpresa::where('id_empresa','=',$expositor['id_empresa'])->get()->toArray();

                    foreach ($subRubrosEmpresa as $key => $value) {
                        $categoria = CategoriasEmpresa::find($value['id_categorias_empresa']);
                        if ($categoria) {
                            if ($categoria['id_padre'] == $request->categoria) {
                                $matchCategoria = true;
                                break;
                            }
                        }
                        
                    }
                }

                // Asumir que hay coincidencia por subrubro si no se especifica subRubro
                $matchSubRubro = false;

                if ($subRubroId) {
                    $matchCategoria = false;
                    // Buscar coincidencia con categoria secundaria
                    $matchSubRubro = \App\Models\CategoriaEmpresa::where('id_empresa', $empresaId)
                        ->where('id_categorias_empresa', $subRubroId)
                        ->first();
                }

                if ($matchCategoria || $matchSubRubro) {
                    $expositoresFinal[] = $expositor;
                }
            }

            $expositores = $expositoresFinal;

            $subRubros = \App\Models\CategoriasEmpresa::where('id_padre', '=', $request->categoria)->orderBy('nombre', 'asc')->get();
        }

        $categorias = \App\Models\CategoriasEmpresa::where('nivel', '=', 1);

        if ($request->entidad == 'etif') {
            $categorias = \App\Models\CategoriasEmpresa::where('etif', '=', true);
        } else {
            $categorias = \App\Models\CategoriasEmpresa::where('nivel', '=', 1);
            /*
            where(function ($query) {
                $query->where('etif', '=', false)->orWhere('etif', '=', NULL);
            })            
            ->where('nivel', '=', 1);
            */
        }

        $categorias = $categorias->orderBy('nombre', 'asc')->get();

        foreach ($expositores as $key => $value) {
            $expositores[$key]['logo'] = \App\Models\EmpresaLogo::where('id_empresa', '=', $value['empresa']->id)
                ->orderBy('created_at', 'desc')
                ->where('id_exposicion', '=', null)
                ->first();
        }

        if ($request->orden == 'stand') {
            usort($expositores, function ($a, $b) {
                $nombreA = $a['stand'] && $a['stand'][0]['numero'] ? $a['stand'][0]['numero'] : '';
                $nombreB = $b['stand'] && $b['stand'][0]['numero'] ? $b['stand'][0]['numero'] : '';

                return $nombreA > $nombreB;
            });
        } else {
            usort($expositores, function ($a, $b) {
                $nombreA = $a['stand'] && $a['stand'][0]['nombre_exposicion'] ? $a['empresa'][$a['stand'][0]['nombre_exposicion']] : '';
                $nombreB = $b['stand'] && $b['stand'][0]['nombre_exposicion'] ? $b['empresa'][$b['stand'][0]['nombre_exposicion']] : '';

                return strcmp($nombreA, $nombreB);
            });
        }

        // Obtener el parámetro 'query' de la búsqueda
        $search = $request->search; // Valor del campo 'query' en la búsqueda

        if ($search) {
            // Filtrar el array de expositores
            $expositores = array_filter($expositores, function ($expositor) use ($search) {
                // Verificar si 'stand' existe
                if (isset($expositor['stand'])) {
                    // Filtrar por nombre de exposición y número dentro de cada 'stand'
                    foreach ($expositor['stand'] as $stand) {
                        $matchQuery = false;

                        // Verificar si el nombre de exposición o el número coincide con 'query'
                        if ($search) {
                            if (isset($stand['nombre_exposicion']) && strpos(strtolower($expositor['empresa'][$stand['nombre_exposicion']]), strtolower($search)) !== false) {
                                $matchQuery = true;
                            }
                            if (isset($stand['numero']) && strpos(strtolower($stand['numero']), strtolower($search)) !== false) {
                                $matchQuery = true;
                            }
                        }

                        // Si se encuentra un match, incluir el expositor
                        if ($matchQuery) {
                            return true;
                        }
                    }
                }

                return false; // Si no se encuentra un match, excluir el expositor
            });
        }

        $viewData = [
            'exposicion' => $exposicion,
            'expositores' => $expositores,
            'categorias' => $categorias,
            'wp' => $request->wp ? true : false,
            'entidad' => $request->entidad,
            'search' => $search,
            'orden' => $request->orden,
            'subRubros' => $subRubros,
            'query' => $request->all(),
        ];

        return view('App.Public.catalogo', ['viewData' => $viewData]);
    }

    public function catalogoExpositorView(Request $request)
    {

        try {

            $expositor = \App\Models\Expositor::find($request->id);
            $empresa = \App\Models\Empresa::find($expositor->id_empresa);
            $logo = \App\Models\EmpresaLogo::where('id_empresa', '=', $expositor->id_empresa)->orderBy('id', 'desc')->first();
            $stand = \App\Models\Stand::where('id_expositor', '=', $expositor->id)->where('id_exposicion', '=', $expositor->id_exposicion)->first();
            $empresasRepresentadas = \App\Models\EmpresaRepresentada::where('id_empresa', '=', $empresa->id)->get();
            $rubros = \App\Models\CategoriaEmpresa::leftJoin('categorias_empresa', 'categorias_empresa.id', '=', 'categoria_empresa.id_categorias_empresa')
                ->select('categorias_empresa.nombre')
                ->where('categoria_empresa.id_exposicion', '=', $expositor->id_exposicion)
                ->where('categoria_empresa.id_empresa', '=', $empresa->id)
                ->where('categorias_empresa.nivel', '=', 1)
                ->orderBy('categorias_empresa.nombre', 'asc')
                ->get();
            $subRubros = \App\Models\CategoriaEmpresa::leftJoin('categorias_empresa', 'categorias_empresa.id', '=', 'categoria_empresa.id_categorias_empresa')
                ->select('categorias_empresa.nombre')
                ->where('categoria_empresa.id_exposicion', '=', $expositor->id_exposicion)
                ->where('categoria_empresa.id_empresa', '=', $empresa->id)
                ->where('categorias_empresa.nivel', '=', 2)
                ->orderBy('categorias_empresa.nombre', 'asc')
                ->get();
            $productos = \App\Models\ProductoServicioEmpresa::where('id_exposicion', '=', $expositor->id_exposicion)->where('id_empresa', '=', $empresa->id)->get();

            $expositorModuloCatalogo = \App\Models\ExpositorModuloCatalogo::where('id_expositor', '=', $expositor->id)->first();

            //dd($productos);
            $viewData = [
                "expositor" => $expositor,
                "logo" => $logo,
                "stand" => $stand,
                "empresa" => $empresa,
                'empresasRepresentadas' => $empresasRepresentadas,
                'rubros' => $rubros,
                'productos' => $productos,
                'expositorModuloCatalogo' => $expositorModuloCatalogo,
                'subRubros' => $subRubros
            ];

            //dd($empresa);

            return view('App.Public.catalogoExpositor', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function mapaFantasma(Request $request)
    {
        try {
            if (!$request->id) {
                return response()->json(['message' => 'No id mapa'], 500);
            }

            $exposicion = \App\Models\Exposicion::find($request->id);

            //dd($exposicion, $request->id);

            $viewData = [
                'mapa' => $exposicion['mapa']
            ];

            return view('App.Exposicion.mapaFantasma', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function salaConferencia(Request $request)
    {
        try {

            if (!$request->idExposicion) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('home');
            } else {
                $exposicion = \App\Models\Exposicion::with([
                    'salaConferencia' => function ($query) {
                        $query->where('publicado', '=', true);
                        $query->orderBy('sala', 'asc');
                        $query->orderBy('fecha', 'asc');
                    },
                    'salaConferenciaTexto'
                ])->find($request->idExposicion);
            }

            $moduloSalaConferencia = \App\Models\ModuloSalaConferencia::with([
                'expositorModuloSalaConferencia.expositor.empresa',
                'expositorModuloSalaConferencia.expositorModuloSalaConferenciaDisertante'
            ]);

            if (!$request->preview) {
                $moduloSalaConferencia = $moduloSalaConferencia->where('publicado_final', '=', true);
            } else {
                $moduloSalaConferencia = $moduloSalaConferencia->where('publicado', '=', true);
            }

            $moduloSalaConferencia = $moduloSalaConferencia
                ->where('status', '=', 'asignado')                
                ->where('id_exposicion', '=', $exposicion->id)
                ->orderBy('fecha', 'asc')
                ->orderBy('sala', 'asc')
                ->get()
                ->toArray();

            foreach ($moduloSalaConferencia as $key => $item) {
                foreach ($item['expositor_modulo_sala_conferencia'] as $keyPedido => $pedido) {
                    if ($pedido['status'] == 'asignado') {
                        $moduloSalaConferencia[$key]['asignado'] = $pedido;
                        break;
                    }
                }
            }

            foreach ($moduloSalaConferencia as $key => $value) {
                if (!isset($value['asignado'])) {
                    // Eliminamos el elemento del array
                    unset($moduloSalaConferencia[$key]);
                    // Continuamos con el siguiente elemento
                    continue;
                }
                $stand = Stand::where('id_expositor', '=', $value['asignado']['id_expositor'])->first();

                if ($stand) {
                    $moduloSalaConferencia[$key]['nombreEmpresa'] = $value['asignado']['expositor']['empresa'][$stand['nombre_exposicion']];
                } else {
                    $moduloSalaConferencia[$key]['nombreEmpresa'] = $value['asignado']['expositor']['empresa']['razon_social'];
                }

                $moduloSalaConferencia[$key]['pertenece_etif'] = $value['asignado']['expositor']['pertenece_etif'];
                $moduloSalaConferencia[$key]['id_expositor'] = $value['asignado']['expositor']['id'];

                unset(
                    $moduloSalaConferencia[$key]['asignado']['expositor'],
                    $moduloSalaConferencia[$key]['asignado']['id'],
                    $moduloSalaConferencia[$key]['asignado']['id_modulo_sala_conferencia'],
                    $moduloSalaConferencia[$key]['asignado']['created_at'],
                    $moduloSalaConferencia[$key]['asignado']['updated_at'],
                    $moduloSalaConferencia[$key]['asignado']['deleted_at'],
                    //$moduloSalaConferencia[$key]['asignado']['responsable_apellido_nombre'],
                    $moduloSalaConferencia[$key]['asignado']['responsable_cargo'],
                    //$moduloSalaConferencia[$key]['asignado']['responsable_email'],
                    $moduloSalaConferencia[$key]['asignado']['responsable_telefono'],
                    $moduloSalaConferencia[$key]['id'],
                    $moduloSalaConferencia[$key]['id_exposicion'],
                    //$moduloSalaConferencia[$key]['sala'],
                    //$moduloSalaConferencia[$key]['fecha'],
                    //$moduloSalaConferencia[$key]['duracion'],
                    $moduloSalaConferencia[$key]['created_at'],
                    $moduloSalaConferencia[$key]['updated_at'],
                    $moduloSalaConferencia[$key]['deleted_at'],
                    //$moduloSalaConferencia[$key]['status'],
                    $moduloSalaConferencia[$key]['publicado'],
                    //$moduloSalaConferencia[$key]['asignado'],
                    //$moduloSalaConferencia[$key]['nombreEmpresa'],
                    //$moduloSalaConferencia[$key]['pertenece_etif'],
                    //$moduloSalaConferencia[$key]['id_expositor'],
                );

                foreach ($moduloSalaConferencia[$key]['asignado']['expositor_modulo_sala_conferencia_disertante'] as $keyDisertante => $value) {
                    unset(
                        $moduloSalaConferencia[$key]['asignado']['expositor_modulo_sala_conferencia_disertante'][$keyDisertante]['id'],
                        $moduloSalaConferencia[$key]['asignado']['expositor_modulo_sala_conferencia_disertante'][$keyDisertante]['id_expositor_modulo_sala_conferencia'],
                        //$moduloSalaConferencia[$key]['asignado']['expositor_modulo_sala_conferencia_disertante'][$keyDisertante]['apellido_nombre'],
                        $moduloSalaConferencia[$key]['asignado']['expositor_modulo_sala_conferencia_disertante'][$keyDisertante]['email'],
                        $moduloSalaConferencia[$key]['asignado']['expositor_modulo_sala_conferencia_disertante'][$keyDisertante]['telefono'],
                        $moduloSalaConferencia[$key]['asignado']['expositor_modulo_sala_conferencia_disertante'][$keyDisertante]['facebook'],
                        $moduloSalaConferencia[$key]['asignado']['expositor_modulo_sala_conferencia_disertante'][$keyDisertante]['instagram'],
                        $moduloSalaConferencia[$key]['asignado']['expositor_modulo_sala_conferencia_disertante'][$keyDisertante]['linkedin'],
                        $moduloSalaConferencia[$key]['asignado']['expositor_modulo_sala_conferencia_disertante'][$keyDisertante]['youtube'],
                        $moduloSalaConferencia[$key]['asignado']['expositor_modulo_sala_conferencia_disertante'][$keyDisertante]['created_at'],
                        $moduloSalaConferencia[$key]['asignado']['expositor_modulo_sala_conferencia_disertante'][$keyDisertante]['updated_at'],
                        $moduloSalaConferencia[$key]['asignado']['expositor_modulo_sala_conferencia_disertante'][$keyDisertante]['deleted_at']
                    );
                }

                unset($moduloSalaConferencia[$key]['expositor_modulo_sala_conferencia']);

                //dd($moduloSalaConferencia[$key]);
            }


            //dd($moduloSalaConferencia);

            $viewData = [
                'exposicion' => $exposicion,
                'moduloSalaConferencia' => $moduloSalaConferencia,
                'wp' => $request->wp ? true : false
            ];

            return view('App.Public.salaConferencia', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
