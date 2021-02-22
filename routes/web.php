<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Global Controller
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\MediaController;

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
use App\Http\Controllers\Admin\Master\SessionVideoController;
use App\Http\Controllers\Admin\Master\TheoryVideoController;
use App\Http\Controllers\Admin\Master\SubClassroomCategoryController;
use App\Http\Controllers\Admin\Master\CoachController;
use App\Http\Controllers\Admin\Master\CoachListController;
use App\Http\Controllers\Admin\Master\AdminController;
use App\Http\Controllers\Admin\Master\StudentController;
use App\Http\Controllers\Admin\Master\ExpertiseController;
use App\Http\Controllers\Admin\Master\GuestStarController;
use App\Http\Controllers\Admin\Master\MasterLessonController;
use App\Http\Controllers\Admin\Schedule\ScheduleController;

/*
|--------------------------------------------------------------------------
| Coach Controller
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Coach\DashboardController;
use App\Http\Controllers\Coach\TheoryController as CoachTheoryController;

/*
|--------------------------------------------------------------------------
| Student Controller
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\InvoiceController as StudentInvoiceController;
use App\Http\Controllers\Student\ScheduleController as StudentScheduleController;
use App\Http\Controllers\Student\MyClassController as StudentMyClassController;
use App\Http\Controllers\Student\NewPackageController as StudentNewPackageController;
use App\Http\Controllers\Student\PackageDetailController as StudentPackageDetailController;
use App\Http\Controllers\Student\TheoryController as StudentTheoryController;

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
    Route::post('media/file', [MediaController::class, 'file_upload']);
    Route::delete('media/file/{id}', [MediaController::class, 'file_delete']);

    /*
    |--------------------------------------------------------------------------
    | Admins Route
    |--------------------------------------------------------------------------
    */

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

                Route::post('session-video/detail/dt/{id}', [TheoryVideoController::class, 'dt']);
                Route::post('session-video/detail/update/{id}', [TheoryVideoController::class, 'update']);
                Route::post('session-video/detail/store', [TheoryVideoController::class, 'store']);
                Route::delete('session-video/detail/{id}', [TheoryVideoController::class, 'destroy']);

                Route::post('session-video/file/dt/{id}', [TheoryVideoController::class, 'file_dt']);
                Route::post('session-video/file/update/{id}', [TheoryVideoController::class, 'file_update']);
                Route::post('session-video/file/store', [TheoryVideoController::class, 'file_store']);
                Route::delete('session-video/file/{id}', [TheoryVideoController::class, 'file_destroy']);

                Route::post('session-video/dt', [SessionVideoController::class, 'dt']);
                Route::resource('session-video', SessionVideoController::class);

                Route::post('master-lesson/dt', [MasterLessonController::class, 'dt']);
                Route::resource('master-lesson', MasterLessonController::class);
            });

            Route::post('guest-star/dt', [GuestStarController::class, 'dt']);
            Route::resource('guest-star', GuestStarController::class);

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

                Route::group(['prefix' => 'view-list'], function () {
                    Route::get('get-class-by-coach/{id}', [CoachListController::class, 'get_class']);
                    Route::get('{id}', [CoachListController::class, 'index']);
                    Route::post('store', [CoachListController::class, 'store']);

                });
            });

            Route::post('coach/dt', [CoachController::class, 'dt']);
            Route::resource('coach', CoachController::class);

            Route::resource('admin', AdminController::class);
            Route::post('admin/dt', [AdminController::class, 'dt']);
            Route::get('admin/permission/{id}', [AdminController::class, 'get_permission']);
            Route::post('admin/permission/{id}', [AdminController::class, 'set_permission']);

            Route::post('student/dt', [StudentController::class, 'dt']);
            Route::post('student/update/{id}', [StudentController::class, 'update']);
            Route::resource('student', StudentController::class);

            Route::post('media-conference/dt', [PlatformController::class, 'dt']);
            Route::post('media-conference/update/{id}', [PlatformController::class, 'update']);
            Route::resource('media-conference', PlatformController::class);

            Route::post('theory/dt', [TheoryController::class, 'dt']);
            Route::post('theory/update/{id}', [TheoryController::class, 'update']);
            Route::resource('theory', TheoryController::class);

            Route::post('expertise/dt', [ExpertiseController::class, 'dt']);
            Route::resource('expertise', ExpertiseController::class);
        });

        Route::get('schedule', [ScheduleController::class, 'index']);
        Route::get('schedule/all', [ScheduleController::class, 'all']);
        Route::get('schedule/{id}', [ScheduleController::class, 'show']);
        Route::get('schedule-show/{id}', [ScheduleController::class, 'show_edit']);
        Route::post('schedule', [ScheduleController::class, 'store']);
        Route::post('schedule/{id}', [ScheduleController::class, 'update']);
        Route::post('schedule/update/{id}', [ScheduleController::class, 'update_time']);
        Route::post('schedule/confirm/{id}', [ScheduleController::class, 'confirm']);
        Route::post('schedule/delete/{id}', [ScheduleController::class, 'delete']);
    });

    /*
    |--------------------------------------------------------------------------
    | Coaches Route
    |--------------------------------------------------------------------------
    */

    Route::group(['prefix' => 'coach', 'middleware' => 'coach-handling'], function () {
        Route::get('dashboard/summary-course-chart', [DashboardController::class, 'summary_course_chart']);
        Route::get('dashboard/side-summary-course', [DashboardController::class, 'side_summary_course']);
        Route::resource('dashboard', DashboardController::class);

        Route::post('theory/file', [CoachTheoryController::class, 'theory_file']);
        Route::delete('theory/file/{id}', [CoachTheoryController::class, 'theory_file_delete']);
        Route::get('theory/list/{classroom_id}/{session_id}', [CoachTheoryController::class, 'theory_list']);
        Route::get('theory/download/{path}', [CoachTheoryController::class, 'download']);
        // Route::resource('theory', CoachTheoryController::class);

    });

    /*
    |--------------------------------------------------------------------------
    | Students Route
    |--------------------------------------------------------------------------
    */

    Route::group(['prefix' => 'student', 'middleware' => 'student-handling'], function () {
        Route::get('/dashboard', [StudentDashboardController::class, 'index']);

        Route::get('invoice', [StudentInvoiceController::class, 'index']);

        Route::group(['prefix' => 'schedule'], function () {
            Route::get('/', [StudentScheduleController::class, 'index']);
            Route::get('regular-class', [StudentScheduleController::class, 'regular_class']);
            Route::get('special-class', [StudentScheduleController::class, 'special_class']);
        });

        Route::get('my-class',[StudentMyClassController::class, 'index']);

        Route::group(['prefix' => 'new-package'], function () {
            Route::get('/',[StudentNewPackageController::class, 'index']);
            Route::get('get-package',[StudentNewPackageController::class, 'get_package']);
            Route::get('classroom-category/{classroom_category_id}',[StudentNewPackageController::class, 'get_classroom_by_category_id']);
            Route::get('get-session-video', [StudentNewPackageController::class, 'get_session_video']);
        });

        Route::group(['prefix' => 'theory'], function () {
            Route::get('/', [StudentTheoryController::class, 'index']);
            Route::get('get-theory', [StudentTheoryController::class, 'get_theory']);
            Route::get('get-class/{student_id}', [StudentTheoryController::class, 'get_class']);
        });

        Route::get('package-detail/{session_video_id}',[StudentPackageDetailController::class, 'index']);
    });

    /*
    |--------------------------------------------------------------------------
    | Public Route
    |--------------------------------------------------------------------------
    */

    Route::group(['prefix' => 'public'], function () {
        Route::get('get-classroom-category', [PublicController::class, 'get_classroom_category']);
        Route::get('get-has-classroom-category', [PublicController::class, 'get_has_classroom_category']);
        Route::get('get-sub-classroom-category', [PublicController::class, 'get_sub_classroom_category']);
        Route::get('get-sub-classroom-category-by-category/{id}', [PublicController::class, 'get_sub_classroom_category_by_category']);
        Route::get('get-profile-coach-video', [PublicController::class, 'get_profile_coach_videos']);
        Route::get('get-sosmed',[PublicController::class, 'get_sosmed']);
        Route::get('get-package',[PublicController::class, 'get_package']);
        Route::get('get-class/{package_id}',[PublicController::class, 'get_class']);
        Route::get('get-classroom/{category_id}&{sub_category_id}', [PublicController::class, 'get_classroom']);
        Route::get('get-session/{classroom_id}', [PublicController::class, 'get_session']);
        Route::get('get-coach',[PublicController::class, 'get_coach']);
        Route::get('get-coach-by-class/{id}',[PublicController::class, 'get_coach_by_class']);
        Route::get('get-expertise',[PublicController::class, 'get_expertise']);
        Route::get('get-platform',[PublicController::class, 'get_platform']);
        Route::get('get-classroom-coach', [PublicController::class, 'get_classroom_coach']);
        Route::get('get-session-coach/{classroom_id}', [PublicController::class, 'get_session_coach']);
    });

});
