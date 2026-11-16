<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\CongresoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ExposicionController;
use App\Http\Controllers\MapaController;
use App\Http\Controllers\ModuloPlanoStandController;
use App\Http\Controllers\ModuloInvitacionController;
use App\Http\Controllers\ModuloElectricidadController;
use App\Http\Controllers\ModuloCatalogoController;
use App\Http\Controllers\ModuloSalaConferenciaController;
use App\Http\Controllers\ModuloServiciosOptativosController;
use App\Http\Controllers\HelperController;
use App\Http\Controllers\ExpositorController;
use App\Http\Controllers\ExpositorAdministracionController;
use App\Http\Controllers\ExpositorModuloCatalogoController;
use App\Http\Controllers\ExpositorModuloInvitacionesController;
use App\Http\Controllers\ExpositorModuloElectricidadController;
use App\Http\Controllers\ExpositorModuloPlanoStandController;
use App\Http\Controllers\ExpositorModuloServiciosOptativosController;
use App\Http\Controllers\ExpositorModuloSalaConferenciaController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\CredencialController;
use App\Http\Controllers\ModuloCredencialController;
use App\Http\Controllers\ExpositorModuloCredencialController;
use App\Http\Controllers\ExpositorModuloDepositoGarantiaController;
use App\Http\Controllers\NovedadesController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\WhatsappController;
use App\Http\Controllers\ModuloNotificacionController;
use App\Http\Controllers\StandTipoController;

use App\Http\Controllers\ModuloPrensaController;
use App\Http\Controllers\ModuloElementosOpcionalesController;
use App\Http\Controllers\ExpositorModuloElementosOpcionalesController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\ModuloDepositoGarantiaController;

//Auth::routes();

Route::get('/prueba-csv', function () {
    $headers = [
        'Content-Type' => 'text/plain; charset=UTF-8',
        'Content-Disposition' => 'attachment; filename="prueba.csv"',
    ];

    return response()->stream(function () {
        $out = fopen('php://output', 'w');
        fwrite($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($out, ['Columna1', 'Columna2'], ';', '"');
        fputcsv($out, ['Dato1', 'Dato2'], ';', '"');
        fclose($out);
    }, 200, $headers);
});


Route::get('/restore-logos', [HelperController::class, 'restoreLogos']);

// Rutas de autenticación
Route::post('/reset-password-confirm', [ExpositorController::class, 'resetPasswordConfirm'])->name('reset-password-confirm');
Route::get('/reset-password/{token}', [ExpositorController::class, 'resetPasswordToken'])->name('reset-password-token');
Route::post('/reset-password', [ExpositorController::class, 'resetPassword'])->name('reset-password');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

//Route::get('/', function() {return redirect()->route('public.catalogo');});
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/home', [DashboardController::class, 'panel'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'panel']);

// Rutas publicas

Route::name('public.')->group(function () {
    Route::get('/catalogo', [PublicController::class, 'catalogoView'])->name('catalogo');
    Route::get('/catalogo-expositor', [PublicController::class, 'catalogoExpositorView'])->name('catalogo-expositor');
    Route::get('/mapa', [PublicController::class, 'mapaFantasma'])->name('mapa-fantasma');
    Route::get('/prensa', [ModuloPrensaController::class, 'iframe'])->name('prensa');
    Route::get('/acreditacion', [ModuloPrensaController::class, 'iframeAcreditacion'])->name('acreditacion');
    Route::post('/acreditacion-store', [ModuloPrensaController::class, 'acreditacionStore'])->name('acreditacion-store');
    Route::get('/sala-conferencia', [PublicController::class, 'salaConferencia'])->name('sala-conferencia');
    Route::get('/optativos', [ModuloElementosOpcionalesController::class, 'publicView'])->name('optativos');
});

//Route::get('/download-file', [ModuloDepositoGarantiaController::class, 'downloadFile'])->name('modulo.deposito-garantia.download-file');

// Rutas expositor
Route::middleware(['expositor'])->group(function () {

    Route::prefix('expositor')->name('expositor.')->group(function () {

        Route::get('/login', [ExpositorController::class, 'expositorLogin'])->name('login');
        Route::get('/list-exposiciones', [ExpositorController::class, 'listExposiciones'])->name('list-exposiciones');

        Route::get('/panel-expositor', [ExpositorController::class, 'panelExpositor'])->name('panel-expositor');
        Route::get('/cambiar-contraseña', [ExpositorController::class, 'changePasswordView'])->name('change-password-view');
        Route::post('/cambiar-contraseña', [ExpositorController::class, 'changePassword'])->name('change-password');

        Route::get('/notificaciones', [ExpositorController::class, 'notificacionesPanel'])->name('notificaciones');
        Route::get('/terminos-condiciones', [ExpositorController::class, 'terminosCondiciones'])->name('terminos-condiciones');

        Route::prefix('modulo')->name('modulo.')->group(function () {

            Route::prefix('deposito-garantia')->name('deposito-garantia.')->group(function () {
                Route::get('/index', [ExpositorModuloDepositoGarantiaController::class, 'index'])->name('index');
                Route::post('/update-store', [ExpositorModuloDepositoGarantiaController::class, 'updateStore'])->name('update-store');
            });

            Route::get('/documentos-importantes', [ExpositorController::class, 'documentosImportantesPanel'])->name('documentos-importantes');

            Route::prefix('material-difusion')->name('material-difusion.')->group(function () {
                Route::get('/', [ExpositorController::class, 'moduloMaterialDifusionPanel'])->name('panel');
            });

            Route::prefix('elementos-opcionales')->name('elementos-opcionales.')->group(function () {
                Route::get('/', [ExpositorModuloElementosOpcionalesController::class, 'index'])->name('index');
                Route::post('/update-store', [ExpositorModuloElementosOpcionalesController::class, 'updateStore'])->name('update-store');
                Route::get('/confirmar', [ExpositorModuloElementosOpcionalesController::class, 'confirmar'])->name('confirmar');
            });

            Route::prefix('catalogo')->name('catalogo.')->group(function () {
                Route::get('/panel', [ExpositorModuloCatalogoController::class, 'panel'])->name('panel');
                Route::post('/update', [ExpositorModuloCatalogoController::class, 'update'])->name('update');
            });

            Route::prefix('invitaciones')->name('invitaciones.')->group(function () {

                Route::get('/index', [ExpositorModuloInvitacionesController::class, 'index'])->name('index');
                Route::post('/update-store', [ExpositorModuloInvitacionesController::class, 'updateStore'])->name('update-store');
            });

            Route::prefix('electricidad')->name('electricidad.')->group(function () {

                Route::get('/index', [ExpositorModuloElectricidadController::class, 'index'])->name('index');
                Route::post('/update-store', [ExpositorModuloElectricidadController::class, 'updateStore'])->name('update-store');
            });

            Route::prefix('plano-stand')->name('plano-stand.')->group(function () {

                Route::get('/index', [ExpositorModuloPlanoStandController::class, 'index'])->name('index');
                Route::post('/update-store', [ExpositorModuloPlanoStandController::class, 'updateStore'])->name('update-store');
                Route::get('/download-file', [ExpositorModuloPlanoStandController::class, 'downloadFile'])->name('download-file');
                Route::get('/update-to-new-requirement', [ExpositorModuloPlanoStandController::class, 'updateToNewRequirement'])->name('update-to-new-requirement');
            });

            Route::prefix('servicios-optativos')->name('servicios-optativos.')->group(function () {

                Route::get('/index', [ExpositorModuloServiciosOptativosController::class, 'index'])->name('index');
                Route::get('/download-file', [ExpositorModuloServiciosOptativosController::class, 'downloadFile'])->name('download-file');
            });

            Route::prefix('sala-conferencia')->name('sala-conferencia.')->group(function () {

                Route::get('/index', [ExpositorModuloSalaConferenciaController::class, 'index'])->name('index');
                Route::post('/update-store', [ExpositorModuloSalaConferenciaController::class, 'updateStore'])->name('update-store');
                Route::get('/cancelar', [ExpositorModuloSalaConferenciaController::class, 'cancelar'])->name('cancelar');
            });

            Route::prefix('credencial')->name('credencial.')->group(function () {
                Route::get('/index', [ExpositorModuloCredencialController::class, 'index'])->name('index');
                Route::post('/update-store', [ExpositorModuloCredencialController::class, 'updateStore'])->name('update-store');
                Route::get('/delete', [ExpositorModuloCredencialController::class, 'delete'])->name('delete');
            });
        });
    });
});

Route::get('/whatsapp/send-predefinido', [WhatsappController::class, 'sendPredefinido'])->name('send-predefinido');
// Rutas Intranet
Route::middleware(['auth'])->group(function () { 

    Route::prefix('configuracion')->name('configuracion.')->group(function () {
        Route::get('/index', [ConfiguracionController::class, 'index'])->name('index');
        Route::post('/update', [ConfiguracionController::class, 'update'])->name('update');
    });

    Route::prefix('stand-tipo')->group(function () {

        Route::get('/', [StandTipoController::class, 'index'])->name('stand-tipo.index');

        Route::get('/create', [StandTipoController::class, 'create'])->name('stand-tipo.create');

        Route::get('/edit/{id}', [StandTipoController::class, 'edit'])->name('stand-tipo.edit');

        Route::post('/update-store', [StandTipoController::class, 'updateStore'])->name('stand-tipo.update-store');

    });

    Route::prefix('novedades')->name('novedades.')->group(function () {
        Route::get('/panel-empresa', [NovedadesController::class, 'panelEmpresa'])->name('panel-empresa');
        Route::get('/resolve-expositor', [NovedadesController::class, 'resolveExpositor'])->name('resolve-expositor');
        Route::get('/resolve-empresa', [NovedadesController::class, 'resolveEmpresa'])->name('resolve-empresa');
    });

    Route::post('/variable/update-store', [HelperController::class, 'variableUpdateStore'])->name('variable.update-store');

    // ADMINISTRACION
    Route::prefix('email')->name('email.')->group(function () {
        Route::get('/predefinido', [EmailController::class, 'updateStorePredefinidoView'])->name('update-store-predefinido-view');
        Route::post('/predefinido', [EmailController::class, 'updateStorePredefinido'])->name('update-store-predefinido');
    });

    Route::prefix('whatsapp')->name('whatsapp.')->group(function () {
        Route::get('/predefinido', [WhatsappController::class, 'updateStorePredefinidoView'])->name('update-store-predefinido-view');
        Route::post('/predefinido', [WhatsappController::class, 'updateStorePredefinido'])->name('update-store-predefinido');
        //Route::post('/send-predefinido', [WhatsappController::class, 'sendPredefinido'])->name('send-predefinido');
        //Route::post('/whatsapp/send-message', [WhatsappController::class, 'sendMessage'])->name('send-message');
    });

    Route::prefix('empresa')->name('empresa.')->group(function () {
        Route::get('/panel', [EmpresaController::class, 'panel'])->name('panel');
        Route::get('/create/{id}', [EmpresaController::class, 'create'])->name('create');
        Route::post('/update-store', [EmpresaController::class, 'updateStore'])->name('update-store');
        Route::post('/delete-many', [EmpresaController::class, 'deleteMany'])->name('delete-many');
        Route::get('/delete', [EmpresaController::class, 'delete'])->name('delete');
    });

    Route::prefix('exposicion')->name('exposicion.')->group(function () {
        Route::get('/switch-visible', [ExposicionController::class, 'switchVisible'])->name('switch-visible');
        Route::get('/panel', [ExposicionController::class, 'panel'])->name('panel');
        Route::get('/create', [ExposicionController::class, 'create'])->name('create');
        Route::post('/store', [ExposicionController::class, 'store'])->name('store');
        Route::get('/create-2', [ExposicionController::class, 'create2'])->name('create-2');
        Route::get('/create-3', [ExposicionController::class, 'create3'])->name('create-3');

        Route::get('/update-web', [ExposicionController::class, 'updateWeb'])->name('update-web');
        Route::get('/switch-archivar', [ExposicionController::class, 'switchArchivar'])->name('switch-archivar');

        Route::get('/delete-documento-importante', [ExposicionController::class, 'deleteDocumentoImportante'])->name('delete-documento-importante');
    });

    Route::prefix('expositor')->name('expositor.')->group(function () {

        Route::post('/exportar', [ExpositorAdministracionController::class, 'exportar'])->name('exportar');

        Route::get('/panel-administracion', [ExpositorAdministracionController::class, 'panel'])->name('panel-administracion');
        Route::get('/create', [ExpositorAdministracionController::class, ''])->name('create');
        Route::post('/update-store', [ExpositorAdministracionController::class, 'updateStore'])->name('update-store');
        Route::get('/get-by-id', [ExpositorAdministracionController::class, 'getById'])->name('get-by-id');
        Route::post('/store-expositor-with-empresa', [ExpositorAdministracionController::class, 'storeExpositorWithEmpresa'])->name('store-expositor-with-empresa');
        Route::get('/update-with-empresa/{id}', [ExpositorAdministracionController::class, 'edit'])->name('update-with-empresa');
        Route::get('/delete', [ExpositorAdministracionController::class, 'delete'])->name('delete');

        Route::get('/copiar-expositor-panel', [ExpositorAdministracionController::class, 'copiarExpositorPanel'])->name('copiar-expositor-panel');
        Route::get('/copiar-expositor-panel-2', [ExpositorAdministracionController::class, 'copiarExpositorPanel2'])->name('copiar-expositor-panel-2');
        Route::get('/copiar-expositor-panel-3', [ExpositorAdministracionController::class, 'copiarExpositorPanel3'])->name('copiar-expositor-panel-3');
    });

    Route::prefix('modulo')->name('modulo.')->group(function () {

        Route::get('/panel-control', [ModuloController::class, 'panelControl'])->name('panel-control');

        Route::prefix('deposito-garantia')->name('deposito-garantia.')->group(function () {
            Route::get('/create', [ModuloDepositoGarantiaController::class, 'create'])->name('create');
            Route::post('/update-store', [ModuloDepositoGarantiaController::class, 'updateStore'])->name('update-store');
            Route::post('/switch-aprobado', [ModuloDepositoGarantiaController::class, 'switchAprobado'])->name('switch-aprobado');
            Route::get('/panel-requerimientos', [ModuloDepositoGarantiaController::class, 'panelRequerimientos'])->name('panel-requerimientos');
            Route::post('/update-file', [ModuloDepositoGarantiaController::class, 'updateFile'])->name('update-file');
            Route::get('/download-file', [ModuloDepositoGarantiaController::class, 'downloadFile'])->name('download-file');
            Route::post('/update-entregado', [ModuloDepositoGarantiaController::class, 'updateEntregado'])->name('update-entregado');
            //Route::get('/panel-requerimientos', [ModuloDepositoGarantiaController::class, 'panelRequerimientos'])->name('panel-requerimientos');
        });

        Route::prefix('elementos-opcionales')->name('elementos-opcionales.')->group(function () {
            Route::get('/panel', [ModuloElementosOpcionalesController::class, 'panel'])->name('panel');
            Route::get('/create', [ModuloElementosOpcionalesController::class, 'create'])->name('create');
            Route::post('/update-store', [ModuloElementosOpcionalesController::class, 'updateStore'])->name('update-store');
            Route::get('/delete', [ModuloElementosOpcionalesController::class, 'delete'])->name('delete');
            Route::get('/panel-requerimientos', [ModuloElementosOpcionalesController::class, 'panelRequerimientos'])->name('panel-requerimientos');
            Route::post('/update-cierre', [ModuloElementosOpcionalesController::class, 'updateCierre'])->name('update-cierre');
        });

        Route::prefix('prensa')->name('prensa.')->group(function () {
            Route::get('/create', [ModuloPrensaController::class, 'create'])->name('create');
            Route::post('/update-store', [ModuloPrensaController::class, 'updateStore'])->name('update-store');
            Route::get('/listado', [ModuloPrensaController::class, 'listado'])->name('listado');
            Route::get('/delete-image', [ModuloPrensaController::class, 'deleteImage'])->name('delete-image');
            Route::get('/delete-archivo', [ModuloPrensaController::class, 'deleteArchivo'])->name('delete-archivo');
            Route::get('/activar', [ModuloPrensaController::class, 'activar'])->name('activar');
            Route::get('/desactivar', [ModuloPrensaController::class, 'desactivar'])->name('desactivar');
            Route::get('/acreditacion-panel', [ModuloPrensaController::class, 'acreditacionPanel'])->name('acreditacion-panel');
        });

        Route::prefix('notificacion')->name('notificacion.')->group(function () {
            Route::get('/panel', [ModuloNotificacionController::class, 'panel'])->name('panel');
            Route::get('/create', [ModuloNotificacionController::class, 'create'])->name('create');
            Route::post('/store', [ModuloNotificacionController::class, 'store'])->name('store');
            Route::get('/delete', [ModuloNotificacionController::class, 'delete'])->name('delete');
        });

        Route::prefix('sala-conferencia')->name('sala-conferencia.')->group(function () {
            Route::get('/panel', [ModuloSalaConferenciaController::class, 'panel'])->name('panel');
            Route::post('/update-store', [ModuloSalaConferenciaController::class, 'updateStore'])->name('update-store');
            Route::post('/update-store-texto', [ModuloSalaConferenciaController::class, 'updateStoreTexto'])->name('update-store-texto');
            Route::get('/delete', [ModuloSalaConferenciaController::class, 'delete'])->name('delete');
            Route::get('/panel-requerimientos', [ModuloSalaConferenciaController::class, 'panelRequerimientos'])->name('panel-requerimientos');
            Route::post('/aceptar-sala', [ModuloSalaConferenciaController::class, 'aceptarSala'])->name('aceptar-sala');
            Route::post('/cancelar-sala', [ModuloSalaConferenciaController::class, 'cancelarSala'])->name('cancelar-sala');
            Route::get('/exportar-excel/{id}', [ModuloSalaConferenciaController::class, 'exportarExcel'])->name('exportar-excel');
            Route::get('/switch-publicado', [ModuloSalaConferenciaController::class, 'switchPublicado'])->name('switch-publicado');
            Route::get('/publicar-todo', [ModuloSalaConferenciaController::class, 'publicarTodo'])->name('publicar-todo');
            Route::get('/publicar-web', [ModuloSalaConferenciaController::class, 'publicarWeb'])->name('publicar-web');
        });

        Route::prefix('plano-stand')->name('plano-stand.')->group(function () {
            Route::get('/create', [ModuloPlanoStandController::class, 'create'])->name('create');
            Route::post('/update-store', [ModuloPlanoStandController::class, 'updateStore'])->name('update-store');
            Route::get('/panel-requerimientos', [ModuloPlanoStandController::class, 'panelRequerimientos'])->name('panel-requerimientos');
        });

        Route::prefix('invitacion')->name('invitacion.')->group(function () {
            Route::get('/create', [ModuloInvitacionController::class, 'create'])->name('create');
            Route::post('/update-store', [ModuloInvitacionController::class, 'updateStore'])->name('update-store');
            Route::get('/panel-requerimientos', [ModuloInvitacionController::class, 'panelRequerimientos'])->name('panel-requerimientos');
        });

        Route::prefix('electricidad')->name('electricidad.')->group(function () {
            Route::get('/create', [ModuloElectricidadController::class, 'create'])->name('create');
            Route::post('/update-store', [ModuloElectricidadController::class, 'updateStore'])->name('update-store');
            Route::get('/panel-requerimientos', [ModuloElectricidadController::class, 'panelRequerimientos'])->name('panel-requerimientos');
            Route::post('/delete', [ModuloElectricidadController::class, 'delete'])->name('delete');
        });

        Route::prefix('catalogo')->name('catalogo.')->group(function () {
            Route::get('/create', [ModuloCatalogoController::class, 'create'])->name('create');
            Route::post('/update-store', [ModuloCatalogoController::class, 'updateStore'])->name('update-store');
            Route::get('/panel-requerimientos', [ModuloCatalogoController::class, 'panelRequerimientos'])->name('panel-requerimientos');
            Route::post('/switch-expositor', [ModuloCatalogoController::class, 'switchExpositor'])->name('switch-expositor');
            Route::post('/switch-etif', [ModuloCatalogoController::class, 'switchExpositorEtif'])->name('switch-expositor-etif');
            Route::get('/exportacion-excel', [ModuloCatalogoController::class, 'exportacionExcel'])->name('exportacion-excel');
            Route::get('/exportacion-word-detalle', [ModuloCatalogoController::class, 'exportacionWordDetalle'])->name('exportacion-word-detalle');
            Route::get('/exportacion-word-rubros', [ModuloCatalogoController::class, 'exportacionWordRubros'])->name('exportacion-word-rubros');
        });

        Route::prefix('proveedores-optativos')->name('servicios-optativos.')->group(function () {
            Route::get('/panel', [ModuloServiciosOptativosController::class, 'panel'])->name('panel');
            Route::get('/create', [ModuloServiciosOptativosController::class, 'create'])->name('create');
            Route::post('/update-store', [ModuloServiciosOptativosController::class, 'updateStore'])->name('update-store');
            Route::get('/delete-file', [ModuloServiciosOptativosController::class, 'deleteFile'])->name('delete-file');
        });

        Route::prefix('credencial')->name('credencial.')->group(function () {
            Route::get('/panel', [ModuloCredencialController::class, 'panel'])->name('panel');
            Route::post('/update-maximo', [ModuloCredencialController::class, 'updateMaximo'])->name('update-maximo');
            Route::post('/update', [ModuloCredencialController::class, 'update'])->name('update');
            Route::get('/panel-requerimientos', [ModuloCredencialController::class, 'panelRequerimientos'])->name('panel-requerimientos');
        });

        //Route::get('/get-invitacion', [ModuloController::class, 'invitaciones'])->name('invitaciones');
    });

    Route::prefix('mapa')->name('mapa.')->group(function () {

        Route::prefix('stand')->name('stand.')->group(function () {
            Route::post('/update-store', [MapaController::class, 'updateStoreStand'])->name('update-store');
            Route::get('/delete', [MapaController::class, 'delete'])->name('delete');
            Route::get('/get-stand', [MapaController::class, 'getStandById'])->name('get-stand-by-id');
        });
    });

    Route::prefix('credencial')->name('credencial.')->group(function () {
        Route::get('/panel', [CredencialController::class, 'panel'])->name('panel');
        Route::post('/update-store', [CredencialController::class, 'updateStore'])->name('update-store');
    });

    Route::prefix('categoria')->name('categoria.')->group(function () {

        Route::get('/panel', [CategoriaController::class, 'panel'])->name('panel');
        Route::post('/update-store', [CategoriaController::class, 'updateStore'])->name('update-store');
        Route::post('/delete', [CategoriaController::class, 'delete'])->name('delete');
        Route::get('/excel', [CategoriaController::class, 'excel'])->name('excel');
    });

    Route::prefix('api')->name('api.')->group(function () {

        Route::get('update-stand-value', [HelperController::class, 'updateStandValue'])->name('update-stand-value');

        Route::post('mapa/update/{idExposicion}', [MapaController::class, 'mapaUpdate'])->name('mapa.update');
        Route::get('mapa/get/{idExposicion}', [MapaController::class, 'mapaGet'])->name('mapa.get');
        Route::get('mapa/get-stands/{idExposicion}', [MapaController::class, 'mapaGetStands'])->name('mapa.get-stands');
        Route::get('empresa/search', [HelperController::class, 'searchEmpresa'])->name('empresa.search');

        Route::prefix('email')->name('email.')->group(function () {
            Route::get('send-user-pass', [EmailController::class, 'sendUserPass'])->name('send-user-pass');
        });

        Route::get('match-expositor-etif-empresa', [HelperController::class, 'matchExpositorEtifEmpresa'])->name('match-expositor-etif-empresa');

        Route::get('empresa/change-etif', [EmpresaController::class, 'changeEtif'])->name('empresa.change-etif');

        Route::post('exposicion/switch-etif', [ExposicionController::class, 'switchEtif'])->name('exposicion.switch-etif');
    });

    // CONGRESO
    Route::prefix('congreso')->name('congreso.')->group(function () {

        Route::get('panel', [CongresoController::class, 'panel'])->name('panel');
        Route::get('create-view', [CongresoController::class, 'createView'])->name('create-view');
        Route::post('create-post', [CongresoController::class, 'createPost'])->name('create-post');

        Route::get('modulo-create-view', [CongresoController::class, 'moduloCreateView'])->name('modulo-create-view');
        Route::post('modulo-create-post', [CongresoController::class, 'moduloCreatePost'])->name('modulo-create-post');
        Route::get('modulo-delete', [CongresoController::class, 'moduloDelete'])->name('modulo-delete');

        Route::get('modulo-disertante-create-view', [CongresoController::class, 'moduloDisertanteCreateView'])->name('modulo-disertante-create-view');
        Route::post('modulo-disertante-create-post', [CongresoController::class, 'moduloDisertanteCreatePost'])->name('modulo-disertante-create-post');

        Route::post('modulo-location-post', [CongresoController::class, 'moduloLocationPost'])->name('modulo-location-post');
        Route::post('modulo-location-post-delete', [CongresoController::class, 'moduloLocationPostDelete'])->name('modulo-location-post-delete');

        Route::get('delete-congreso', [CongresoController::class, 'deleteCongreso'])->name('delete-congreso');

        Route::get('diploma-raw', [CongresoController::class, 'diplomaRaw'])->name('diploma-raw');
        Route::post('diploma-raw-post', [CongresoController::class, 'diplomaRawPost'])->name('diploma-raw-post');
        Route::get('generar-diplomas-view', [CongresoController::class, 'generarDiplomasView'])->name('generar-diplomas-view');
        Route::post('generar-diplomas', [CongresoController::class, 'generarDiplomas'])->name('generar-diplomas');
        Route::get('ver-diploma/{filename}', [CongresoController::class, 'verDiploma'])->name('ver-diploma');
        Route::get('descargar-diploma/{filename}', [CongresoController::class, 'descargarDiploma'])->name('descargar-diploma');
        Route::delete('borrar-diploma/{filename}', [CongresoController::class, 'borrarDiploma'])->name('borrar-diploma');

        Route::get('congreso/diploma-font', function () {
            return response()->file(storage_path('app/fonts/GreatVibes-Regular.ttf'));
        })->name('diploma-font');
        
        Route::get('congreso/diploma-raw-file/{idCongreso}', [CongresoController::class, 'diplomaRawFile'])->name('diploma-raw-file');

    });
});

// PUBLIC
Route::get('congreso/view-excel', [CongresoController::class, 'viewExcel'])->name('congreso.view-excel');
Route::get('congreso/disertantes', [CongresoController::class, 'ModuloDisertantePublic'])->name('congreso.modulo-disretantes-public');
Route::get('congreso/disertante-cv', [CongresoController::class, 'ModuloDisertanteCVPublic'])->name('congrerso.modulo-disertante-cv-public');