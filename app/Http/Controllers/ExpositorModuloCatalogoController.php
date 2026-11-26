<?php

namespace App\Http\Controllers;

use App\Models\CategoriasEmpresa;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ExpositorModuloCatalogoController extends Controller
{
    public function panel(Request $request)
    {

        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            if ($request->idExposicion && $request->idEmpresa) {
                $idExposicion = $request->idExposicion;
                $idEmpresa = $request->idEmpresa;
            } else {
                $idExposicion = Auth::guard('expositor')->user()->id_exposicion;
                $idEmpresa = Auth::guard('expositor')->user()->id_empresa;
            }

            $exposicion = \App\Models\Exposicion::find($idExposicion);
            $moduloCatalogo = \App\Models\ModuloCatalogo::where('id_exposicion', '=', $idExposicion)->first();

            $empresa = \App\Models\Empresa::leftJoin('pais', 'pais.id', '=', 'empresa.id_pais')
                ->select('empresa.*', 'pais.nombre as pais')
                ->where('empresa.id', '=', $idEmpresa)
                ->first();

            $empresa->expositor = \App\Models\Expositor::where('id_empresa', '=', $idEmpresa)
                ->where('id_exposicion', '=', $idExposicion)
                ->first();

            $empresa->stand = \App\Models\Stand::where('id_exposicion', '=', $idExposicion)
                ->where('id_expositor', '=', $empresa->expositor->id)
                ->first();

            $categorias = \App\Models\CategoriaEmpresa::leftJoin('categorias_empresa', 'categorias_empresa.id', '=', 'categoria_empresa.id_categorias_empresa')
                ->select('categoria_empresa.id', 'categorias_empresa.nombre', 'categoria_empresa.id_categorias_empresa')
                ->where('categoria_empresa.id_empresa', '=', $idEmpresa)
                ->where('id_exposicion', '=', $idExposicion)
                ->get()
                ->map(function ($item) {
                    $item->nombre = mb_convert_case(mb_strtolower($item->nombre, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                    return $item;
                })
                ->toArray();

            foreach ($categorias as $key => $value) {
                $categoria = CategoriasEmpresa::find($categorias[$key]['id_categorias_empresa']);

                if (!$categoria) {
                    Log::debug(json_encode($value));
                }
                if ($categoria && $categoria['nivel'] == '2') {
                    $categoria2  = CategoriasEmpresa::find($categoria['id_padre']);
                    if ($categoria2) {
                        $categorias[$key]['nombre'] = $categoria2['nombre'] . ' - ' . $categorias[$key]['nombre'];
                    }
                }
            }

            usort($categorias, function ($a, $b) {
                return strcmp($a['nombre'], $b['nombre']);
            });

            $empresa->categorias = $categorias;

            $empresa->logo = \App\Models\EmpresaLogo::where('id_empresa', '=', $empresa->id)->where('id_exposicion', '=', $idExposicion)->orderBy('created_at', 'desc')->first();

            $empresa->productos = \App\Models\ProductoServicioEmpresa::where('id_empresa', '=', $empresa['id'])->where('id_exposicion', '=', $idExposicion)->get();

            $empresa->empresaRepresentadas = \App\Models\EmpresaRepresentada::where('id_empresa', '=', $empresa['id'])->where('id_exposicion', '=', $idExposicion)->get();

            $empresa->contactos = \App\Models\EmpresaContacto::where('id_empresa', '=', $empresa['id'])->get();

            $categoria0 = \App\Models\CategoriasEmpresa::where('nivel', '=', 0);
            if ($empresa->expositor->pertenece_etif) {
                $categoria0 = $categoria0->where('etif', '=', true);
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

            $categoriasRaw = null;
            if ($empresa->expositor->pertenece_etif) {
                $categoriasRaw = \App\Models\CategoriasEmpresa::where('etif', '=', true)->get();
            } else {
                $categoriasRaw = \App\Models\CategoriasEmpresa::where('etif', '=', false)->get();
            }

            $idExpositor = Auth::guard('expositor')->user() 
                ? Auth::guard('expositor')->user()->id 
                : $empresa->expositor->id;

            $expositorModuloCatalogo = \App\Models\ExpositorModuloCatalogo::where('id_expositor', '=', $idExpositor)->first();

            $viewData = [
                //'provincias' => \App\Models\Provincia::all()->toArray(),
                //'ciudades' => \App\Models\Ciudad::all()->toArray(),
                //'paises' => \App\Models\Pais::all()->toArray(),
                'catalogo' => \App\Models\ModuloCatalogo::where('id_exposicion', '=', $idExposicion)->orderBy('id', 'desc')->first(),
                'categorias' => $categoria0,
                'categoriasRaw' => $categoriasRaw,
                'empresa' => $empresa,
                "exposicion" => $exposicion,
                "moduloCatalogo" => $moduloCatalogo,
                "expositorModuloCatalogo" => $expositorModuloCatalogo,
                "idExpositor" => $idExpositor
            ];

            return view('App.ModuloExpositor.catalogo', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function update(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            //dd($request->all());

            $item = \App\Models\Empresa::find($request->id);
            $item->descripcion = $request->descripcion;
            $item->descripcion_ingles = $request->descripcion_ingles;
            $item->paises_exportar = $request->paises_exportar;
            $item->facebook = $request->facebook;
            $item->instagram = $request->instagram;
            $item->youtube = $request->youtube;
            $item->linkedin = $request->linkedin;
            $item->origen = $request->origen;
            $item->fecha_fundacion = $request->fecha_fundacion;
            $item->save();

            $expositorModuloCatalogo = \App\Models\ExpositorModuloCatalogo::where('id_expositor', '=', $request->idExpositor)->first();

            if (!$expositorModuloCatalogo) {
                $expositorModuloCatalogo = new \App\Models\ExpositorModuloCatalogo();
                $expositorModuloCatalogo->id_expositor = $request->idExpositor;
            }

            if (Auth::guard()->check() || Auth::guard('expositor')->check()) {
                $expositorModuloCatalogo->updated_at_expositor = date('Y-m-d');
            }

            $expositorModuloCatalogo->pais = $request->pais;
            $expositorModuloCatalogo->provincia = $request->provincia;
            $expositorModuloCatalogo->localidad = $request->localidad;
            $expositorModuloCatalogo->codigo_postal = $request->codigo_postal;
            $expositorModuloCatalogo->nombre = $request->catalgoNombre;
            $expositorModuloCatalogo->direccion = $request->direccion;
            $expositorModuloCatalogo->telefono = $request->telefono;
            $expositorModuloCatalogo->whatsapp = $request->whatsapp;
            $expositorModuloCatalogo->email = $request->email;
            $expositorModuloCatalogo->web = $request->web;
            $expositorModuloCatalogo->save();

            // Esto borra
            // Obtener categorias de la empresa
            $categorias = \App\Models\CategoriaEmpresa::leftJoin('categorias_empresa', 'categorias_empresa.id', '=', 'categoria_empresa.id_categorias_empresa')
                ->select('categoria_empresa.id', 'categoria_empresa.id_categorias_empresa')
                ->where('id_empresa', '=', $item['id'])
                ->get();
            for ($i = 0; $i < count($categorias); $i++) {
                // Si el input de categoria tiene value true delete
                if ($request->get('categoria' . $categorias[$i]['id']) == '1') {
                    $categorias[$i]->delete();

                    $categoriaExpositor = \App\Models\CategoriaEmpresa::where('id_empresa', '=', $item->id)
                        ->where('id_categorias_empresa', '=', $categorias[$i]->id_categorias_empresa)
                        ->where('id_exposicion', '=', null)
                        ->first();

                    if ($categoriaExpositor) {
                        $categoriaExpositor->delete();
                    }
                }
            }

            $productos = \App\Models\ProductoServicioEmpresa::where('id_empresa', '=', $item['id'])->get();
            for ($i = 0; $i < count($productos); $i++) {
                if ($request->get('deleteProdServExist' . $productos[$i]['id']) == '1') {
                    $productos[$i]->delete();
                } else if ($request->file('prodServImagenExist' . $productos[$i]['id'])) {
                    $newFile = new \App\Models\ProductoServicioEmpresa();
                    $newFile->id_empresa = $item->id;
                    $newFile->id_exposicion = $request->id_exposicion;
                    $newFile->nombre = $request->get('productoServicioNombre' . $productos[$i]['id']);
                    $newFile->descripcion = $request->get('productoServicioDescripcion' . $productos[$i]['id']);
                    $newFile->path_logo = $request->file('prodServImagenExist' . $productos[$i]['id'])->store("public/empresa/productoServicioLogo");
                    $newFile->path_logo = str_replace("public/empresa/productoServicioLogo/", '', $newFile->path_logo);
                    $newFile->save();
                    $productos[$i]->delete();
                } else {
                    $newFile = \App\Models\ProductoServicioEmpresa::find($productos[$i]['id']);
                    $newFile->nombre = $request->get('productoServicioNombre' . $productos[$i]['id']);
                    $newFile->descripcion = $request->get('productoServicioDescripcion' . $productos[$i]['id']);
                    $newFile->save();
                }
            }

            $empresasRepresentadas = \App\Models\EmpresaRepresentada::where('id_empresa', '=', $item['id'])->get();
            for ($i = 0; $i < count($empresasRepresentadas); $i++) {
                if ($request->get('deleteEmpresaRepresentadaExist' . $empresasRepresentadas[$i]['id']) == '1') {
                    $empresasRepresentadas[$i]->delete();
                } else if ($request->file('empresaRepresentadaImagenExist' . $empresasRepresentadas[$i]['id'])) {
                    $newFile = new \App\Models\EmpresaRepresentada();
                    $newFile->id_empresa = $item->id;
                    $newFile->id_exposicion = $request->id_exposicion;
                    $newFile->nombre = $request->get('empresaRepresentadaNombre' . $empresasRepresentadas[$i]['id']);
                    $newFile->descripcion = $request->get('empresaRepresentadaDescripcion' . $empresasRepresentadas[$i]['id']);
                    $newFile->path_logo = $request->file('empresaRepresentadaImagenExist' . $empresasRepresentadas[$i]['id'])->store("public/empresa/empresaRepresentadaLogo");
                    $newFile->path_logo = str_replace("public/empresa/empresaRepresentadaLogo/", '', $newFile->path_logo);
                    $newFile->save();
                    $empresasRepresentadas[$i]->delete();
                } else {
                    $newFile = \App\Models\EmpresaRepresentada::find($empresasRepresentadas[$i]['id']);
                    $newFile->nombre = $request->get('empresaRepresentadaNombre' . $empresasRepresentadas[$i]['id']);
                    $newFile->descripcion = $request->get('empresaRepresentadaDescripcion' . $empresasRepresentadas[$i]['id']);
                    $newFile->save();
                }
            }

            /*
            $contactos = \App\Models\EmpresaContacto::where('id_empresa','=',$item['id'])->get();            
            for ($i=0; $i < count($contactos); $i++) { 
                $contacto = $contactos[$i];
                if ($request->get('contactoDeleted'.$contacto['id']) == '1') {
                    $contacto->delete();
                } else {
                    $contacto->apellido_nombre = $request->get('contactoApellidoNombre'.$contacto['id']);
                    $contacto->cargo = $request->get('contactoCargo'.$contacto['id']);
                    $contacto->telefono = $request->get('contactoWhatsapp'.$contacto['id']);
                    $contacto->email = $request->get('contactoEmail'.$contacto['id']);
                    $contacto->save();
                }
            }
            */

            // Esto crea
            if ($request->productoServicioNombre) {
                for ($i = 0; $i < count($request->productoServicioNombre); $i++) {

                    $newFile = new \App\Models\ProductoServicioEmpresa();
                    $newFile->id_exposicion = $request->id_exposicion;
                    $newFile->id_empresa = $item->id;
                    $newFile->nombre = $request->productoServicioNombre[$i];
                    $newFile->descripcion = $request->productoServicioDescripcion[$i];

                    $image = $request->productosImagenes[$i];
                    if ($image) {
                        $newFile->path_logo = $image->store("public/empresa/productoServicioLogo");
                        $newFile->path_logo = str_replace("public/empresa/productoServicioLogo/", '', $newFile->path_logo);
                    }
                    $newFile->save();
                }
            }

            if ($request->empresaRepresentadaNombre) {
                for ($i = 0; $i < count($request->empresaRepresentadaNombre); $i++) {

                    $newFile = new \App\Models\EmpresaRepresentada();
                    $newFile->id_exposicion = $request->id_exposicion;
                    $newFile->id_empresa = $item->id;
                    $newFile->nombre = $request->empresaRepresentadaNombre[$i];
                    $newFile->descripcion = $request->empresaRepresentadaDescripcion[$i];

                    $image = $request->empresaRepresentadaImagenes[$i];
                    if ($image) {
                        $newFile->path_logo = $image->store("public/empresa/empresaRepresentadaLogo");
                        $newFile->path_logo = str_replace("public/empresa/empresaRepresentadaLogo/", '', $newFile->path_logo);
                    }
                    $newFile->save();
                }
            }

            if ($request->categorias) {
                for ($i = 0; $i < count($request->categorias); $i++) {
                    $newCategoria = new \App\Models\CategoriaEmpresa();
                    $newCategoria->id_exposicion = $request->id_exposicion;
                    $newCategoria->id_categorias_empresa = $request->categorias[$i];
                    $newCategoria->id_empresa = $item->id;
                    $newCategoria->save();

                    $newCategoria = new \App\Models\CategoriaEmpresa();
                    $newCategoria->id_categorias_empresa = $request->categorias[$i];
                    $newCategoria->id_empresa = $item->id;
                    $newCategoria->save();
                }
            }

            if ($request->logo) {
                $newFile = new \App\Models\EmpresaLogo();
                $newFile->id_exposicion = $request->id_exposicion;
                $newFile->id_empresa = $item->id;
                $newFile->name = $request->logo->getClientOriginalName();
                $newFile->path = $request->logo->store("public/empresa/logo");
                $newFile->path = str_replace("public/empresa/logo/", '', $newFile->path);
                $newFile->save();

                $newFile = new \App\Models\EmpresaLogo();
                $newFile->id_empresa = $item->id;
                $newFile->name = $request->logo->getClientOriginalName();
                $newFile->path = $request->logo->store("public/empresa/logo");
                $newFile->path = str_replace("public/empresa/logo/", '', $newFile->path);
                $newFile->save();
            }

            $messageBag->add('success', 'Empresa ' . ($request->id ? 'actualizada' : 'creada') . ' con éxito!');

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
