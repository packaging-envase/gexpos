<?php

namespace App\Http\Controllers;

use App\Models\CategoriaEmpresa;
use App\Models\CategoriasEmpresa;
use App\Models\Empresa;
use App\Models\EmpresaContacto;
use App\Models\EmpresaLogo;
use App\Models\EmpresaRepresentada;
use App\Models\Exposicion;
use App\Models\Expositor;
use App\Models\ExpositorModuloCatalogo;
use App\Models\ExpositorModuloElectricidad;
use App\Models\ExpositorModuloElementosOpcionales;
use App\Models\ExpositorModuloInvitacion;
use App\Models\ExpositorModuloSalaConferencia;
use App\Models\ProductoServicioEmpresa;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExpositorAdministracionController extends Controller
{
    public function panel(Request $request)
    {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('exposicion.panel');
            } else {
                $exposicion = \App\Models\Exposicion::find($request->id);
                $expositores = \App\Models\Expositor::with(['expositorModuloCatalogo'])
                    ->leftJoin('email_predefinido_to_expositor', 'email_predefinido_to_expositor.id_expositor', '=', 'expositor.id')
                    ->leftJoin('email_predefinido', function ($query) {
                        $query->on('email_predefinido.id', '=', 'email_predefinido_to_expositor.id_email_predefinido')
                            ->where('email_predefinido.descripcion', '=', 'Envio de Usuario y Contraseña');
                    })
                    ->select('expositor.*', DB::raw('count(email_predefinido.id) as email_count'))
                    ->groupBy('expositor.id')
                    ->where('id_exposicion', '=', $request->id);

                if (Auth::guard()->user()->grupo == 3 || $request->filtro == 3) {
                    $expositores = $expositores->where('pertenece_etif', '=', true)->where('aprobado', '=', true);
                }
                if ($request->filtro == 2) {
                    $expositores = $expositores->where(function ($query) {
                        $query->where('pertenece_etif', '=', false);
                        $query->orWhere('pertenece_etif', '=', null);
                    });
                }
                $expositores = $expositores->get();
            }

            $categoria0 = \App\Models\CategoriasEmpresa::where('nivel', '=', 0);
            if (Auth::guard()->user()->grupo == 3) {
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
                    ->get()->map(function ($item) {
                        $item->nombre = mb_convert_case(mb_strtolower($item->nombre, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                        return $item;
                    });

                $nombre = isset($categoria0[$i]['categoria1']['nombre']) ?  $categoria0[$i]['categoria1']['nombre'] : '';
                for ($j = 0; $j < count($categoria0[$i]['categoria1']); $j++) {
                    $categoria0[$i]['categoria1'][$j]['categoria2'] = \App\Models\CategoriasEmpresa::where('nivel', '=', 2)
                        ->where('id_padre', '=', $categoria0[$i]['categoria1'][$j]->id)
                        ->orderBy('nombre')
                        ->get()->map(function ($item) use ($nombre) {
                            $item->nombre = $nombre . '-' . mb_convert_case(mb_strtolower($item->nombre, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
                            return $item;
                        });
                }
            }

            // DATOS DE FORMULARIOS

            foreach ($expositores as $keyExpositor => $expositor) {
                $energia = ExpositorModuloElectricidad::where('id_exposicion', '=', $exposicion['id'])->where('id_expositor', '=', $expositor['id'])->first();
                if ($energia) {
                    $expositores[$keyExpositor]->completoEnergia = true;
                } else {
                    $expositores[$keyExpositor]->completoEnergia = false;
                }

                $catalogo = ExpositorModuloCatalogo::where('id_expositor', '=', $expositor['id'])->first();
                if ($catalogo) {
                    $expositores[$keyExpositor]->completoCatalogo = true;
                } else {
                    $expositores[$keyExpositor]->completoCatalogo = false;
                }

                $conferencia = ExpositorModuloSalaConferencia::where('id_expositor', '=', $expositor['id'])->first();
                if ($conferencia) {
                    $expositores[$keyExpositor]->completoConferencia = true;
                } else {
                    $expositores[$keyExpositor]->completoConferencia = false;
                }

                $elementos = ExpositorModuloElementosOpcionales::where('id_expositor', '=', $expositor['id'])->first();
                if ($elementos) {
                    $expositores[$keyExpositor]->completoElementos = true;
                } else {
                    $expositores[$keyExpositor]->completoElementos = false;
                }

                $invitacion = ExpositorModuloInvitacion::where('id_expositor', '=', $expositor['id'])->first();
                if ($invitacion) {
                    $expositores[$keyExpositor]->completoInvitacion = true;
                } else {
                    $expositores[$keyExpositor]->completoInvitacion = false;
                }
            }

            $categoriasRaw = null;
            if (Auth::guard()->user()->grupo == 3) {
                $categoriasRaw = \App\Models\CategoriasEmpresa::where('etif', '=', true)->get();
            } else {
                $categoriasRaw = \App\Models\CategoriasEmpresa::where('etif', '=', false)->get();
            }

            $viewData = [
                "exposicion" => $exposicion,
                "expositores" => $expositores,
                'provincias' => \App\Models\Provincia::orderBy('descripcion', 'asc')->get()->toArray(),
                'ciudades' => \App\Models\Ciudad::orderBy('descripcion', 'asc')->get()->toArray(),
                'paises' => \App\Models\Pais::orderBy('nombre', 'asc')->get()->toArray(),
                'categorias' => $categoria0,
                'categoriasRaw' => $categoriasRaw,
                'catalogo' => \App\Models\ModuloCatalogo::where('id_exposicion', '=', $request->id)->orderBy('id', 'desc')->first(),
                'filtro' => $request->filtro
            ];

            return view('App.Expositor.panelAdministrador', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function create()
    {

        try {
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

            if (\App\Models\Expositor::where('usuario', '=', $request->usuarioExpositorEmpresa)->first()) {
                $messageBag->add('danger', 'Ya existe un usuario con el nombre ' . $request->usuarioExpositorEmpresa);
                return back()->withInput();
            }

            $expositor = \App\Models\Expositor::where('id_empresa', '=', $request->empresa)->where('id_exposicion', '=', $request->id_exposicion)->first();
            if ($expositor) {
                $empresa = \App\Models\Empresa::find($request->empresa);
                $messageBag->add('danger', 'Ya existe el expositor ' . $expositor->usuario . ' para la empresa ' . $empresa->razon_social);
                return back()->withInput();
            }

            $newExpositor = new \App\Models\Expositor();
            $newExpositor->id_exposicion = $request->id_exposicion;
            $newExpositor->id_empresa = $request->empresa;
            $newExpositor->password = strtolower(Str::random(10));
            $newExpositor->pertenece_etif = $request->pertenece == 'etif' || Auth::guard()->user()->grupo == 3 ? true : false;

            if ($request->pertenece == 'etif' || Auth::guard()->user()->grupo == 3) {
                $empresa = \App\Models\Empresa::find($newExpositor->id_empresa);
                if (!$empresa->pertenece_etif) {
                    $newExpositor->aprobado = false;
                    $novedad = new \App\Models\NovedadesEmpresa();
                    $novedad->id_user = Auth::guard()->user()->id;
                    $novedad->id_exposicion = $request->id_exposicion;
                    $novedad->id_empresa = $empresa->id;
                    $novedad->descripcion = "Ref: Base de Datos " . $empresa->razon_social;
                    $novedad->status = Auth::guard()->user()->grupo == 1 ? 'aprobado' : 'pendiente';
                    $novedad->save();
                }

                if (Auth::guard()->user()->grupo != 1) {
                    $messageBag->add('success', 'El expositor fue enviado al Administrador para ser aprobado');
                }
            }

            if ($request->catalogo == 'envase') {
                $newExpositor->catalogo = true;
            } else if ($request->catalogo == 'etif') {
                $newExpositor->etif = true;
            }

            $newExpositor->usuario = $request->usuarioExpositor;
            $newExpositor->save();

            $notificaciones = \App\Models\Notificacion::where('id_exposicion', '=', $newExpositor->id_exposicion)->get();

            foreach ($notificaciones as $key => $value) {
                $notificacionExpositor = new \App\Models\NotificacionExpositor();
                $notificacionExpositor->id_expositor = $newExpositor->id;
                $notificacionExpositor->id_notificacion = $value->id;
                $notificacionExpositor->save();
            }

            $messageBag->add('success', 'Exposotior ' . $newExpositor->usuario . ' creado con éxito');

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getById(Request $request)
    {

        try {

            $expositor = \App\Models\Expositor::find($request->idExpositor);
            $empresa = \App\Models\Empresa::find($expositor->id_empresa);


            $data = [
                'expositor' => $expositor,
                'empresa' => $empresa
            ];

            return response()->json($data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function storeExpositorWithEmpresa(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            //dd($request->contactoApellido);
            //dd($request->recibe_email);

            if (\App\Models\Expositor::where('usuario', '=', $request->usuarioExpositorEmpresa)->first() && !$request->idExpositor) {
                $messageBag->add('danger', 'Ya existe un usuario con el nombre ' . $request->usuarioExpositorEmpresa);
                return back()->withInput();
            }

            $idEmpresa = $request->idEmpresa ? (int) $request->idEmpresa : null;
            $idExposicion = $request->idExposicion;

            if ($request->idExpositor) {
                $newExpositor = \App\Models\Expositor::find($request->idExpositor);
            } else {
                $newExpositor = new \App\Models\Expositor();
                $newExpositor->password = strtolower(Str::random(10));
                if (Auth::guard()->user()->grupo == 3) {
                    $newExpositor->pertenece_etif = true;
                }
            }

            $newExpositor->id_exposicion = $idExposicion;
            $newExpositor->usuario = $request->usuarioExpositorEmpresa;
            $newExpositor->password = $request->passwordExpositorEmpresa;
            $newExpositor->whatsapp = $request->expositorWhatsapp;
            $newExpositor->pertenece_etif = $request->pertenece == 'etif' ? true : false;

            if ($request->catalogo == 'envase') {
                $newExpositor->etif = false;
                $newExpositor->catalogo = true;
            } else if ($request->catalogo == 'etif') {
                $newExpositor->etif = true;
                $newExpositor->catalogo = false;
            }

            $newExpositor->save();

            if (!$request->idExpositor) {
                $notificaciones = \App\Models\Notificacion::where('id_exposicion', '=', $newExpositor->id_exposicion)->get();

                foreach ($notificaciones as $key => $value) {
                    $notificacionExpositor = new \App\Models\NotificacionExpositor();
                    $notificacionExpositor->id_expositor = $newExpositor->id;
                    $notificacionExpositor->id_notificacion = $value->id;
                    $notificacionExpositor->save();
                }
            }

            $expositorModuloCatalogo = \App\Models\ExpositorModuloCatalogo::where('id_expositor', '=', $request->idExpositor)->first();

            if (!$expositorModuloCatalogo) {
                $expositorModuloCatalogo = new \App\Models\ExpositorModuloCatalogo();
                $expositorModuloCatalogo->id_expositor = $newExpositor->id;
            }

            $expositorModuloCatalogo->nombre = $request->catalgoNombre;
            $expositorModuloCatalogo->direccion = $request->catalogoDireccion;
            $expositorModuloCatalogo->telefono = $request->catalogoTelefono;
            $expositorModuloCatalogo->whatsapp = $request->catalogoWhatsapp;
            $expositorModuloCatalogo->email = $request->catalogoEmail;
            $expositorModuloCatalogo->web = $request->catalogoWeb;
            $expositorModuloCatalogo->save();

            $lastExposicionActive = \App\Models\Exposicion::where('active', '=', true)->orderBy('id', 'desc')->first();

            $newEmpresa = null;
            if ($idEmpresa) {
                $newEmpresa = \App\Models\Empresa::find($request->idEmpresa);
            } else {
                $newEmpresa = new \App\Models\Empresa();
            }

            $newEmpresa->cuit = $request->cuit;
            $newEmpresa->codigo_iae = $request->codigo_iae;
            $newEmpresa->razon_social = $request->razon_social;
            $newEmpresa->nombre_fantasia = $request->nombre_fantasia;
            $newEmpresa->descripcion = $request->descripcion;
            $newEmpresa->facebook = $request->facebook;
            $newEmpresa->instagram = $request->instagram;
            $newEmpresa->youtube = $request->youtube;
            $newEmpresa->linkedin = $request->linkedin;
            $newEmpresa->origen = $request->origen;
            $newEmpresa->fecha_fundacion = $request->fecha_fundacion;
            $newEmpresa->id_user = Auth::user()->id;

            $newEmpresa->id_pais = $request->id_pais;
            if ($newEmpresa->id_pais == '11') {
                $newEmpresa->id_provincia = $request->id_provincia;
                $newEmpresa->id_ciudad = $request->id_ciudad;
            } else {
                $newEmpresa->provincia = $request->provincia;
                $newEmpresa->ciudad = $request->ciudad;
            }

            $newEmpresa->codigo_postal = $request->codigo_postal;
            $newEmpresa->direccion = $request->direccion;
            $newEmpresa->telefono = $request->telefono;
            $newEmpresa->whatsapp = $request->whatsapp;
            $newEmpresa->email = $request->email;
            $newEmpresa->web = $request->web;
            $newEmpresa->fecha_alta = $request->fecha_alta;
            $newEmpresa->fecha_baja = $request->fecha_baja;

            $newEmpresa->recibe_email = isset($request->recibe_email) ? true : false;
            $newEmpresa->exportador = isset($request->exportador) ? true : false;
            $newEmpresa->importador = isset($request->importador) ? true : false;
            $newEmpresa->representante = isset($request->representante) ? true : false;
            $newEmpresa->distribuidor = isset($request->distribuidor) ? true : false;
            $newEmpresa->fabricante = isset($request->fabricante) ? true : false;

            $newEmpresa->save();

            if ($request->pertenece == 'etif' || Auth::guard()->user()->grupo == 3) {

                $newExpositor->aprobado = Auth::guard()->user()->grupo == 1 ? true : false;
                $newExpositor->save();

                $novedad = new \App\Models\NovedadesEmpresa();
                $novedad->id_user = Auth::guard()->user()->id;
                $novedad->id_exposicion = $idExposicion;
                $novedad->id_empresa = $newEmpresa->id;
                if ($idEmpresa) {
                    $novedad->descripcion = "Ref: Base de Datos " . $newEmpresa->razon_social;
                } else {
                    $novedad->descripcion = "Ref: Nueva " . $newEmpresa->razon_social;
                }
                $novedad->status = Auth::guard()->user()->grupo == 1 ? 'aprobado' : 'pendiente';
                $novedad->save();

                if (Auth::guard()->user()->grupo != 1) {
                    $messageBag->add('success', 'El expositor fue enviado al Administrador para ser aprobado');
                }
            }

            $exposicion = Exposicion::find($idExposicion);
            $expositor = \App\Models\Expositor::where('id_exposicion', '=', $idExposicion)->where('id_empresa', '=', $newEmpresa->id)->first();
            $stands = \App\Models\Stand::where('id_expositor', '=', $newExpositor->id)->get();

            foreach ($stands as $key => $stand) {

                $mapa = json_decode($exposicion->mapa);
                $keyStand = null;
                foreach ($mapa->artboards[0]->children as $key => $child) {
                    if ($child->id == $stand->id_children_mapa) {
                        $keyStand = $key;
                        break; // Salimos del bucle una vez encontrado el objeto
                    }
                }

                if ($keyStand !== null) {
                    $title = "# " . $stand->numero . " - " . ($expositor->nombre_exposicion == 'nombre_fantasia' ? $newEmpresa->nombre_fantasia : $newEmpresa->razon_social);
                    $mapa->artboards[0]->children[$keyStand]->title = $title;
                    $mapa->artboards[0]->children[$keyStand]->tooltip_content[0]->text = $title;
                    $mapa->artboards[0]->children[$keyStand]->actions->script = 'openModalAsignarStand("' . $title . '")';
                    $exposicion->mapa = json_encode($mapa);
                    $exposicion->save();
                }
            }

            // Esto borra
            // Obtener categorias de la empresa
            $categorias = \App\Models\CategoriaEmpresa::leftJoin('categorias_empresa', 'categorias_empresa.id', '=', 'categoria_empresa.id_categorias_empresa')
                ->select('categoria_empresa.id', 'categoria_empresa.id_categorias_empresa')
                ->where('id_empresa', '=', $newEmpresa['id'])
                ->get();
            for ($i = 0; $i < count($categorias); $i++) {
                // Si el input de categoria tiene value true delete
                if ($request->get('categoria' . $categorias[$i]['id']) == '1') {
                    $categorias[$i]->delete();

                    $categoriaExpositor = \App\Models\CategoriaEmpresa::where('id_empresa', '=', $newEmpresa->id)
                        ->where('id_categorias_empresa', '=', $categorias[$i]->id_categorias_empresa)
                        ->where('id_exposicion', '=', null)
                        ->first();

                    if ($categoriaExpositor) {
                        $categoriaExpositor->delete();
                    }
                }
            }

            $productos = \App\Models\ProductoServicioEmpresa::where('id_empresa', '=', $newEmpresa['id'])->get();
            for ($i = 0; $i < count($productos); $i++) {
                if ($request->get('deleteProdServExist' . $productos[$i]['id']) == '1') {
                    $productos[$i]->delete();
                } else if ($request->file('prodServImagenExist' . $productos[$i]['id'])) {
                    $newFile = new \App\Models\ProductoServicioEmpresa();
                    $newFile->id_empresa = $newEmpresa->id;
                    $newFile->id_exposicion = $$idExposicion;
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

            $empresasRepresentadas = \App\Models\EmpresaRepresentada::where('id_empresa', '=', $newEmpresa['id'])->get();
            for ($i = 0; $i < count($empresasRepresentadas); $i++) {
                if ($request->get('deleteEmpresaRepresentadaExist' . $empresasRepresentadas[$i]['id']) == '1') {
                    $empresasRepresentadas[$i]->delete();
                } else if ($request->file('empresaRepresentadaImagenExist' . $empresasRepresentadas[$i]['id'])) {
                    $newFile = new \App\Models\EmpresaRepresentada();
                    $newFile->id_empresa = $newEmpresa->id;
                    $newFile->id_exposicion = $idExposicion;
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

            // Esto crea
            // TODO: Copiar como categorias y logo las productos y servicios, y empresas represntadas
            if ($request->productoServicioNombre) {
                for ($i = 0; $i < count($request->productoServicioNombre); $i++) {

                    $newFile = new \App\Models\ProductoServicioEmpresa();
                    $newFile->id_exposicion = $idExposicion;
                    $newFile->id_empresa = $newEmpresa->id;
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
                    $newFile->id_exposicion = $idExposicion;
                    $newFile->id_empresa = $newEmpresa->id;
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

            if ($request->contactoApellido) {

                for ($i = 0; $i < count($request->contactoApellido); $i++) {

                    $newContacto = new \App\Models\EmpresaContacto();
                    $newContacto->id_empresa = $newEmpresa->id;
                    $newContacto->apellido = $request->contactoApellido[$i];
                    $newContacto->nombre = $request->contactoNombre[$i];
                    $newContacto->cargo = $request->contactoCargo[$i];
                    $newContacto->telefono = $request->contactoWhatsapp[$i];
                    $newContacto->email = $request->contactoEmail[$i];
                    $newContacto->save();
                }
            }

            $contactos = EmpresaContacto::where('id_empresa', '=', $newEmpresa->id)->get();

            foreach ($contactos as $keyContacto => $contacto) {
                if ($request->input('contactoDeleted' . $contacto['id']) == '1') {
                    $contacto->delete();
                } else {
                    if ($request->input('contactoApellido' . $contacto['id'])) {
                        $contacto->apellido = $request->input('contactoApellido' . $contacto['id']);
                        $contacto->nombre = $request->input('contactoNombre' . $contacto['id']);
                        $contacto->cargo = $request->input('contactoCargo' . $contacto['id']);
                        $contacto->telefono = $request->input('contactoWhatsapp' . $contacto['id']);
                        $contacto->email = $request->input('contactoEmail' . $contacto['id']);
                        $contacto->save();
                    }
                }
            }

            //dd($request->categorias);
            if ($request->categorias) {
                for ($i = 0; $i < count($request->categorias); $i++) {
                    $newCategoria = new \App\Models\CategoriaEmpresa();
                    $newCategoria->id_exposicion = $idExposicion;
                    $newCategoria->id_categorias_empresa = $request->categorias[$i];
                    $newCategoria->id_empresa = $newEmpresa->id;
                    $newCategoria->save();

                    $newCategoria = new \App\Models\CategoriaEmpresa();
                    $newCategoria->id_categorias_empresa = $request->categorias[$i];
                    $newCategoria->id_empresa = $newEmpresa->id;
                    $newCategoria->save();
                }
            }

            if ($request->logo) {
                $newFile = new \App\Models\EmpresaLogo();
                $newFile->id_empresa = $newEmpresa->id;
                $newFile->name = $request->logo->getClientOriginalName();
                $newFile->path = $request->logo->store("public/empresa/logo");
                $newFile->path = str_replace("public/empresa/logo/", '', $newFile->path);
                $newFile->save();

                if ($lastExposicionActive) {
                    $newFile = new \App\Models\EmpresaLogo();
                    $newFile->id_exposicion = $lastExposicionActive->id;
                    $newFile->id_empresa = $newEmpresa->id;
                    $newFile->name = $request->logo->getClientOriginalName();
                    $newFile->path = $request->logo->store("public/empresa/logo");
                    $newFile->path = str_replace("public/empresa/logo/", '', $newFile->path);
                    $newFile->save();
                }
            }

            //dd($newEmpresa);

            $newExpositor->id_empresa = $newEmpresa->id;
            $newExpositor->save();

            $messageBag->add('success', 'Empresa ' . $newEmpresa->razon_social . ' con Expositor ' . $newExpositor->usuario . ' guardado con éxito!');

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function edit($id)
    {
        try {
            $expositor = \App\Models\Expositor::find($id);

            if (Auth::guard()->user()->grupo != 4 && Auth::guard()->user()->grupo != 1 && !$expositor->pertenece_etif) {
                return response()->json(['message' => 'Unavailable'], 200);
            }

            $empresa = \App\Models\Empresa::find($expositor->id_empresa);

            $idExposicion = $expositor->id_exposicion;
            $idEmpresa = $empresa->id;

            $exposicion = \App\Models\Exposicion::find($idExposicion);
            $moduloCatalogo = \App\Models\ModuloCatalogo::where('id_exposicion', '=', $idExposicion)->first();

            /*
            $empresa->expositor = \App\Models\Expositor::where('id_empresa', '=', $idEmpresa)
                ->where('id_exposicion', '=', $idExposicion)
                ->first();

            $empresa->stand = \App\Models\Stand::where('id_exposicion', '=', $idExposicion)
                ->where('id_expositor', '=', $empresa->expositor->id)
                ->first();
            */

            $categorias = \App\Models\CategoriaEmpresa::leftJoin('categorias_empresa', 'categorias_empresa.id', '=', 'categoria_empresa.id_categorias_empresa')
                ->select('categoria_empresa.id', 'categorias_empresa.nombre', 'categoria_empresa.id_categorias_empresa')
                ->where('categoria_empresa.id_empresa', '=', $idEmpresa)
                ->where('id_exposicion', '=', $idExposicion)
                ->get()
                ->toArray();

            foreach ($categorias as $key => $value) {
                $categoria = CategoriasEmpresa::find($categorias[$key]['id_categorias_empresa']);
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

            $empresa->logo = \App\Models\EmpresaLogo::where('id_empresa', '=', $empresa->id)
                ->where('id_exposicion', '=', $idExposicion)
                ->orderBy('created_at', 'desc')
                ->first();

            $empresa->productos = \App\Models\ProductoServicioEmpresa::where('id_empresa', '=', $empresa['id'])
                ->where('id_exposicion', '=', $idExposicion)
                ->get();

            $empresa->empresaRepresentadas = \App\Models\EmpresaRepresentada::where('id_empresa', '=', $empresa['id'])
                ->where('id_exposicion', '=', $idExposicion)
                ->get();

            $empresa->contactos = \App\Models\EmpresaContacto::where('id_empresa', '=', $empresa['id'])
                ->get();

            $categoria0 = \App\Models\CategoriasEmpresa::where('nivel', '=', 0);
            if ($expositor->etif || Auth::guard()->user()->grupo == 3) {
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
                    ->get()->map(function ($item) {
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
            if (Auth::guard()->user()->grupo == 3) {
                $categoriasRaw = \App\Models\CategoriasEmpresa::where('etif', '=', true)->get();
            } else {
                $categoriasRaw = \App\Models\CategoriasEmpresa::where('etif', '=', false)->get();
            }

            $expositorModuloCatalogo = \App\Models\ExpositorModuloCatalogo::where('id_expositor', '=', $expositor->id)->first();

            $viewData = [
                'provincias' => \App\Models\Provincia::all()->toArray(),
                'ciudades' => \App\Models\Ciudad::orderBy('descripcion', 'asc')->get()->toArray(),
                'paises' => \App\Models\Pais::all()->toArray(),
                'catalogo' => \App\Models\ModuloCatalogo::where('id_exposicion', '=', $idExposicion)->orderBy('id', 'desc')->first(),
                'categorias' => $categoria0,
                'categoriasRaw' => $categoriasRaw,
                'empresa' => $empresa,
                'expositor' => $expositor,
                "exposicion" => $exposicion,
                "moduloCatalogo" => $moduloCatalogo,
                'expositorModuloCatalogo' => $expositorModuloCatalogo
            ];

            return view('App.Expositor.updateExpositor', ['viewData' => $viewData]);
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

            $expositor = \App\Models\Expositor::find($request->id);

            $stands = \App\Models\Stand::where('id_expositor', '=', $expositor->id)->get();

            foreach ($stands as $key => $stand) {

                $exposicion = \App\Models\Exposicion::find($stand->id_exposicion);

                $mapa = json_decode($exposicion->mapa);
                $keyStand = null;
                foreach ($mapa->artboards[0]->children as $key => $child) {
                    if ($child->id == $stand->id_children_mapa) {
                        $keyStand = $key;
                        break; // Salimos del bucle una vez encontrado el objeto
                    }
                }

                if ($keyStand === null) {
                    $messageBag->add('danger', 'Id Stand desasociado. Debe eliminar en planimetria y volver a crear');
                    return back();
                }

                $stand->id_expositor = null;
                $stand->status = 'libre';
                $stands->nombre_exposicion = null;
                $stand->save();

                //$expositor->delete();

                $mapa->artboards[0]->children[$keyStand]->default_style->background_color = "#777777";
                $title = "# " . $stand->numero . " - LIBRE";
                $mapa->artboards[0]->children[$keyStand]->title = $title;
                $mapa->artboards[0]->children[$keyStand]->tooltip_content[0]->text = $title;
                $mapa->artboards[0]->children[$keyStand]->actions->script = 'openModalAsignarStand("' . $title . '")';
                $exposicion->mapa = json_encode($mapa);
                $exposicion->save();

                $messageBag->add('success', 'Stand ' . $stand->numero . ' liberado exitosamente');
            }

            $expositor->delete();

            $messageBag->add('success', 'Expositor eliminado exitosamente');

            return redirect()->route('expositor.panel-administracion');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function exportar(Request $request)
    {
        try {
            $query = Expositor::leftJoin('empresa', 'empresa.id', '=', 'expositor.id_empresa')
                ->leftJoin('expositor_modulo_catalogo', 'expositor_modulo_catalogo.id_expositor', '=', 'expositor.id')
                ->leftJoin('ciudad', 'ciudad.id', '=', 'empresa.id_ciudad')
                ->leftJoin('provincia', 'provincia.id', '=', 'empresa.id_provincia')
                ->leftJoin('pais', 'pais.id', '=', 'empresa.id_pais')
                ->leftJoin(DB::raw("(SELECT id_expositor, GROUP_CONCAT(DISTINCT numero ORDER BY numero ASC SEPARATOR ' - ') AS stands 
                     FROM stand 
                     GROUP BY id_expositor) as s"), 's.id_expositor', '=', 'expositor.id')
                ->leftJoin('empresa_contacto', 'empresa_contacto.id_empresa', '=', 'empresa.id')
                ->where('expositor.id_exposicion', '=', $request->idExposicion);

            if (Auth::guard()->user()->grupo == 3 || $request->filtro == 'etif') {
                $query->where('expositor.pertenece_etif', '=', true);
            }

            if ($request->filtro == 'envase') {
                $query->where('expositor.pertenece_etif', '=', false);
            }

            $selectFields = [];

            // ID empresa para control de repetición
            $selectFields[] = 'empresa.id as id_empresa';

            // EMPRESA
            if ($request->cuit) $selectFields[] = 'empresa.cuit';
            if ($request->codigo_iae) $selectFields[] = 'empresa.codigo_iae';
            if ($request->razon_social) $selectFields[] = 'empresa.razon_social';
            if ($request->nombre_fantasia) $selectFields[] = 'empresa.nombre_fantasia';
            if ($request->facebook) $selectFields[] = 'empresa.facebook';
            if ($request->instagram) $selectFields[] = 'empresa.instagram';
            if ($request->youtube) $selectFields[] = 'empresa.youtube';
            if ($request->linkedin) $selectFields[] = 'empresa.linkedin';
            if ($request->descripcion) {
                $selectFields[] = DB::raw("
                REGEXP_REPLACE(empresa.descripcion, '<[^>]*>', '') as descripcion
            ");
            }

            // Ciudad
            if ($request->ciudad) {
                $selectFields[] = DB::raw("
                CASE 
                    WHEN empresa.id_ciudad IS NOT NULL THEN ciudad.descripcion 
                    ELSE empresa.ciudad 
                END as ciudad
            ");
            }

            // Provincia
            if ($request->provincia) {
                $selectFields[] = DB::raw("
                CASE 
                    WHEN empresa.id_provincia IS NOT NULL THEN provincia.descripcion 
                    ELSE empresa.provincia 
                END as provincia
            ");
            }

            // País
            if ($request->pais) {
                $selectFields[] = DB::raw("
                CASE 
                    WHEN empresa.id_pais IS NOT NULL THEN pais.nombre 
                    ELSE empresa.pais 
                END as pais
            ");
            }

            // Código postal
            if ($request->codigo_postal) {
                $selectFields[] = DB::raw("
                CASE 
                    WHEN empresa.id_ciudad IS NOT NULL AND empresa.id_ciudad != 5001 THEN ciudad.codigo_postal 
                    ELSE empresa.codigo_postal 
                END as codigo_postal
            ");
            }

            // CATALOGO
            if ($request->direccion) $selectFields[] = 'expositor_modulo_catalogo.nombre';
            if ($request->direccion) $selectFields[] = 'expositor_modulo_catalogo.direccion';
            if ($request->telefono) $selectFields[] = 'expositor_modulo_catalogo.telefono';
            if ($request->whatsapp) $selectFields[] = 'expositor_modulo_catalogo.whatsapp';
            if ($request->email) $selectFields[] = 'expositor_modulo_catalogo.email';
            if ($request->web) $selectFields[] = 'expositor_modulo_catalogo.web';
            if ($request->provincia_catalogo) $selectFields[] = 'expositor_modulo_catalogo.provincia';
            if ($request->localidad) $selectFields[] = 'expositor_modulo_catalogo.localidad';
            if ($request->codigo_postal_catalogo) $selectFields[] = 'expositor_modulo_catalogo.codigo_postal';

            if ($request->stands) {
                $selectFields[] = 's.stands';
            }

            // CONTACTOS
            if ($request->contactos) {
                $selectFields[] = 'empresa_contacto.nombre as contacto_nombre';
                $selectFields[] = 'empresa_contacto.apellido as contacto_apellido';
                $selectFields[] = 'empresa_contacto.cargo as contacto_cargo';
                $selectFields[] = 'empresa_contacto.telefono as contacto_telefono';
                $selectFields[] = 'empresa_contacto.email as contacto_email';
            }

            if (empty($selectFields)) {
                return response()->json(['error' => 'No hay campos seleccionados para exportar.'], 400);
            }

            $data = $query->select($selectFields)->get();

            // Encabezados
            $headers = [];
            foreach ($selectFields as $field) {
                if (strpos($field, ' as ') !== false) {
                    $alias = trim(explode(' as ', $field)[1]);
                    if ($alias !== 'id_empresa') { // No mostrar id_empresa en export
                        $headers[] = ucfirst(str_replace('_', ' ', $alias));
                    }
                } else {
                    $fieldName = explode('.', $field);
                    if (end($fieldName) !== 'id') {
                        $headers[] = ucfirst(str_replace('_', ' ', end($fieldName)));
                    }
                }
            }

            $exportArray = [];
            $exportArray[] = $headers;

            // Control para vaciar columnas repetidas
            $lastEmpresaId = null;

            foreach ($data as $row) {
                $rowData = [];
                $isSameEmpresa = ($lastEmpresaId === $row->id_empresa);

                foreach ($headers as $header) {
                    $key = strtolower(str_replace(' ', '_', $header));

                    if ($isSameEmpresa && strpos($key, 'contacto_') !== 0) {
                        $rowData[] = '';
                    } else {
                        $rowData[] = $row->$key ? html_entity_decode($row->$key, ENT_QUOTES | ENT_HTML5, 'UTF-8') : '';
                    }
                }

                $exportArray[] = $rowData;
                $lastEmpresaId = $row->id_empresa;
            }

            // Calcular anchos
            $columnWidths = [];
            foreach ($exportArray as $row) {
                foreach ($row as $colIndex => $value) {
                    $length = mb_strlen((string)$value);
                    if (!isset($columnWidths[$colIndex]) || $length > $columnWidths[$colIndex]) {
                        $columnWidths[$colIndex] = $length;
                    }
                }
            }

            foreach ($columnWidths as &$width) {
                if ($width < 10) $width = 10;
                if ($width > 50) $width = 50;
            }
            unset($width);

            $fileName = 'export_expositores_' . date('Y_m_d_H_i_s') . '.xlsx';

            return \Maatwebsite\Excel\Facades\Excel::download(
                new class($exportArray, $columnWidths) implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithColumnWidths {
                    private $array;
                    private $widths;

                    public function __construct(array $array, array $widths)
                    {
                        $this->array = $array;
                        $this->widths = $widths;
                    }

                    public function array(): array
                    {
                        return $this->array;
                    }

                    public function columnWidths(): array
                    {
                        $columns = [];
                        foreach ($this->widths as $index => $width) {
                            $columns[$this->numToColumn($index + 1)] = $width;
                        }
                        return $columns;
                    }

                    private function numToColumn(int $num): string
                    {
                        $letter = '';
                        while ($num > 0) {
                            $mod = ($num - 1) % 26;
                            $letter = chr(65 + $mod) . $letter;
                            $num = intval(($num - $mod) / 26);
                        }
                        return $letter;
                    }
                },
                $fileName
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function copiarExpositorPanel(Request $request)
    {
        try {

            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('exposicion.panel');
            } else {
                $exposicion = \App\Models\Exposicion::find($request->id);
            }

            $exposiciones = Exposicion::where('id', '!=', $request->id)->get();

            $viewData = [
                'exposiciones' => $exposiciones,
                'exposicion' => $exposicion
            ];

            return view('App.Expositor.copiarExpositorPanel', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function copiarExpositorPanel2(Request $request)
    {
        try {

            if (!$request->id) {
                //$exposicion = \App\Models\Exposicion::orderBy('id','desc')->first();
                return redirect()->route('exposicion.panel');
            } else {
                $exposicion = \App\Models\Exposicion::find($request->id);
            }

            $exposicionSelected = Exposicion::find($request->idExposicion);
            $expositores = Expositor::leftJoin('empresa', 'empresa.id', '=', 'expositor.id_empresa')
                ->where('id_exposicion', '=', $exposicionSelected['id'])
                ->select(
                    'expositor.id',
                    'expositor.usuario',
                    'empresa.razon_social'
                )
                ->get();

            $viewData = [
                'exposicion' => $exposicion,
                'exposicionSelected' => $exposicionSelected,
                'expositores' => $expositores
            ];

            return view('App.Expositor.copiarExpositorPanel2', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function copiarExpositorPanel3(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $origenExpositor = Expositor::findOrFail($request->idExpositor);

            $expositorExist = Expositor::where('id_exposicion', '=', $request->id)
                ->where('usuario', '=', $origenExpositor->usuario)
                ->first();

            if ($expositorExist) {
                $messageBag->add('danger', 'El expositor ya existe en la exposición destino');
                return back();
            }

            DB::transaction(function () use ($request) {

                // Expositor origen
                $origenExpositor = Expositor::findOrFail($request->idExpositor);

                // Empresa origen
                $empresaOrigen = Empresa::findOrFail($origenExpositor->id_empresa);

                // Copiar empresa
                $nuevaEmpresa = $empresaOrigen->replicate();
                $nuevaEmpresa->save();

                // Copiar expositor
                $newExpositor = $origenExpositor->replicate();
                $newExpositor->id_exposicion = $request->id;
                $newExpositor->id_empresa = $nuevaEmpresa->id;
                $newExpositor->password = random_int(100000, 999999);
                $newExpositor->save();

                // ExpositorModuloCatalogo
                if ($catalogo = ExpositorModuloCatalogo::where('id_expositor', $origenExpositor->id)->first()) {
                    $nuevo = $catalogo->replicate();
                    $nuevo->id_expositor = $newExpositor->id;
                    $nuevo->save();
                }

                // Categorías
                foreach (CategoriaEmpresa::where('id_empresa', $empresaOrigen->id)->get() as $item) {
                    $nuevo = $item->replicate();
                    $nuevo->id_empresa = $nuevaEmpresa->id;
                    $nuevo->id_exposicion = $request->id;
                    $nuevo->save();
                }

                // Logos
                foreach (EmpresaLogo::where('id_empresa', $empresaOrigen->id)->get() as $item) {
                    $nuevo = $item->replicate();
                    $nuevo->id_empresa = $nuevaEmpresa->id;
                    $nuevo->id_exposicion = $request->id;
                    $nuevo->save();
                }

                // Productos / Servicios
                foreach (ProductoServicioEmpresa::where('id_empresa', $empresaOrigen->id)->get() as $item) {
                    $nuevo = $item->replicate();
                    $nuevo->id_empresa = $nuevaEmpresa->id;
                    $nuevo->id_exposicion = $request->id;
                    $nuevo->save();
                }

                // Empresas representadas
                foreach (EmpresaRepresentada::where('id_empresa', $empresaOrigen->id)->get() as $item) {
                    $nuevo = $item->replicate();
                    $nuevo->id_empresa = $nuevaEmpresa->id;
                    $nuevo->id_exposicion = $request->id;
                    $nuevo->save();
                }

                // Contactos
                /*
                foreach (EmpresaContacto::where('id_empresa', $empresaOrigen->id)->get() as $item) {
                    $nuevo = $item->replicate();
                    $nuevo->id_empresa = $nuevaEmpresa->id;
                    $nuevo->save();
                }
                */
            });

            $messageBag->add('success', 'Expositor copiado exitosamente');

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
