<?php

namespace App\Http\Controllers;

use App\Models\Stand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;

class MapaController extends Controller
{
    public function mapaUpdate(Request $request, $idExposicion)
    {

        try {

            $exposicion = \App\Models\Exposicion::find($idExposicion);

            $mapa = json_encode($request->all());
            $mapa = json_decode($mapa);

            //Log::debug(json_encode($mapa));

            if (!isset($mapa->artboards[0]) || !$mapa->artboards[0]) {
                $mapa->artboards[0] = new \stdClass();
            }

            $mapa->artboards[0]->background_type = "image";
            $mapa->artboards[0]->image_url = $exposicion->url_mapa;

            $oldMapa = json_decode($exposicion->mapa);

            if ($oldMapa) {
                if (isset($oldMapa->artboards[0]->children)) {
                    foreach ($oldMapa->artboards[0]->children as $key => $oldChildren) {
                        // Verifica si existe un elemento en $mapa->artboards[0]->children con el mismo id que $oldChildren->id
                        $exists = false;
                        foreach ($mapa->artboards[0]->children as $children) {
                            if ($children->id == $oldChildren->id) {
                                $exists = true;
                                break;
                            }
                        }

                        // ELIMINACION EN PLANIMETRIA
                        if (!$exists) {
                            $stand = \App\Models\Stand::where('id_exposicion', '=', $idExposicion)
                                ->where('id_children_mapa', '=', $oldChildren->id)
                                ->first();

                            if ($stand) {
                                $stand->delete();
                            }
                        }
                    }
                }
            }

            if (isset($mapa->artboards[0]->children)) {
                if (!isset($mapa->object_list) || $mapa->object_list->enable_object_list == false) {
                    $mapa->object_list = new \stdClass();
                    $mapa->object_list->enable_object_list = true;
                }
                if (!isset($mapa->zooming) || $mapa->zooming == false) {
                    $mapa->zooming = new \stdClass();
                    $mapa->zooming->enable_zooming = true;
                }

                for ($i = 0; $i < count($mapa->artboards[0]->children); $i++) {

                    if (!isset($mapa->artboards[0]->children[$i]->tooltip_content)) {
                        $mapa->artboards[0]->children[$i]->tooltip_content = [
                            [
                                "type" => "Heading",
                                "heading" => "h3",
                                "style" => [
                                    "fontFamily" => "sans-serif",
                                    "fontSize" => 20.8,
                                    "lineHeight" => "normal",
                                    "color" => "#ffffff",
                                    "textAlign" => "left"
                                ],
                                "boxModel" => [
                                    "width" => "auto",
                                    "height" => "auto",
                                    "margin" => [
                                        "top" => 0,
                                        "bottom" => 0,
                                        "left" => 0,
                                        "right" => 0
                                    ],
                                    "padding" => [
                                        "top" => 10,
                                        "bottom" => 10,
                                        "left" => 10,
                                        "right" => 10
                                    ]
                                ]
                            ]
                        ];
                    }

                    //$mapa->artboards[0]->children[$i]->title = ' ';


                    //$mapa->artboards[0]->children[$i]->default_style->background_color = '#777777';

                    /*
                    $mapa->artboards[0]->children[$i]->tooltip = (object) array ("enable_tooltip" => true);
                    $mapa->artboards[0]->children[$i]->tooltip_content = array(
                        (object) array(
                            'type' => 'Heading',
                            'heading' => 'h3',
                            'style' => (object) array(
                                'fontFamily' => 'sans-serif',
                                'fontSize' => 20.8,
                                'lineHeight' => 'normal',
                                'color' => '#ffffff',
                                'textAlign' => 'left',
                            ),
                            'boxModel' => (object) array(
                                'width' => 'auto',
                                'height' => 'auto',
                                'margin' => (object) array(
                                    'top' => 0,
                                    'bottom' => 0,
                                    'left' => 0,
                                    'right' => 0,
                                ),
                                'padding' => (object) array(
                                    'top' => 10,
                                    'bottom' => 10,
                                    'left' => 10,
                                    'right' => 10,
                                ),
                            ),
                        ),
                    );
                    */
                    //unset($mapa->artboards[0]->children[$i]->tooltip_content);
                    /*
                    if (isset($mapa->artboards[0]->children[$i]->tooltip_content[0]->text) && $mapa->artboards[0]->children[$i]->tooltip_content[0]->text == 'STAND - - LIBRE') {
                        //Log::debug('ENCONTRADO');
                        $mapa->artboards[0]->children[$i]->title = '';
                    }
                    */
                    /*
                    $mapa->artboards[0]->children[$i]->title = '';
                    $mapa->artboards[0]->children[$i]->tooltip_content[0]->text = '';
                    */

                    if (!isset($mapa->artboards[0]->children[$i]->mouseover_style)) {
                        $mapa->artboards[0]->children[$i]->mouseover_style = [
                            "background_color" => "#777777",
                            "background_opacity" => 0.5,
                            "border_radius" => 0,
                            "icon_size" => 5
                        ];
                    }

                    if (!isset($mapa->artboards[0]->children[$i]->tooltip_style)) {
                        $mapa->artboards[0]->children[$i]->tooltip_style = [
                            "auto_width" => true
                        ];
                    }

                    if (!isset($mapa->artboards[0]->children[$i]->default_style)) {
                        $mapa->artboards[0]->children[$i]->default_style = [
                            "border_radius" => 0
                        ];
                    }

                    if (
                        isset($mapa->artboards[0]->children[$i]->tooltip) && $mapa->artboards[0]->children[$i]->tooltip->enable_tooltip == false
                        || !isset($mapa->artboards[0]->children[$i]->tooltip)
                    ) {
                        $mapa->artboards[0]->children[$i]->tooltip = (object) array(
                            "enable_tooltip" => true
                        );
                    }

                    if (!isset($mapa->artboards[0]->children[$i]->title) || $mapa->artboards[0]->children[$i]->title == 'Rect' || $mapa->artboards[0]->children[$i]->title == 'Poly') {
                        $mapa->artboards[0]->children[$i]->title = "# - LIBRE";
                    }

                    if (isset($mapa->artboards[0]->children[$i]->tooltip_content[0]->text) && preg_match('/^\d+[a-zA-Z\s]?$/', $mapa->artboards[0]->children[$i]->tooltip_content[0]->text)) {
                        $numeroStand = $mapa->artboards[0]->children[$i]->tooltip_content[0]->text;
                        $mapa->artboards[0]->children[$i]->tooltip_content[0]->text = '# ' . $numeroStand . ' - LIBRE';
                        $mapa->artboards[0]->children[$i]->title = $mapa->artboards[0]->children[$i]->tooltip_content[0]->text;
                        $mapa->artboards[0]->children[$i]->default_style = (object) array(
                            "background_color" => "#777777",
                            "background_opacity" => 0.5,
                            "border_radius" => 0,
                            "icon_size" => 5
                        );

                        $stand = \App\Models\Stand::where('id_exposicion', '=', $idExposicion)
                            ->where(function ($query) use ($i, $mapa, $numeroStand) {
                                $query->where('id_children_mapa', '=', $mapa->artboards[0]->children[$i]->id);
                                $query->orWhere('numero', '=', $numeroStand);
                            })
                            ->first();
                        if (!$stand) {
                            $stand = new \App\Models\Stand();
                        }
                        $stand->id_exposicion = $idExposicion;
                        $stand->numero = $numeroStand;
                        $stand->status = 'libre';
                        $stand->id_children_mapa = $mapa->artboards[0]->children[$i]->id;
                        $stand->save();
                    }

                    $mapa->artboards[0]->children[$i]->actions = new \stdClass();
                    $mapa->artboards[0]->children[$i]->actions->click = 'run-script';
                    //Log::debug(json_encode($mapa->artboards[0]->children[$i]));
                    $mapa->artboards[0]->children[$i]->actions->script = 'openModalAsignarStand("' . $mapa->artboards[0]->children[$i]->title . '")';
                }
            }

            $exposicion->mapa = json_encode($mapa);
            $exposicion->save();


            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function mapaGet($idExposicion)
    {

        try {

            $exposicion = \App\Models\Exposicion::find($idExposicion);
            Log::debug('GUARDANDO');

            return response()->json($exposicion->mapa);
        } catch (\Exception $ex) {
            Log::error($ex);
        }
    }


    public function mapaGetStands($idExposicion)
    {

        try {

            $stands = \App\Models\Stand::leftJoin('expositor', 'expositor.id', '=', 'stand.id_expositor')
                ->select(
                    'stand.*',
                    'expositor.id as id_expositor'
                )
                ->where('stand.id_exposicion', '=', $idExposicion)
                ->get()
                ->toArray();

            return response()->json($stands);
        } catch (\Exception $ex) {
            Log::error($ex);
        }
    }

    public function updateStoreStand(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            //dd($request->all());

            if (!$request->empresa) {
                $stand = \App\Models\Stand::where('id_exposicion', '=', $request->id_exposicion)
                    ->where('numero', '=', $request->numero_stand)
                    ->first();
                if ($stand) {
                    $stand->metros_cuadrados = $request->metros_cuadrados;
                    $stand->save();
                    $messageBag->add('danger', 'Stand guardado sin expositor correctamente, solamente metros cuadrados');
                    return back();
                }

                $messageBag->add('danger', 'Debe seleccionar una empresa');
                return back();
            }

            $expositorExist = \App\Models\Expositor::where('id_exposicion', '=', $request->id_exposicion)
                ->where('id_empresa', '=', $request->empresa)
                ->first();

            // UPDATE
            if ($expositorExist) {

                //Log::debug('ACTUALIZANDO STAND');

                $exposicion = \App\Models\Exposicion::find($request->id_exposicion);
                $stand = \App\Models\Stand::where('id_exposicion', '=', $request->id_exposicion)
                    ->where('id_expositor', '=', $expositorExist->id)
                    ->where('numero', '=', $request->numero_stand)
                    ->first();

                $empresa = \App\Models\Empresa::find($request->empresa);

                if (!$stand) {
                    $stand = \App\Models\Stand::where('id_exposicion', '=', $request->id_exposicion)
                        ->where('id_expositor', '=', $expositorExist->id)
                        ->first();

                    /*
                    if ($stand) {
                        $messageBag->add('danger', 'La empresa ' . $empresa->razon_social . ' ya posee el stand ' . $stand->numero);
                        return back();
                    } 
                    */
                } else {
                    $mapa = json_decode($exposicion->mapa);
                    $keyStand = null;
                    //Log::debug(json_encode($stand));
                    foreach ($mapa->artboards[0]->children as $key => $children) {
                        if ($children->id == $stand->id_children_mapa) {
                            $keyStand = $key;
                            break;
                        }
                    }

                    //Log::debug(['key', $keyStand]);
                    if ($keyStand === null) {
                        $messageBag->add('danger', '· Id Stand desasociado. Debe eliminarlo en planimetria y volver a crearlo');
                        return back();
                    }

                    $stand->metros_cuadrados = $request->metros_cuadrados;
                    $stand->status = $request->status;
                    $stand->valor = $request->valor;
                    //dd($stand->nombre_exposicion);
                    $stand->nombre_exposicion = $request->nombre_exposicion == 'nombre_fantasia' ? 'nombre_fantasia' : 'razon_social';
                    $stand->moneda = $request->moneda;
                    $stand->id_stand_tipo = $request->id_stand_tipo;

                    $stand->save();

                    $color = '';
                    //dd($request->status);
                    if ($request->status == 'reservado') {
                        $color = '#FFFF7F';
                    }
                    if ($request->status == 'solicitud_pendiente') {
                        $color = '#157347';
                    }
                    if ($request->status == 'pagado') {
                        $color = '#9191FF';
                        Log::debug('$color: ' . $color);
                        Log::debug('$mapa->artboards[0]->children[$keyStand]->default_style->background_color: ' . $mapa->artboards[0]->children[$keyStand]->default_style->background_color);
                        Log::debug('$request->status: ' . $request->status);
                        if ($mapa->artboards[0]->children[$keyStand]->default_style->background_color != $color) {
                            $stand->id_user = Auth::user()->id;
                            $stand->save();
                        }
                    }

                    if ($keyStand !== null) {
                        $mapa->artboards[0]->children[$keyStand]->default_style->background_color = $color;
                        $title = "# " . $stand->numero . " - " . ($request->nombre_exposicion == 'nombre_fantasia' ? $empresa->nombre_fantasia : $empresa->razon_social);

                        $mapa->artboards[0]->children[$keyStand]->title = $title;
                        $mapa->artboards[0]->children[$keyStand]->tooltip_content[0]->text = $title;
                        $mapa->artboards[0]->children[$keyStand]->actions->script = 'openModalAsignarStand("' . $title . '")';

                        $exposicion->mapa = json_encode($mapa);
                        $exposicion->save();

                        $messageBag->add('success', 'Stand ' . $stand->numero . ' actualizado correctamente');
                    }

                    $messageBag->add('success', 'Stand ' . $stand->numero . ' actualizado con éxito');

                    return back();
                }
            }

            // CREATE
            //Log::debug(json_encode(($request->all())));
            $empresa = \App\Models\Empresa::find($request->empresa);
            $exposicion = \App\Models\Exposicion::find($request->id_exposicion);
            $stand = \App\Models\Stand::where('id_exposicion', '=', $request->id_exposicion)
                ->where('numero', '=', $request->numero_stand)
                ->first();


            //Log::debug($stand->id_children_mapa);

            $mapa = json_decode($exposicion->mapa);

            $keyStand = null;
            foreach ($mapa->artboards[0]->children as $key => $children) {
                if ($children->id == $stand->id_children_mapa) {
                    $keyStand = $key;
                    break;
                }
            }

            if ($keyStand === null) {
                $messageBag->add('danger', 'Id Stand desasociado. Debe eliminarlo en planimetria y volver a crearlo');
                return back();
            }

            if (!$expositorExist) {
                $newExpositor = new \App\Models\Expositor();
                $newExpositor->id_exposicion = $request->id_exposicion;
                $newExpositor->id_empresa = $request->empresa;
                $newExpositor->usuario = $request->usuarioExpositor;
                $newExpositor->password = strtolower(Str::random(10));
            } else {
                $newExpositor = $expositorExist;
            }
            $newExpositor->whatsapp = $request->expositorWhatsapp;
            $newExpositor->save();

            if (!$expositorExist) {
                $notificaciones = \App\Models\Notificacion::where('id_exposicion', '=', $newExpositor->id_exposicion)->get();

                foreach ($notificaciones as $key => $value) {
                    $notificacionExpositor = new \App\Models\NotificacionExpositor();
                    $notificacionExpositor->id_expositor = $newExpositor->id;
                    $notificacionExpositor->id_notificacion = $value->id;
                    $notificacionExpositor->save();
                }
            }

            if (!$expositorExist) {
                $staticCategoriasEmpresa = \App\Models\CategoriaEmpresa::where('id_empresa', '=', $request->empresa)->where('id_exposicion', '=', null)->get();

                foreach ($staticCategoriasEmpresa as $key => $item) {
                    $newCategoriaEmpresa = new \App\Models\CategoriaEmpresa();
                    $newCategoriaEmpresa->id_empresa = $item->id_empresa;
                    $newCategoriaEmpresa->id_categorias_empresa = $item->id_categorias_empresa;
                    $newCategoriaEmpresa->id_exposicion = $request->id_exposicion;
                    $newCategoriaEmpresa->save();
                }

                $staticEmpresaLogo = \App\Models\EmpresaLogo::where('id_empresa', '=', $request->empresa)->where('id_exposicion', '=', null)->first();

                if ($staticEmpresaLogo) {
                    $newEmpresaLogo = new \App\Models\EmpresaLogo();
                    $newEmpresaLogo->id_empresa = $staticEmpresaLogo->id_empresa;
                    $newEmpresaLogo->name = $staticEmpresaLogo->name;
                    $newEmpresaLogo->path = $staticEmpresaLogo->path;
                    $newEmpresaLogo->id_exposicion = $request->id_exposicion;
                    $newEmpresaLogo->save();
                }
            }

            $stand->metros_cuadrados = $request->metros_cuadrados;
            $stand->status = $request->status;
            $stand->valor = $request->valor;
            $stand->id_expositor = $newExpositor->id;
            $stand->nombre_exposicion = $request->nombre_exposicion == 'nombre_fantasia' ? 'nombre_fantasia' : 'razon_social';
            $stand->moneda = $request->moneda;
            $stand->id_stand_tipo = $request->id_stand_tipo;
            $stand->save();

            $color = '';
            if ($request->status == 'reservado') {
                $color = '#FFFF7F';
            }
            if ($request->status == 'solicitud_pendiente') {
                $color = '#157347';
            }
            if ($request->status == 'pagado') {
                $color = '#9191FF';
            }

            Log::debug('2$mapa->artboards[0]->children[$keyStand]->default_style->background_color: ' . $mapa->artboards[0]->children[$keyStand]->default_style->background_color);
            Log::debug('2$request->status: ' . $request->status);
            Log::debug('$color: ' . $color);
            if ($mapa->artboards[0]->children[$keyStand]->default_style->background_color != $color && $request->status == 'pagado') {
                $stand->id_user = Auth::user()->id;
                $stand->save();
            }

            $mapa->artboards[0]->children[$keyStand]->default_style->background_color = $color;

            $title = "# " . $stand->numero . " - " . ($request->nombre_exposicion == 'nombre_fantasia' ? $empresa->nombre_fantasia : $empresa->razon_social);

            $mapa->artboards[0]->children[$keyStand]->title = $title;
            $mapa->artboards[0]->children[$keyStand]->tooltip_content[0]->text = $title;
            $mapa->artboards[0]->children[$keyStand]->actions->script = 'openModalAsignarStand("' . $title . '")';
            $exposicion->mapa = json_encode($mapa);
            $exposicion->save();

            $messageBag->add('success', 'Empresa  ' . $empresa->razon_social . ' asociada al stand ' . $stand->numero . ' con éxito');

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

            $stand = \App\Models\Stand::find($request->idStand);
            $expositor = \App\Models\Expositor::find($stand->id_expositor);

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
            //$messageBag->add('success', 'Expositor eliminado exitosamente');

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function generarStringAleatorioUnico($length = 10)
    {
        // Función para generar un string aleatorio de letras minúsculas
        $generateString = function ($length) {
            return strtolower(Str::random($length));
        };

        // Generar string y verificar que no exista en la base de datos
        do {
            $string = $generateString($length);
            $exists = \App\Models\Expositor::where('usuario', $string)->exists();
        } while ($exists);

        return $string;
    }

    function getStandById(Request $request)
    {
        try {
            $stand = Stand::find($request->id);

            return response()->json([$stand], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 500);
        }
    }
}
