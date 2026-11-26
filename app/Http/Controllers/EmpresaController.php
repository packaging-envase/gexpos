<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;

use App\Models\Empresa;

class EmpresaController extends Controller
{
    public function panel(Request $request)
    {

        $empresas = Empresa::leftJoin('users', 'empresa.id_user', '=', 'users.id');

        if ($request->search) {
            $empresas = $empresas
                ->where(function ($query) use ($request) {
                    $query->where('empresa.razon_social', 'like', '%' . $request->search . '%');
                    $query->orWhere('empresa.cuit', 'like', '%' . $request->search . '%');
                    $query->orWhere('empresa.nombre_fantasia', 'like', '%' . $request->search . '%');
                });
        }

        if (Auth::guard()->user()->grupo == 3) {
            $empresas = $empresas->where('empresa.pertenece_etif', '=', true);
        }

        $empresas = $empresas->select(
                'empresa.*',
                'users.name as name_prospector'
            )
            ->orderBy('empresa.razon_social')
            ->paginate(20);

        $exposicion = null;
        if ($request->id) {
            $exposicion = \App\Models\Exposicion::find($request->id);
        }

        $viewData = [
            'empresas' => $empresas,
            'exposicion' => $exposicion
        ];

        return view('App.Empresa.panel', ['viewData' => $viewData]);
    }

    public function create(Request $request)
    {

        try {
            $id = $request->id && $request->id != '0' ? $request->id : null;
            $idExposicion = $request->idExposicion && $request->idExposicion != '0' ? $request->idExposicion : null;

            $exposicion = $idExposicion ? \App\Models\Exposicion::find($idExposicion) : null;

            $exposicion = null;
            if ($request) {
                $exposicion = \App\Models\Exposicion::find($request->idExposicion);
            }

            if ($id) {
                $empresa = \App\Models\Empresa::find($id);

                if (Auth::guard()->user()->grupo == 3 && !$empresa->pertenece_etif) {
                    return response()->json(['message' => 'Unauthorized'], 200);
                }

                $empresa->categorias = \App\Models\CategoriaEmpresa::leftJoin('categorias_empresa', 'categorias_empresa.id', '=', 'categoria_empresa.id_categorias_empresa')
                    ->select('categoria_empresa.id', 'categorias_empresa.nombre')
                    ->where('categoria_empresa.id_empresa', '=', $id)
                    ->where('id_exposicion', '=', null)
                    ->get()->toArray();

                $empresa->logo = \App\Models\EmpresaLogo::where('id_empresa', '=', $empresa->id)->orderBy('created_at', 'desc')->where('id_exposicion', '=', null)->first();

                $empresa->productos = \App\Models\ProductoServicioEmpresa::where('id_empresa', '=', $empresa['id'])->where('id_exposicion', '=', null)->get();

                $empresa->empresaRepresentadas = \App\Models\EmpresaRepresentada::where('id_empresa', '=', $empresa['id'])->where('id_exposicion', '=', null)->get();

                $empresa->contactos = \App\Models\EmpresaContacto::where('id_empresa', '=', $empresa['id'])->get();
            } else {
                $empresa = null;
            }

            $categoria0 = \App\Models\CategoriasEmpresa::where('nivel', '=', 0)->get()->map(function ($item) {
                $item->nombre = mb_convert_case(mb_strtolower($item->nombre, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                return $item;
            });
            for ($i = 0; $i < count($categoria0); $i++) {
                $categoria0[$i]['categoria1'] = \App\Models\CategoriasEmpresa::where('nivel', '=', 1)
                    ->where('id_padre', '=', $categoria0[$i]->id)
                    ->get()->map(function ($item) {
                        $item->nombre = mb_convert_case(mb_strtolower($item->nombre, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                        return $item;
                    });

                for ($j = 0; $j < count($categoria0[$i]['categoria1']); $j++) {
                    $categoria0[$i]['categoria1'][$j]['categoria2'] = \App\Models\CategoriasEmpresa::where('nivel', '=', 2)
                        ->where('id_padre', '=', $categoria0[$i]['categoria1'][$j]->id)
                        ->get()->map(function ($item) {
                            $item->nombre = mb_convert_case(mb_strtolower($item->nombre, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                            return $item;
                        });
                }
            }

            $viewData = [
                'catalogo' => \App\Models\ModuloCatalogo::where('id_exposicion', '=', 1)->first()->toArray(),
                'provincias' => \App\Models\Provincia::orderBy('descripcion', 'asc')->get()->toArray(),
                'ciudades' => \App\Models\Ciudad::orderBy('descripcion', 'asc')->get()->toArray(),
                'paises' => \App\Models\Pais::orderBy('nombre', 'asc')->get()->toArray(),
                'categorias' => $categoria0,
                'categoriasRaw' => \App\Models\CategoriasEmpresa::all()->toArray(),
                'empresa' => $empresa,
                'exposicion' => $exposicion
            ];

            return view('App.Empresa.create', ['viewData' => $viewData]);
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

            $lastExposicionActive = \App\Models\Exposicion::where('active', '=', true)->orderBy('id', 'desc')->first();

            if ($request->id) {

                $item = Empresa::find($request->id);

                if (Auth::guard()->user()->grupo == 3 && !$item->pertenece_etif) {
                    return response()->json(['message' => 'Unauthorized'], 200);
                }

                $expositor = \App\Models\Expositor::where('id_exposicion', '=', $lastExposicionActive->id)->where('id_empresa', '=', $item->id)->first();
                if ($lastExposicionActive && $expositor) {
                    $stands = \App\Models\Stand::where('id_expositor', '=', $expositor->id)->get();

                    foreach ($stands as $key => $stand) {

                        $mapa = json_decode($lastExposicionActive->mapa);
                        $keyStand = null;
                        foreach ($mapa->artboards[0]->children as $key => $child) {
                            if ($child->id == $stand->id_children_mapa) {
                                $keyStand = $key;
                                break; // Salimos del bucle una vez encontrado el objeto
                            }
                        }

                        if ($keyStand !== null) {
                            $title = "# " . $stand->numero . " - " . ($request->nombre_exposicion == 'nombre_fantasia' ? $request->nombre_fantasia : $request->razon_social);
                            $mapa->artboards[0]->children[$keyStand]->title = $title;
                            $mapa->artboards[0]->children[$keyStand]->tooltip_content[0]->text = $title;
                            $mapa->artboards[0]->children[$keyStand]->actions->script = 'openModalAsignarStand("' . $title . '")';
                            $lastExposicionActive->mapa = json_encode($mapa);
                            $lastExposicionActive->save();
                        }
                    }
                }

                // Obtener categorias de la empresa
                $categorias = \App\Models\CategoriaEmpresa::leftJoin('categorias_empresa', 'categorias_empresa.id', '=', 'categoria_empresa.id_categorias_empresa')
                    ->select('categoria_empresa.id', 'categoria_empresa.id_categorias_empresa')
                    ->where('id_empresa', '=', $item['id'])
                    ->get();
                for ($i = 0; $i < count($categorias); $i++) {
                    // Si el input de categoria tiene value true delete
                    if ($request->get('categoria' . $categorias[$i]['id']) == '1') {
                        $categorias[$i]->delete();
                        if ($lastExposicionActive) {
                            $categoriaExpositor = \App\Models\CategoriaEmpresa::where('id_empresa', '=', $item->id)
                                ->where('id_categorias_empresa', '=', $categorias[$i]->id_categorias_empresa)
                                ->where('id_exposicion', '=', $lastExposicionActive->id)
                                ->first();

                            if ($categoriaExpositor) {
                                $categoriaExpositor->delete();
                            }
                        }
                    }
                }

                /*
                $productos = \App\Models\ProductoServicioEmpresa::where('id_empresa','=',$item['id'])->get();
                for ($i=0; $i < count($productos); $i++) {                
                    if ($request->get('deleteProdServExist'.$productos[$i]['id']) == '1') {
                        $productos[$i]->delete();
                    } else if ($request->file('prodServImagenExist'.$productos[$i]['id'])) {
                        $newFile = new \App\Models\ProductoServicioEmpresa();
                        $newFile->id_empresa = $item->id;
                        $newFile->nombre = $request->get('productoServicioNombre'.$productos[$i]['id']);
                        $newFile->descripcion = $request->get('productoServicioDescripcion'.$productos[$i]['id']);
                        $newFile->path_logo = $request->file('prodServImagenExist'.$productos[$i]['id'])->store("public/empresa/productoServicioLogo");
                        $newFile->path_logo = str_replace("public/empresa/productoServicioLogo/", '', $newFile->path_logo);
                        $newFile->save();
                        $productos[$i]->delete();
                    } else {
                        $newFile = \App\Models\ProductoServicioEmpresa::find($productos[$i]['id']);
                        $newFile->nombre = $request->get('productoServicioNombre'.$productos[$i]['id']);
                        $newFile->descripcion = $request->get('productoServicioDescripcion'.$productos[$i]['id']);
                        $newFile->save();
                    }
                }

                $empresasRepresentadas = \App\Models\EmpresaRepresentada::where('id_empresa','=',$item['id'])->get();            
                for ($i=0; $i < count($empresasRepresentadas); $i++) {                
                    if ($request->get('deleteEmpresaRepresentadaExist'.$empresasRepresentadas[$i]['id']) == '1') {
                        $empresasRepresentadas[$i]->delete();
                    } else if ($request->file('empresaRepresentadaImagenExist'.$empresasRepresentadas[$i]['id'])) {
                        $newFile = new \App\Models\EmpresaRepresentada();
                        $newFile->id_empresa = $item->id;
                        $newFile->nombre = $request->get('empresaRepresentadaNombre'.$empresasRepresentadas[$i]['id']);
                        $newFile->descripcion = $request->get('empresaRepresentadaDescripcion'.$empresasRepresentadas[$i]['id']);
                        $newFile->path_logo = $request->file('empresaRepresentadaImagenExist'.$empresasRepresentadas[$i]['id'])->store("public/empresa/empresaRepresentadaLogo");
                        $newFile->path_logo = str_replace("public/empresa/empresaRepresentadaLogo/", '', $newFile->path_logo);
                        $newFile->save();
                        $empresasRepresentadas[$i]->delete();
                    } else {
                        $newFile = \App\Models\EmpresaRepresentada::find($empresasRepresentadas[$i]['id']);
                        $newFile->nombre = $request->get('empresaRepresentadaNombre'.$empresasRepresentadas[$i]['id']);
                        $newFile->descripcion = $request->get('empresaRepresentadaDescripcion'.$empresasRepresentadas[$i]['id']);
                        $newFile->save();
                    }
                }
                */
                $contactos = \App\Models\EmpresaContacto::where('id_empresa', '=', $item['id'])->get();
                for ($i = 0; $i < count($contactos); $i++) {
                    $contacto = $contactos[$i];
                    if ($request->get('contactoDeleted' . $contacto['id']) == '1') {
                        $contacto->delete();
                    } else {
                        $contacto->apellido = $request->get('contactoApellido' . $contacto['id']);
                        $contacto->nombre = $request->get('contactoNombre' . $contacto['id']);
                        $contacto->cargo = $request->get('contactoCargo' . $contacto['id']);
                        $contacto->telefono = $request->get('contactoWhatsapp' . $contacto['id']);
                        $contacto->email = $request->get('contactoEmail' . $contacto['id']);
                        $contacto->save();
                    }
                }
            } else {
                $item = new Empresa();
                $item->id_user = Auth::user()->id;
            }

            $item->cuit = $request->cuit;
            $item->codigo_iae = $request->codigo_iae;
            $item->razon_social = $request->razon_social;
            $item->nombre_fantasia = $request->nombre_fantasia;
            $item->descripcion = $request->descripcion;
            $item->facebook = $request->facebook;
            $item->instagram = $request->instagram;
            $item->youtube = $request->youtube;
            $item->linkedin = $request->linkedin;

            $item->id_pais = $request->id_pais;
            if ($item->id_pais == '11') {
                $item->id_provincia = $request->id_provincia;
                $item->id_ciudad = $request->id_ciudad;
            } else {
                $item->provincia = $request->provincia;
                $item->ciudad = $request->ciudad;
            }

            $item->codigo_postal = $request->codigo_postal;
            $item->direccion = $request->direccion;
            $item->telefono = $request->telefono;
            $item->whatsapp = $request->whatsapp;
            $item->email = $request->email;
            $item->web = $request->web;
            $item->fecha_alta = $request->fecha_alta;
            $item->fecha_baja = $request->fecha_baja;

            $item->recibe_email = $request->recibe_email ? true : false;
            $item->exportador = $request->exportador ? true : false;
            $item->importador = $request->importador ? true : false;
            $item->representante = $request->representante ? true : false;
            $item->distribuidor = $request->distribuidor ? true : false;
            $item->fabricante = $request->fabricante ? true : false;

            $item->save();

            if ($request->pertenece == 'etif' || Auth::guard()->user()->grupo == 3) {

                $novedad = new \App\Models\NovedadesEmpresa();
                $novedad->id_user = Auth::guard()->user()->id;
                $novedad->id_exposicion = $lastExposicionActive ? $lastExposicionActive->id : null;
                $novedad->id_empresa = $item->id;
                $novedad->descripcion = "Ref: Nueva " . $item->razon_social;
                $novedad->status = 'pendiente';
                $novedad->save();

                $messageBag->add('success', 'La empresa fue enviada al Administrador para ser aprobada');
            }

            /*
            if ($request->productoServicioNombre) {
                for ($i=0; $i < count($request->productoServicioNombre); $i++) { 
                    
                    $newFile = new \App\Models\ProductoServicioEmpresa();
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
                for ($i=0; $i < count($request->empresaRepresentadaNombre); $i++) { 
                    
                    $newFile = new \App\Models\EmpresaRepresentada();
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
            */

            if ($request->contactoApellidoNombre) {
                for ($i = 0; $i < count($request->contactoApellidoNombre); $i++) {
                    $newContacto = new \App\Models\EmpresaContacto();
                    $newContacto->id_empresa = $item->id;
                    $newContacto->apellido_nombre = $request->contactoApellidoNombre[$i];
                    $newContacto->cargo = $request->contactoCargo[$i];
                    $newContacto->telefono = $request->contactoWhatsapp[$i];
                    $newContacto->email = $request->contactoEmail[$i];
                    $newContacto->save();
                }
            }

            if ($request->categorias) {
                for ($i = 0; $i < count($request->categorias); $i++) {
                    $newCategoria = new \App\Models\CategoriaEmpresa();
                    $newCategoria->id_categorias_empresa = $request->categorias[$i];
                    $newCategoria->id_empresa = $item->id;
                    $newCategoria->save();
                }

                if ($lastExposicionActive && \App\Models\Expositor::where('id_exposicion', '=', $lastExposicionActive->id)->where('id_empresa', '=', $item->id)->first()) {
                    for ($i = 0; $i < count($request->categorias); $i++) {
                        $newCategoria = new \App\Models\CategoriaEmpresa();
                        $newCategoria->id_exposicion = $lastExposicionActive->id;
                        $newCategoria->id_categorias_empresa = $request->categorias[$i];
                        $newCategoria->id_empresa = $item->id;
                        $newCategoria->save();
                    }
                }
            }

            if ($request->logo) {
                $newFile = new \App\Models\EmpresaLogo();
                $newFile->id_empresa = $item->id;
                $newFile->name = $request->logo->getClientOriginalName();
                $newFile->path = $request->logo->store("public/empresa/logo");
                $newFile->path = str_replace("public/empresa/logo/", '', $newFile->path);
                $newFile->save();

                if ($lastExposicionActive) {
                    $newFile = new \App\Models\EmpresaLogo();
                    $newFile->id_exposicion = $lastExposicionActive->id;
                    $newFile->id_empresa = $item->id;
                    $newFile->name = $request->logo->getClientOriginalName();
                    $newFile->path = $request->logo->store("public/empresa/logo");
                    $newFile->path = str_replace("public/empresa/logo/", '', $newFile->path);
                    $newFile->save();
                }
            }



            $messageBag->add('success', 'Empresas ' . ($request->id ? 'actualizada' : 'creada') . ' con éxito!');
            return back();
            //return redirect(route('empresa.panel'));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteMany(Request $request)
    {

        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $withExpositor = [];
            foreach ($request->checkboxBorrarEmpresas as $key => $idEmpresa) {
                if (\App\Models\Expositor::where('id_empresa', '=', $idEmpresa)->first()) {
                    $withExpositor[] = \App\Models\Empresa::find($idEmpresa);
                    $messageBag->add('danger', ' La empresa ' . $withExpositor[count($withExpositor) - 1]->razon_social . ' tiene un expositor asociado. Debe eliminar el expositor primero');
                }
            }

            if (count($withExpositor)) {
                return back();
            }

            foreach ($request->checkboxBorrarEmpresas as $key => $idEmpresa) {
                $empresa = \App\Models\Empresa::find($idEmpresa);
                $empresa->delete();
            }

            $messageBag->add('success', count($request->checkboxBorrarEmpresas) . ' empresas borradas con éxito!');
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

            $empresa = \App\Models\Empresa::find($request->id);
            $empresa->delete();

            $messageBag->add('success', 'Empresa borrada con éxito!');

            return redirect()->route('empresa.panel');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function changeEtif(Request $request)
    {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $empresa = Empresa::find($request->id);
            $empresa->pertenece_etif = !$empresa->pertenece_etif;
            $empresa->save();

            $messageBag->add('success', 'Empresa ' . $empresa->razon_social . ($empresa->pertenece_etif ? ' ahora es ETIF' : ' ahora no es ETIF'));

            return response()->json([], 200);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
