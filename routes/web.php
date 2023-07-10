<?php

use App\Http\Controllers\Account\SettingsController;
use App\Http\Controllers\Auth\SocialiteLoginController;
use App\Http\Controllers\Documentation\ReferencesController;
use App\Http\Controllers\Logs\AuditLogsController;
use App\Http\Controllers\Logs\SystemLogsController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\TeacherCOntroller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

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

// Route::get('/', function () {
//     return redirect('index');
// });

// Documentations pages
Route::prefix('documentation')->group(function () {
    Route::get('getting-started/references', [ReferencesController::class, 'index']);
    Route::get('getting-started/changelog', [PagesController::class, 'index']);
});


Route::resource('users', UsersController::class);

/**
 * Socialite login using Google service
 * https://laravel.com/docs/8.x/socialite
 */
Route::get('/auth/redirect/{provider}', [SocialiteLoginController::class, 'redirect']);

require __DIR__.'/auth.php';

Route::get('/Sign_Up', [RegisterController::class, 'index'])->name('registering');
Route::post('/Create/Account', [RegisterController::class, 'SignUp'])->name('register');
Route::post('/student-form', [DashboardController::class, 'studentForm'])->name('student-form');
Route::post('/teacher-form', [DashboardController::class, 'teacherForm'])->name('teacher-form');
Route::post('/teacher_edit_form', [TeacherController::class, 'editTeacherForm']);
Route::delete('/teachers/{id}', [TeacherController::class, 'destroy']);

//student
Route::post('/save-result', [StudentController::class, 'submitStudentResults']);
Route::delete('/students/{id}', [StudentController::class, 'destroy']);
Route::get('/check-marks-status/{id}', [StudentController::class, 'checkMarksStatus']);
Route::put('/update-status/{id}', [StudentController::class, 'updateStatus']);
Route::post('/student_edit_form', [StudentController::class, 'editStudentForm']);
Route::post('/result_edit', [StudentController::class, 'editResult']);
Route::post('/result_end_edit', [StudentController::class, 'editEndResult']);

//Route::post('/login', [LoginController::class, 'store'])->name('signIn');

Route::middleware(['auth', 'student'])->group(function () {
    // Routes accessible only to student users
    

    //
    Route::get('/Students', [StudentController::class, 'index'])->name('student-page');

    //Teacher
    Route::get('/Teachers', [TeacherController::class, 'index'])->name('Teacher-page');
});

Route::middleware('auth')->group(function () {
    // Account pages
    Route::prefix('account')->group(function () {
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::put('settings/email', [SettingsController::class, 'changeEmail'])->name('settings.changeEmail');
        Route::put('settings/password', [SettingsController::class, 'changePassword'])->name('settings.changePassword');
    });

    // Logs pages
    Route::prefix('log')->name('log.')->group(function () {
    Route::resource('system', SystemLogsController::class)->only(['index', 'destroy']);
    Route::resource('audit', AuditLogsController::class)->only(['index', 'destroy']);
    });


    Route::get('/', [PagesController::class, 'index']);

    Route::get('/Registration', [ResidentController::class, 'addResident']);

});

