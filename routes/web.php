<?php

use App\Http\Controllers\CreateUsersBatch;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Http\Livewire\Students;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ModuleSessionsController;
use App\Notifications\SendCredentials;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;



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

Route::get('/', function () {
    if (Auth::check()) {
        // The user is logged in...
        return view('/dashboard');
    }
    return view('welcome');
});

Route::middleware(['auth:sanctum'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum'])->get('/dashboard/meetings', function () {
    return view('meetings');
})->name('dashboard-meetings');

// Route::get('/register-student', function () {
//     return view('auth.register-student');
// })->name('register-student');

Route::middleware(['auth:sanctum', 'verified', 'can:edit_users'])->get('/dashboard/users', function () {
    return view('users');
})->name('dashboard-users');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard/block/{id}', function ($id) {
    $user = User::find($id);
    $user->status = 0;
    $user->save();
    return view('users');
})->name('dashboard-block');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard/active/{id}', function ($id) {
    $user = User::find($id);
    $user->status = 1;
    $user->save();
    return view('users');
})->name('dashboard-active');

Route::view('contacts', 'users.contacts');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard/users/{id}', function ($id) {
    return view('users.show');
})->name('user.show');

Route::middleware(['auth:sanctum'])->get('/dashboard/recursos-estudiante', function () {
    return view('users.recursos');
})->name('recursos');

Route::middleware(['auth:sanctum'])->get('/dashboard/material-digital', function () {
    return view('users.material-digital');
})->name('material_digital');

Route::get('send-mail', function () {
    $details = [
        'title' => 'Mail from ItSolutionStuff.com',
        'body' => 'This is for testing email using smtp'
    ];
    Mail::to('your_receiver_email@gmail.com')->send(new \App\Mail\RegistrationMail($details));
    dd("Email is Sent.");
});

// rutas solo accesibles si esta autenticado
Route::middleware(['auth:sanctum'])->group(function () {
// Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::get('student', function () {
        return view('pages.students');
    })->middleware('can:crud_drives')->name('dashboard-students');

    //modulo recursos
    Route::get('course-resources', function () {
        return view('pages.students');
    })->middleware('can:crud_drives')->name('course-resources');

    // usuarios crud para admin
    Route::get('/admin/users', function () {
        return view('pages.students');
    })->name('users-crud');

    // rutas para user-rol
    Route::get('users-roles', [\App\Http\Controllers\AsignacionController::class, 'index'])->name('asignacion.index');
    Route::get('users-roles/create', [\App\Http\Controllers\AsignacionController::class, 'create']);
    Route::post('users-roles', [\App\Http\Controllers\AsignacionController::class, 'store']);

    Route::get('upload-students-excel', [\App\Http\Controllers\ExcelUploadController::class, 'fileUpload'])->name('excel.upload');
    Route::post('upload-students-excel', [\App\Http\Controllers\ExcelUploadController::class, 'fileUploadPost'])->name('excel.upload.post');
    
    //excel asistencias
    Route::get('admin/asistencias-excel', [\App\Http\Controllers\AsistenciasController::class, 'exportar'])->name('asistencias.export');

    Route::get('/companies', function () {
        return view('pages.companies');
    })->name('companies');
    
    //Oferta academica
    Route::get('/oferta-academica', function () {
        return view('pages.oferta-academica');
    })->name('oferta-academica');
    Route::get('/importar-oferta', [App\Http\Controllers\Oferta\OfertaUploadController::class, 'import'])->name('import-oferta');

    Route::get('/course_programs', function () {
        return view('pages.course_programs');
    })->name('course_programm');

    Route::get('/admin/drives', function () {
        return view('pages.drives');
    })->name('drives');

    Route::get('/admin/asistencias', function () {
        return view('pages.asistencias');
    })->name('asistencias-crud');

    Route::get('/admin/sesiones', function () {
        return view('pages.sesiones');
    })->name('sesiones-crud');

    Route::get('/admin/zoom-configuration', function () {
        return view('pages.zoom-configuration');
    })->name('zoom-configuration');

    //email pruebas
    Route::get('/test-email1', function () {
        $user = User::find(1976);
        $details = [
            'title' => 'Creación de cuenta Eus3',
            'body' => 'Saludos desde ',
            'user' => $user,
        ];
        Mail::to('dylan.jaramillo@gfeval.com')->send(new \App\Mail\RegistrationMail($details));
    });

    Route::get('/test-email2', function () {
        $user = User::find(1976);

        $user->notify(new SendCredentials($user));
    });

    // ruta para manejor de zoom sdk
    // Route::get('/meet', [\App\Http\Controllers\ZoomMeetingController::class, 'main'])->name('app.meet');

    // registrar usuario en zoom meeting
    Route::post('/meetings/create/registrants', [\App\Http\Controllers\Zoom\MeetingController::class, 'add_registrant'])->name('store_registrant');
    Route::get('/meetings/create/registrants', [\App\Http\Controllers\Zoom\MeetingController::class, 'create_registrant'])->name('create_registrant');
    Route::get('/meetings/registro', [\App\Http\Controllers\Zoom\MeetingController::class, 'registrar_estudiantes_zoom'])->name('registrant');

    //set id_zoom student
    Route::get('/meetings/set-ids', [\App\Http\Controllers\Zoom\MeetingController::class, 'set_zoom_ids'])->name('set_zoom_ids');

    // Registro participantes
    Route::post('/meetings/register/participants', [\App\Http\Controllers\Zoom\MeetingController::class, 'add_participantes'])
        ->name('post.register_participants');

    Route::get('/meetings/register/participants', [\App\Http\Controllers\Zoom\MeetingController::class, 'registro_participantes'])
        ->name('get.register_participants');

    //Cambiar aprobacion
    Route::get('/meetings/set-approval/{type}/{meeting_id}', [\App\Http\Controllers\Zoom\MeetingController::class, 'change_approval_type'])->name('approval_type');


    // subir excel y actualizar base de datos
    Route::get('actualizar-base', [\App\Http\Controllers\UpdaterController\UpdaterController::class, 'excelUpload'])->name('base.upload');
    Route::post('actualizar-base', [\App\Http\Controllers\UpdaterController\UpdaterController::class, 'excelUploadPost'])->name('base.upload.post');

    // subir excel y actualizar base de datos 2
    Route::get('actualizar-cuentas', [\App\Http\Controllers\CuentasController\UpdaterController::class, 'excelUpload'])->name('actualizar_base.get');
    Route::post('actualizar-cuentas', [\App\Http\Controllers\CuentasController\UpdaterController::class, 'excelUploadPost'])->name('actualizar_base.post');

    //Borrar usuarios
    Route::get('eliminar-studiantes-excel', [\App\Http\Controllers\UpdaterController\UpdaterController::class, 'borrarCuentas'])->name('base.borrar');

    // eliminar usuario
    Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy']);

    // rutas para roles
    Route::get('roles', [\App\Http\Controllers\RoleController::class, 'index'])->name('roles.index');
    Route::get('roles/create', [\App\Http\Controllers\RoleController::class, 'create']);
    Route::post('roles', [\App\Http\Controllers\RoleController::class, 'store']);
    Route::get('/roles/{rol}/edit', [\App\Http\Controllers\RoleController::class, 'edit']);
    Route::put('/roles/{rol}', [\App\Http\Controllers\RoleController::class, 'update']);
    Route::get('/roles/{rol}', [\App\Http\Controllers\RoleController::class, 'destroy']);
    Route::get('/roles/{idrol}/show', [\App\Http\Controllers\RoleController::class, 'show']);

    // rutas para abilities
    Route::get('abilities', [\App\Http\Controllers\AbilitiesController::class, 'index'])->name('abilities.index');
    Route::get('abilities/create', [\App\Http\Controllers\AbilitiesController::class, 'create']);
    Route::post('abilities', [\App\Http\Controllers\AbilitiesController::class, 'store']);
    Route::get('/abilities/{rol}/edit', [\App\Http\Controllers\AbilitiesController::class, 'edit']);
    Route::put('/abilities/{rol}', [\App\Http\Controllers\AbilitiesController::class, 'update']);
    Route::get('/abilities/{rol}', [\App\Http\Controllers\AbilitiesController::class, 'destroy']);
    Route::get('/abilities/{idability}/show', [\App\Http\Controllers\AbilitiesController::class, 'show']);

    // rutas para rol-ability
    Route::get('roles-permisos', [\App\Http\Controllers\PermisosController::class, 'index'])->name('permisos.index');
    Route::get('roles-permisos/create', [\App\Http\Controllers\PermisosController::class, 'create']);
    Route::post('roles-permisos', [\App\Http\Controllers\PermisosController::class, 'store']);

    //clases grabadas
    // Route::get('clases-grabadas', [\App\Http\Controllers\CGrabadas\ClasesGrabController::class, 'show'])->name('clases.index');
    Route::get('/clases-grabadas', App\Http\Livewire\CGrabadas\ClasesGrabadas::class)->name('clases');

    //calculadora
    Route::get('/calculadora', App\Http\Livewire\Calculadora\CalculadoraController::class)->name('calculadora');

    // Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    //     \UniSharp\LaravelFilemanager\Lfm::routes();
    // });

    // Carreras disponibles
    Route::get('/carreras', App\Http\Livewire\Carreras\CarrerasController::class)->name('carreras');

    //Panel Informativo
    Route::get('/informacion', function () {
        return view('pages.informacion');
    })->name('informacion');
    // Route::get('/informacion', App\Http\Livewire\Informacion\InformacionController::class)->name('informacion');

    // importación base carreras
    Route::get('/importar-carreras', [App\Http\Controllers\Careers\CareerUploadController::class, 'import'])->name('import-careers');

    // buscador de carreras
    // Route::get('careers-datatables', App\Http\Livewire\Careers\CareersDatatables::class)->name('careers-datatable');

    Route::get('careers-datatables', function () {
        return view('livewire.careers.careers-datatables');
    });

    Route::post('encuesta', [\App\Http\Controllers\EncuestaController::class, 'store'])->name('encuesta');

    Route::get('asistencias', function () {
        return view('livewire.attendances.attendances');
    });

    //status 0
    Route::get('status', [\App\Http\Controllers\StatusController::class, 'store'])->name('status');

    //Encuestas xcel
    Route::get('/encuestas', [\App\Http\Controllers\EncuestaController::class, 'downloadEncuestas']);

    Route::get('/createUsersBatch', CreateUsersBatch::class);

    //Enviar correos usuarios
    Route::get('/enviar-correos-cuentas', [\App\Http\Controllers\UserController::class, 'enviarCorreoCuentas']);

    //pdf
    Route::get('pdfview', [\App\Http\Controllers\Pdf\CertificadoController::class, 'pdfForm'])->name('pdfview');

    Route::get('/createCourseSessions', \App\Http\Controllers\CreateSchedules::class);

    // Route::get('/admin/dashboard', function () {
    //     return view('pages.dashboard');
    // })->name('admin.dashboard');

    Route::get('/admin/dashboard', function () {
        return view('layouts.adminsa');
    })->name('layouts');

    //ajax
    Route::get('ajax/students',[\App\Http\Controllers\UserController::class, 'getStudentsAjax'])->name('ajax.students');
    Route::get('ajax/clases',[\App\Http\Controllers\UserController::class, 'getClasesAjax'])->name('ajax.clases');
    Route::post('ajax/setUserExamMonth',[\App\Http\Controllers\UserController::class, 'setUserExamMonth'])->name('ajax.userExamMonth');

    // zoom
    Route::get('ajax/zoom-signature',[\App\Http\Controllers\Zoom\MeetingController::class, 'getZoomSignature'])->name('ajax.zoom-signature');
    Route::get('ajax/zmtg-join-data',[\App\Http\Controllers\Zoom\MeetingController::class, 'getZoomMtgData'])->name('ajax.zoom-mtg-data');

});


//Asistencias
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard/asistencias', function () {
    return view('asistencias');
})->name('dashboard-asistencias');
