<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Global Controller
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PublicController;

/*
|--------------------------------------------------------------------------
| Admins Controller
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\Master\TheoryController;
use App\Http\Controllers\Admin\Master\PackageController;
use App\Http\Controllers\Admin\Master\PlatformController;
use App\Http\Controllers\Admin\Master\ClassroomController;
use App\Http\Controllers\Admin\Master\ClassroomCategoryController;
use App\Http\Controllers\Admin\Master\SubClassroomCategoryController;
use App\Http\Controllers\Admin\Master\CoachController;
use App\Http\Controllers\Admin\Master\AdminController;
use App\Http\Controllers\Admin\Master\StudentController;
use App\Http\Controllers\Admin\Schedule\ScheduleController;

/*
|--------------------------------------------------------------------------
| Coach Controller
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Student Controller
|--------------------------------------------------------------------------
*/

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
    return redirect('/login');
});

Route::group(['middleware' => ['guest-handling']], function () {
    Route::get('login', [LoginController::class, 'index_login']);
    Route::post('login', [LoginController::class, 'login']);
});

Route::group(['middleware' => ['auth-handling']], function () {
    Route::get('logout', [LoginController::class, 'logout']);

    Route::group(['prefix' => 'admin', 'middleware' => 'admin-handling'], function () {
        Route::get('dashboard', [AdminDashboard::class, 'index']);

        Route::group(['prefix' => 'master'], function () {
            Route::group(['prefix' => 'courses'], function () {
                Route::post('package/dt', [PackageController::class, 'dt']);
                Route::resource('package', PackageController::class);

                Route::post('classroom-category/dt', [ClassroomCategoryController::class, 'dt']);
                Route::resource('classroom-category', ClassroomCategoryController::class);

                Route::post('sub-classroom-category/dt', [SubClassroomCategoryController::class, 'dt']);
                Route::post('sub-classroom-category/update/{id}', [SubClassroomCategoryController::class, 'update']);
                Route::resource('sub-classroom-category', SubClassroomCategoryController::class);

                Route::get('classroom/tools/ac', [ClassroomController::class, 'ac']);
                Route::get('classroom/tools/{id}', [ClassroomController::class, 'get_tools']);
                Route::delete('classroom/tools/{id}', [ClassroomController::class, 'delete_tools']);
                Route::post('classroom/dt', [ClassroomController::class, 'dt']);
                Route::post('classroom/update/{id}', [ClassroomController::class, 'update']);
                Route::resource('classroom', ClassroomController::class);
            });

            Route::group(['prefix' => 'coach'], function () {
                Route::get('coach-sosmed/{id}', [CoachController::class, 'coach_sosmed']);
                Route::get('permission/{id}', [CoachController::class, 'get_permission']);
                Route::get('class/{id}', [CoachController::class, 'get_class']);
                Route::get('view-calendar/{id}', [CoachController::class, 'view_calendar']);
                Route::post('update/{id}', [CoachController::class, 'update']);
                Route::post('permission/{id}', [CoachController::class, 'set_permission']);
                Route::post('config/{id}', [CoachController::class, 'config']);
                Route::post('activate-suspend/{id}', [CoachController::class, 'activate_suspend']);
                Route::post('suspend/{id}', [CoachController::class, 'suspend']);
                Route::delete('delete-medsos/{id}', [CoachController::class, 'delete_medsos']);
            });

            Route::post('coach/dt', [CoachController::class, 'dt']);
            Route::resource('coach', CoachController::class);

            Route::post('admin/dt', [AdminController::class, 'dt']);
            Route::resource('admin', AdminController::class);

            Route::post('student/dt', [StudentController::class, 'dt']);
            Route::post('student/update/{id}', [StudentController::class, 'update']);
            Route::resource('student', StudentController::class);
            Route::post('media-conference/dt', [PlatformController::class, 'dt']);
            Route::post('media-conference/update/{id}', [PlatformController::class, 'update']);
            Route::resource('media-conference', PlatformController::class);

            Route::post('theory/file', [TheoryController::class, 'theory_file']);
            Route::delete('theory/file/{id}', [TheoryController::class, 'theory_file_delete']);
            Route::post('theory/dt', [TheoryController::class, 'dt']);
            Route::post('theory/update/{id}', [TheoryController::class, 'update']);
            Route::resource('theory', TheoryController::class);
        });

        Route::get('schedule', [ScheduleController::class, 'index']);
    });

    Route::group(['prefix' => 'coach', 'middleware' => 'coach-handling'], function () {

    });

    Route::group(['prefix' => 'student', 'middleware' => 'student-handling'], function () {

    });

    Route::group(['prefix' => 'public'], function () {
        Route::get('get-classroom-category', [PublicController::class, 'get_classroom_category']);
        Route::get('get-sub-classroom-category', [PublicController::class, 'get_sub_classroom_category']);
        Route::get('get-sub-classroom-category-by-category/{id}', [PublicController::class, 'get_sub_classroom_category_by_category']);
        Route::get('get-profile-coach-video', [PublicController::class, 'get_profile_coach_videos']);
        Route::get('get-sosmed',[PublicController::class, 'get_sosmed']);
        Route::get('get-package',[PublicController::class, 'get_package']);
        Route::get('get-class/{package_id}',[PublicController::class, 'get_class']);
        Route::get('get-classroom/{category_id}&{sub_category_id}', [PublicController::class, 'get_classroom']);
        Route::get('get-session/{classroom_id}', [PublicController::class, 'get_session']);
    });
});
