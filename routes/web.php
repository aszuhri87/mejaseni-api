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
use App\Http\Controllers\Admin\Master\PackageController;
use App\Http\Controllers\Admin\Master\ClassroomController;
use App\Http\Controllers\Admin\Master\ClassroomCategoryController;
use App\Http\Controllers\Admin\Master\SubClassroomCategoryController;
use App\Http\Controllers\Admin\Master\CoachController;
use App\Http\Controllers\Admin\Master\AdminController;

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
                Route::post('classroom/dt', [ClassroomController::class, 'dt']);
                Route::post('classroom/update/{id}', [ClassroomController::class, 'update']);
                Route::resource('classroom', ClassroomController::class);
            });

            Route::post('coach/dt', [CoachController::class, 'dt']);
            Route::post('coach/update/{id}', [CoachController::class, 'update']);
            Route::delete('coach/delete-medsos/{id}', [CoachController::class, 'delete_medsos']);
            Route::get('coach/coach-sosmed/{id}', [CoachController::class, 'coach_sosmed']);
            Route::get('coach/permission/{id}', [CoachController::class, 'get_permission']);
            Route::post('coach/permission/{id}', [CoachController::class, 'set_permission']);
            Route::resource('coach', CoachController::class);

            Route::post('admin/dt', [AdminController::class, 'dt']);
            Route::resource('admin', AdminController::class);

        });
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
    });
});
