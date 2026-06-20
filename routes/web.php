<?php

use App\Http\Controllers\CreateUsersBatch;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    $landingPath = public_path('landing/index.html');

    if (! file_exists($landingPath)) {
        abort(503, 'Landing no compilada. Ejecuta pnpm build.');
    }

    return response()->file($landingPath);
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/dashboard/meetings', 'meetings')->name('dashboard-meetings');
    Route::view('/dashboard/recursos-estudiante', 'users.recursos')->name('recursos');
    Route::view('/dashboard/material-digital', 'users.material-digital')->name('material_digital');

    Route::middleware(['verified', 'can:edit_users'])->group(function () {
        Route::view('/dashboard/users', 'users')->name('dashboard-users');

        Route::patch('/dashboard/active/{id}', function ($id) {
            $user = User::findOrFail($id);
            $user->status = 1;
            $user->save();
            return back();
        })->name('dashboard-active');
    });

    Route::middleware(['verified'])->group(function () {
        Route::get('/dashboard/users/{id}', function ($id) {
            return view('users.show');
        })->name('user.show');

        Route::view('/dashboard/asistencias', 'asistencias')->name('dashboard-asistencias');
    });

    Route::view('contacts', 'users.contacts');

    Route::middleware('can:crud_drives')->group(function () {
        Route::view('/admin/drives', 'pages.drives')->name('drives');
    });

    Route::middleware('can:edit_users')->group(function () {
        Route::view('student', 'pages.students')->name('dashboard-students');
        Route::view('course-resources', 'pages.students')->name('course-resources');
        Route::view('/admin/users', 'pages.users')->name('users-crud');
        Route::view('/companies', 'pages.companies')->name('companies');
        Route::view('/admin/asistencias', 'pages.asistencias')->name('asistencias-crud');
        Route::view('/admin/sesiones', 'pages.sesiones')->name('sesiones-crud');
        Route::view('/admin/live-class-provider', 'pages.live-class-provider')->name('live-class-provider');
        Route::redirect('/admin/zoom-configuration', '/admin/live-class-provider')->name('zoom-configuration');

        // User-rol assignment
        Route::get('users-roles', [\App\Http\Controllers\AsignacionController::class, 'index'])->name('asignacion.index');
        Route::get('users-roles/create', [\App\Http\Controllers\AsignacionController::class, 'create']);
        Route::post('users-roles', [\App\Http\Controllers\AsignacionController::class, 'store']);

        // Excel upload
        Route::get('upload-students-excel', [\App\Http\Controllers\ExcelUploadController::class, 'fileUpload'])->name('excel.upload');
        Route::post('upload-students-excel', [\App\Http\Controllers\ExcelUploadController::class, 'fileUploadPost'])->name('excel.upload.post');
        Route::get('admin/asistencias-excel', [\App\Http\Controllers\AsistenciasController::class, 'exportar'])->name('asistencias.export');

        // Zoom meetings
        Route::post('/meetings/create/registrants', [\App\Http\Controllers\Zoom\MeetingController::class, 'add_registrant'])->name('store_registrant');
        Route::view('/meetings/create/registrants', 'meetings.registrants-create')->name('create_registrant');
        Route::get('/meetings/registro', [\App\Http\Controllers\Zoom\MeetingController::class, 'registrar_estudiantes_zoom'])->name('registrant');
        Route::post('/meetings/set-ids', [\App\Http\Controllers\Zoom\MeetingController::class, 'set_zoom_ids'])->name('set_zoom_ids');
        Route::post('/meetings/register/participants', [\App\Http\Controllers\Zoom\MeetingController::class, 'add_participantes'])->name('post.register_participants');
        Route::get('/meetings/register/participants', [\App\Http\Controllers\Zoom\MeetingController::class, 'registro_participantes'])->name('get.register_participants');
        Route::patch('/meetings/set-approval/{type}/{meeting_id}', [\App\Http\Controllers\Zoom\MeetingController::class, 'change_approval_type'])->name('approval_type');

        // Base update
        Route::get('actualizar-base', [\App\Http\Controllers\UpdaterController\UpdaterController::class, 'excelUpload'])->name('base.upload');
        Route::post('actualizar-base', [\App\Http\Controllers\UpdaterController\UpdaterController::class, 'excelUploadPost'])->name('base.upload.post');
        Route::get('actualizar-cuentas', [\App\Http\Controllers\CuentasController\UpdaterController::class, 'excelUpload'])->name('actualizar_base.get');
        Route::post('actualizar-cuentas', [\App\Http\Controllers\CuentasController\UpdaterController::class, 'excelUploadPost'])->name('actualizar_base.post');
        Route::post('eliminar-studiantes-excel', [\App\Http\Controllers\UpdaterController\UpdaterController::class, 'borrarCuentas'])->name('base.borrar');
        Route::delete('/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy']);

        // Roles CRUD
        Route::resource('roles', \App\Http\Controllers\RoleController::class)->except(['show']);
        Route::get('/roles/{rol}/show', [\App\Http\Controllers\RoleController::class, 'show']);

        // Abilities CRUD
        Route::resource('abilities', \App\Http\Controllers\AbilitiesController::class)->except(['show']);
        Route::get('/abilities/{ability}/show', [\App\Http\Controllers\AbilitiesController::class, 'show']);

        // Role-ability assignment
        Route::get('roles-permisos', [\App\Http\Controllers\PermisosController::class, 'index'])->name('permisos.index');
        Route::get('roles-permisos/create', [\App\Http\Controllers\PermisosController::class, 'create']);
        Route::post('roles-permisos', [\App\Http\Controllers\PermisosController::class, 'store']);

        // Oferta
        Route::get('/importar-oferta', [App\Http\Controllers\Oferta\OfertaUploadController::class, 'import'])->name('import-oferta');

        // Careers
        Route::get('/importar-carreras', [App\Http\Controllers\Careers\CareerUploadController::class, 'import'])->name('import-careers');

        // Status
        Route::post('status', [\App\Http\Controllers\StatusController::class, 'store'])->name('status');

        // Encuestas
        Route::get('/encuestas', [\App\Http\Controllers\EncuestaController::class, 'downloadEncuestas']);

        // Bulk actions
        Route::post('/createUsersBatch', CreateUsersBatch::class);
        Route::post('/createCourseSessions', \App\Http\Controllers\CreateSchedules::class);
        Route::post('/enviar-correos-cuentas', [\App\Http\Controllers\UserController::class, 'enviarCorreoCuentas']);

        // Ajax
        Route::get('ajax/students', [\App\Http\Controllers\UserController::class, 'getStudentsAjax'])->name('ajax.students');
        Route::get('ajax/clases', [\App\Http\Controllers\UserController::class, 'getClasesAjax'])->name('ajax.clases');

        Route::view('/admin/dashboard', 'layouts.admin')->name('admin.dashboard');
    });

    // Public authenticated routes
    Route::view('/oferta-academica', 'pages.oferta-academica')->name('oferta-academica');

    Route::middleware('can:read_course_programs')->group(function () {
        Route::view('/course_programs', 'pages.course_programs')->name('course_programm');
    });

    Route::get('/clases-grabadas', App\Http\Livewire\CGrabadas\ClasesGrabadas::class)->name('clases');
    Route::get('/calculadora', App\Http\Livewire\Calculadora\CalculadoraController::class)->name('calculadora');
    Route::get('/carreras', App\Http\Livewire\Carreras\CarrerasController::class)->name('carreras');
    Route::view('/informacion', 'pages.informacion')->name('informacion');

    Route::post('encuesta', [\App\Http\Controllers\EncuestaController::class, 'store'])->name('encuesta');

    // PDF
    Route::get('pdfview', [\App\Http\Controllers\Pdf\CertificadoController::class, 'pdfForm'])->name('pdfview');

    Route::post('ajax/setUserExamMonth', [\App\Http\Controllers\UserController::class, 'setUserExamMonth'])->name('ajax.userExamMonth');
});
