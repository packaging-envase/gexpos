<?php

namespace App\Http\Controllers;

use App\Models\Congreso;
use App\Models\CongresoModulo;
use App\Models\CongresoModuloDisertante;
use App\Models\CongresoModuloLocation;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CongresoController extends Controller
{

    public function panel(Request $request)
    {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            //$messageBag->add('success', '');

            $viewData = [
                'congresos' => Congreso::all()
            ];

            return view('App.Congreso.panel', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createView(Request $request)
    {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            //$messageBag->add('success', '');

            if ($request->idCongreso) {
                $congreso = Congreso::find($request->idCongreso)->toArray();
                $congreso['congresoModulo'] = CongresoModulo::leftJoin('congreso_modulo_location', 'congreso_modulo_location.id', '=', 'congreso_modulo.id_location')
                    ->select('congreso_modulo.*', 'congreso_modulo_location.descripcion as location_descripcion')
                    ->where('congreso_modulo.id_congreso', '=', $request->idCongreso)
                    ->get()
                    ->toArray();

                foreach ($congreso['congresoModulo'] as $key => $modulo) {
                    $congreso['congresoModulo'][$key]['disertantes'] = CongresoModuloDisertante::where('id_congreso_modulo', '=', $modulo['id'])->get()->toArray();
                }



                //Ordenar $congreso['congresoModulo'] por desde
                usort($congreso['congresoModulo'], function ($a, $b) {

                    $locationCompare = strcmp(
                        $a['location_descripcion'],
                        $b['location_descripcion']
                    );

                    if ($locationCompare !== 0) {
                        return $locationCompare;
                    }

                    return strtotime($a['desde']) - strtotime($b['desde']);
                });

                $locaciones = CongresoModuloLocation::where('id_congreso', '=', $request->idCongreso)->get()->toArray();
            } else {
                $congreso = null;
                $locaciones = null;
            }


            $viewData = [
                'congreso' => $congreso,
                'locaciones' => $locaciones
            ];

            return view('App.Congreso.create', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function createPost(Request $request)
    {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            //$messageBag->add('success', '');

            if ($request->idCongreso) {
                $congreso = Congreso::find($request->idCongreso);
            } else {
                $congreso = new Congreso();
            }

            $congreso->nombre = $request->nombre;
            $congreso->descripcion = $request->descripcion;
            $congreso->desde = $request->desde;
            $congreso->hasta = $request->hasta;
            $congreso->location = $request->location;
            $congreso->save();

            $messageBag->add('success', 'Congreso guardado con éxito');

            return $this->createView(new Request(['idCongreso' => $congreso->idCongeso]));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function moduloCreateView(Request $request)
    {
        try {
            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            //$messageBag->add('success', '');

            if ($request->idCongresoModulo) {
                $modulo = CongresoModulo::find($request->idCongresoModulo);
            } else {
                $modulo = null;
            }

            $viewData = [
                'congreso' => Congreso::find($request->idCongreso),
                'modulo' => $modulo,
                'locaciones' => CongresoModuloLocation::where('id_congreso', '=', $request->idCongreso)->get()->toArray(),
                'break' => $request->break == 'true' ? 'true' : 'false'
            ];

            return view('App.Congreso.moduloCreate', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function moduloCreatePost(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            //$messageBag->add('success', '');

            if ($request->idModulo) {
                $modulo = CongresoModulo::find($request->idModulo);
            } else {
                $modulo = new CongresoModulo();
                $modulo->id_congreso = $request->idCongreso;
            }

            // Validar superposición de horarios
            $superpuesto = CongresoModulo::where('id_congreso', $modulo->id_congreso)
                ->where('id_location', '=', $request->id_location)
                ->where('id', '!=', $modulo->id)
                ->where(function ($query) use ($request) {
                    $query->where('desde', '<', $request->hasta)
                        ->where('hasta', '>', $request->desde);
                })
                ->exists();

            if ($superpuesto) {
                $messageBag->add('danger', 'El horario ingresado se superpone con otro módulo del congreso.');

                return back();
            }

            $modulo->nombre = $request->nombre;
            $modulo->nombre_color = $request->nombre_color;
            $modulo->descripcion = $request->descripcion;
            $modulo->descripcion_color = $request->descripcion_color;
            $modulo->auspiciante = $request->auspiciante;
            $modulo->auspiciante_color = $request->auspiciante_color;
            $modulo->background = $request->background;
            $modulo->desde = $request->desde;
            $modulo->hasta = $request->hasta;
            $modulo->id_location = $request->id_location;

            $modulo->save();

            $messageBag->add('success', 'Modulo guardado con éxito');

            return $this->createView(
                new Request(['idCongreso' => $request->idCongreso])
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function moduloDisertanteCreateView(Request $request)
    {
        try {

            if ($request->idDisertante) {
                $disertante = CongresoModuloDisertante::find($request->idDisertante);
            } else {
                $disertante = null;
            }

            $congreso = Congreso::find($request->idCongreso);

            $viewData = [
                'idCongreso' => $request->idCongreso,
                'idCongresoModulo' => $request->idCongresoModulo,
                'congreso' => $congreso,
                'disertante' => $disertante
            ];

            return view('App.Congreso.moduloDisertanteCreate', ['viewData' => $viewData]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function moduloDisertanteCreatePost(Request $request)
    {
        try {


            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            if ($request->filled('id_disertante')) {
                $disertante = CongresoModuloDisertante::findOrFail($request->id_disertante);
            } else {
                $disertante = new CongresoModuloDisertante();
                $disertante->id_congreso_modulo = $request->id_congreso_modulo;
            }

            $disertante->apellido_nombre = $request->apellido_nombre;
            $disertante->titulo = $request->titulo;
            $disertante->especialidad = $request->especialidad;
            $disertante->cargo = $request->cargo;
            $disertante->empresa = $request->empresa;
            $disertante->descripcion = $request->descripcion;

            if ($request->hasFile('path_foto')) {

                // Borra la foto anterior
                if ($disertante->path_foto && file_exists(public_path($disertante->path_foto))) {
                    unlink(public_path($disertante->path_foto));
                }

                $archivo = $request->file('path_foto');
                $nombre = uniqid() . '.' . $archivo->getClientOriginalExtension();

                $archivo->move(public_path('uploads/disertantes'), $nombre);

                $disertante->path_foto = 'uploads/disertantes/' . $nombre;
            }

            $disertante->save();
            $messageBag->add('success', 'Disertante guardado con éxito');

            return redirect()->route('congreso.panel');
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function ModuloDisertantePublic(Request $request)
    {
        $idCongreso = $request->get('idCongreso');

        if (!$idCongreso) {
            abort(404);
        }

        $disertantes = CongresoModuloDisertante::select(
            'congreso_modulo_disertante.*'
        )
            ->join(
                'congreso_modulo',
                'congreso_modulo.id',
                '=',
                'congreso_modulo_disertante.id_congreso_modulo'
            )
            ->where('congreso_modulo.id_congreso', $idCongreso)
            ->orderBy('congreso_modulo_disertante.apellido_nombre')
            ->get();

        return view('App.Congreso.disertantes', [
            'disertantes' => $disertantes,
            'idCongreso' => $idCongreso,
        ]);
    }

    public function ModuloDisertanteCVPublic(Request $request)
    {
        $id = $request->get('id');

        if (!$id) {
            abort(404);
        }

        $disertante = CongresoModuloDisertante::leftJoin(
            'congreso_modulo',
            'congreso_modulo.id',
            '=',
            'congreso_modulo_disertante.id_congreso_modulo'
        )
            ->select(
                'congreso_modulo_disertante.*',
                'congreso_modulo.id_congreso'
            )
            ->where('congreso_modulo_disertante.id', $id)
            ->firstOrFail();

        return view('App.Congreso.disertante-cv', [
            'disertante' => $disertante,
            'idCongreso' => $disertante->id_congreso,
        ]);
    }

    public function moduloLocationPost(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            //Log::debug(json_encode($request->all()));

            if ($request->idModuloLocation) {
                $congresoMudloLocation = CongresoModuloLocation::find($request->idModuloLocation);
            } else {
                $congresoMudloLocation = new CongresoModuloLocation();
                $congresoMudloLocation->id_congreso = $request->idCongreso;
            }

            $congresoMudloLocation->descripcion = $request->descripcion;
            $congresoMudloLocation->save();

            $messageBag->add('success', 'Location guardada con éxito');

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function viewExcel(Request $request)
    {
        $congreso = Congreso::query()
            ->leftJoin('congreso_modulo', function ($query) {
                $query->on('congreso_modulo.id_congreso', '=', 'congreso.id');
                $query->where('congreso_modulo.deleted_at', '=', null);
                //$query->where('congreso_modulo.nombre', '!=', 'BREAK');
            })
            ->leftJoin('congreso_modulo_disertante', 'congreso_modulo_disertante.id_congreso_modulo', '=', 'congreso_modulo.id')
            ->leftJoin('congreso_modulo_location', 'congreso_modulo_location.id', '=', 'congreso_modulo.id_location')
            ->select(

                'congreso_modulo.id as modulo_id',
                'congreso_modulo.nombre as modulo_nombre',
                'congreso_modulo.nombre_color as modulo_nombre_color',
                'congreso_modulo.descripcion as modulo_descripcion',
                'congreso_modulo.descripcion_color as modulo_descripcion_color',
                'congreso_modulo.auspiciante as modulo_auspiciante',
                'congreso_modulo.auspiciante_color as modulo_auspiciante_color',
                'congreso_modulo.background as modulo_background',
                'congreso_modulo.desde as modulo_desde',
                'congreso_modulo.hasta as modulo_hasta',

                'congreso_modulo_location.descripcion',

                'congreso_modulo_disertante.id as disertante_id',
                'congreso_modulo_disertante.apellido_nombre as disertante_nombre',
                'congreso_modulo_disertante.empresa as disertante_empresa',
                'congreso_modulo_disertante.titulo as disertante_titulo',

            )
            ->where('congreso.id', $request->idCongreso)
            ->get();

        return view('App.Congreso.excel', [
            'congreso' => $congreso
        ]);
    }

    public function deleteCongreso(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            //$messageBag->add('success', '');

            $congreso = Congreso::find($request->idCongreso);
            $congreso->delete();

            $messageBag->add('success', 'Congreso eliminado con exito');

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function moduloLocationPostDelete(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $locacionId = $request->idModuloLocation;

            $modulos = CongresoModulo::where('id_location', $locacionId)
                ->get();

            if ($modulos->count() > 0) {

                $nombres = $modulos
                    ->pluck('nombre')
                    ->implode(', ');

                $messageBag->add('danger', 'Editar los módulos que tengan esta locación primero: ' . $nombres);

                return back();
            }

            CongresoModuloLocation::where('id', $locacionId)->delete();

            return back()->with(
                'success',
                'Locación eliminada correctamente.'
            );
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function moduloDelete(Request $request)
    {
        try {

            // Create a new MessageBag instance flashed to the session.
            $messageBag = session()->get('messageBag') ?? new MessageBag;
            request()->session()->flash('messageBag', $messageBag);

            $modulo = CongresoModulo::find($request->idCongresoModulo);
            $modulo->delete();

            $messageBag->add('success', 'Modulo eliminado correctamente');

            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function diplomaRaw(Request $request)
    {
        $idCongreso = $request->idCongreso;

        $configuracion = DB::table('congreso_diplomas_raw')
            ->where('id_congreso', $idCongreso)
            ->first();

        $diploma = null;

        if ($configuracion) {
            $path = storage_path('app/congresos/diplomas/' . $idCongreso . '/' . $configuracion->diploma_raw);

            if (file_exists($path)) {
                $diploma = [
                    'archivo' => $configuracion->diploma_raw,
                    'url' => route('congreso.diploma-raw-file', [
                        'idCongreso' => $idCongreso,
                    ]),
                    'posicion_x' => $configuracion->diploma_posision_x,
                    'posicion_y' => $configuracion->diploma_posicion_y,
                ];
            }
        }

        return view('App.Congreso.diplomaRaw', [
            'idCongreso' => $idCongreso,
            'diploma' => $diploma,
        ]);
    }

    public function diplomaRawFile($idCongreso)
    {
        $configuracion = DB::table('congreso_diplomas_raw')
            ->where('id_congreso', $idCongreso)
            ->first();

        if (!$configuracion) {
            abort(404);
        }

        $path = storage_path('app/congresos/diplomas/' . $idCongreso . '/' . $configuracion->diploma_raw);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }


    public function diplomaRawPost(Request $request)
    {
        $request->validate([
            'idCongreso' => 'required|integer',
            'diploma_raw' => 'nullable|file|mimes:png,jpg,jpeg',
            'diploma_posision_x' => 'required|numeric',
            'diploma_posicion_y' => 'required|numeric',
            'diploma_font_size' => 'required|numeric|min:1|max:500',
        ]);

        $idCongreso = $request->idCongreso;

        $configuracion = DB::table('congreso_diplomas_raw')
            ->where('id_congreso', $idCongreso)
            ->first();

        $directory = storage_path('app/congresos/diplomas/' . $idCongreso);

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filename = $configuracion ? $configuracion->diploma_raw : null;

        if ($request->hasFile('diploma_raw')) {
            $extension = strtolower($request->file('diploma_raw')->getClientOriginalExtension());
            $filename = 'diploma_raw.' . $extension;
            $request->file('diploma_raw')->move($directory, $filename);
        }

        if (!$filename) {
            return back()
                ->withErrors([
                    'diploma_raw' => 'Debe seleccionar una imagen para el diploma.'
                ])
                ->withInput();
        }

        $data = [
            'diploma_raw' => $filename,
            'diploma_posision_x' => $request->diploma_posision_x,
            'diploma_posicion_y' => $request->diploma_posicion_y,
            'diploma_font_size' => $request->diploma_font_size,
            'updated_at' => now(),
        ];

        if ($configuracion) {
            DB::table('congreso_diplomas_raw')
                ->where('id_congreso', $idCongreso)
                ->update($data);
        } else {
            $data['id_congreso'] = $idCongreso;
            $data['created_at'] = now();

            DB::table('congreso_diplomas_raw')->insert($data);
        }

        return redirect()
            ->route('congreso.generar-diplomas-view', [
                'idCongreso' => $idCongreso
            ])
            ->with('success', 'Configuración del diploma guardada correctamente.');
    }


    public function generarDiplomasView(Request $request)
    {
        $messageBag = session()->get('messageBag') ?? new MessageBag;

        if (!$request->filled('idCongreso') || !is_numeric($request->idCongreso)) {
            $messageBag->add('danger', 'No se recibió un ID de congreso válido.');
            request()->session()->flash('messageBag', $messageBag);
            return back();
        }

        $idCongreso = $request->idCongreso;
        $directory = storage_path('app/congresos/diplomas/' . $idCongreso);
        $diplomas = [];

        if (File::exists($directory)) {
            foreach (File::files($directory) as $file) {
                if (!in_array(strtolower($file->getExtension()), ['png', 'jpg', 'jpeg'])) {
                    continue;
                }

                if ($file->getFilename() === 'diploma_raw.png' || $file->getFilename() === 'diploma_raw.jpg') {
                    continue;
                }

                $filename = $file->getFilename();

                $nombre = pathinfo($filename, PATHINFO_FILENAME);
                $nombre = preg_replace('/^\d+_/', '', $nombre);
                $nombre = str_replace('-', ' ', $nombre);
                $nombre = ucwords($nombre);

                $diplomas[] = [
                    'nombre' => $nombre,
                    'filename' => $filename,
                    'url' => route('congreso.ver-diploma', [
                        'idCongreso' => $idCongreso,
                        'filename' => $filename,
                    ]),
                    'urlDownload' => route('congreso.descargar-diploma', [
                        'idCongreso' => $idCongreso,
                        'filename' => $filename,
                    ]),
                ];
            }
        }

        usort($diplomas, function ($a, $b) {
            return strcmp($a['filename'], $b['filename']);
        });

        return view('App.Congreso.diplomasView', [
            'diplomas' => $diplomas,
            'idCongreso' => $idCongreso,
        ]);
    }


    public function generarDiplomas(Request $request)
    {
        try {
            $messageBag = session()->get('messageBag') ?? new MessageBag;

            if (!$request->filled('idCongreso') || !is_numeric($request->idCongreso)) {
                $messageBag->add('danger', 'No se recibió un ID de congreso válido.');
                request()->session()->flash('messageBag', $messageBag);
                return back();
            }

            if (!$request->filled('nombre') && !$request->hasFile('csv')) {
                $messageBag->add('danger', 'Debe ingresar un nombre o seleccionar un archivo CSV.');
                request()->session()->flash('messageBag', $messageBag);
                return back();
            }

            $idCongreso = $request->idCongreso;

            $configuracion = DB::table('congreso_diplomas_raw')
                ->where('id_congreso', $idCongreso)
                ->first();

            if (!$configuracion) {
                $messageBag->add('danger', 'No existe una configuración de diploma para este congreso.');
                request()->session()->flash('messageBag', $messageBag);
                return back();
            }

            $template = storage_path('app/congresos/diplomas/' . $idCongreso . '/' . $configuracion->diploma_raw);
            $outputDir = storage_path('app/congresos/diplomas/' . $idCongreso);
            $font = storage_path('app/fonts/GreatVibes-Regular.ttf');

            if (!file_exists($template)) {
                $messageBag->add('danger', 'No se encontró la plantilla del diploma.');
                request()->session()->flash('messageBag', $messageBag);
                return back();
            }

            if (!file_exists($font)) {
                $messageBag->add('danger', 'No se encontró la fuente en storage/app/fonts/GreatVibes-Regular.ttf.');
                request()->session()->flash('messageBag', $messageBag);
                return back();
            }

            if (!File::exists($outputDir)) {
                File::makeDirectory($outputDir, 0755, true);
            }

            $nombres = [];

            if ($request->filled('nombre')) {
                $nombres[] = trim($request->nombre);
            }

            if ($request->hasFile('csv')) {
                $csvPath = $request->file('csv')->getRealPath();
                if (($handle = fopen($csvPath, 'r')) !== false) {
                    while (($data = fgetcsv($handle, 0, ',')) !== false) {
                        if (isset($data[0], $data[1]) && trim($data[0]) !== '' && trim($data[1]) !== '') {
                            $nombres[] = mb_convert_case(trim($data[1]), MB_CASE_TITLE, 'UTF-8') . ' ' . mb_convert_case(trim($data[0]), MB_CASE_TITLE, 'UTF-8');
                        }
                    }
                    fclose($handle);
                }
            }

            if (empty($nombres)) {
                $messageBag->add('danger', 'No se encontraron nombres para generar los diplomas.');
                request()->session()->flash('messageBag', $messageBag);
                return back();
            }

            $imageInfo = getimagesize($template);

            if (!$imageInfo) {
                $messageBag->add('danger', 'La imagen del diploma no es válida.');
                request()->session()->flash('messageBag', $messageBag);
                return back();
            }

            switch ($imageInfo['mime']) {
                case 'image/jpeg':
                    $original = imagecreatefromjpeg($template);
                    break;

                case 'image/png':
                    $original = imagecreatefrompng($template);
                    imagealphablending($original, true);
                    imagesavealpha($original, true);
                    break;

                default:
                    $messageBag->add('danger', 'El formato de la plantilla no es compatible.');
                    request()->session()->flash('messageBag', $messageBag);
                    return back();
            }

            $posicionX = (float) $configuracion->diploma_posision_x;
            $posicionY = (float) $configuracion->diploma_posicion_y;
            $fontSize = max(1, (int) ($configuracion->diploma_font_size ?? 60));

            foreach ($nombres as $nombre) {
                $diploma = imagecreatetruecolor(
                    imagesx($original),
                    imagesy($original)
                );

                imagealphablending($diploma, false);
                imagesavealpha($diploma, true);

                imagecopy(
                    $diploma,
                    $original,
                    0,
                    0,
                    0,
                    0,
                    imagesx($original),
                    imagesy($original)
                );

                imagealphablending($diploma, true);

                $color = imagecolorallocate($diploma, 0, 0, 0);

                /*
             * X/Y representan el centro exacto del nombre.
             * Se calcula el desplazamiento utilizando el bounding box
             * de la fuente para centrar el texto.
             */
                $bbox = imagettfbbox($fontSize, 0, $font, $nombre);

                $bboxCenterX = ($bbox[0] + $bbox[2]) / 2;
                $bboxCenterY = ($bbox[1] + $bbox[7]) / 2;

                $textX = $posicionX - $bboxCenterX;
                $textY = $posicionY - $bboxCenterY;

                imagettftext(
                    $diploma,
                    $fontSize,
                    0,
                    (int) round($textX),
                    (int) round($textY),
                    $color,
                    $font,
                    $nombre
                );

                $safeName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $nombre);
                $safeName = trim($safeName, '_');

                if ($safeName === '') {
                    $safeName = 'diploma';
                }

                $filename = 'diploma_' . $safeName . '_' . uniqid() . '.png';
                $outputPath = $outputDir . '/' . $filename;

                imagepng($diploma, $outputPath);

                imagedestroy($diploma);
            }

            imagedestroy($original);

            $messageBag->add(
                'success',
                'Se generaron correctamente ' . count($nombres) . ' diploma(s).'
            );

            request()->session()->flash('messageBag', $messageBag);

            return redirect()->route('congreso.generar-diplomas-view', [
                'idCongreso' => $idCongreso
            ]);
        } catch (\Throwable $e) {
            $messageBag = session()->get('messageBag') ?? new MessageBag;

            $messageBag->add(
                'danger',
                'Error al generar los diplomas: ' . $e->getMessage()
            );

            request()->session()->flash('messageBag', $messageBag);

            return back();
        }
    }


    public function verDiploma(Request $request)
    {
        $path = storage_path('app/congresos/diplomas/' . $request->idCongreso . '/' . basename($request->filename));

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    public function descargarDiploma(Request $request)
    {
        $path = storage_path('app/congresos/diplomas/' . $request->idCongreso . '/' . basename($request->filename));

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download($path);
    }

    public function borrarDiploma(Request $request)
    {
        $path = storage_path('app/congresos/diplomas/' . $request->idCongreso . '/' . basename($request->filename));

        if (!File::exists($path)) {
            return redirect()->route('congreso.generar-diplomas-view', [
                'idCongreso' => $request->idCongreso,
            ])->with('error', 'El diploma no existe.');
        }

        File::delete($path);

        return redirect()->route('congreso.generar-diplomas-view', ['idCongreso' => $request->idCongreso,])->with('success', 'Diploma eliminado correctamente.');
    }
    public function downloadDiplomas(Request $request)
    {
        $folder = storage_path('app/congresos/diplomas/' . $request->idCongreso);

        if (!is_dir($folder)) {
            return response()->json(['message' => 'No existe la carpeta de diplomas'], 404);
        }

        $zipPath = storage_path('app/diplomas_' . $request->idCongreso . '.zip');
        $zip = new \ZipArchive();

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return response()->json(['message' => 'No se pudo crear el ZIP'], 500);
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($folder, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($files as $file) {
            if ($file->isFile()) {
                $zip->addFile($file->getRealPath(), substr($file->getPathname(), strlen($folder) + 1));
            }
        }

        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
