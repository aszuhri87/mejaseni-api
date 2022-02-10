<?php

use Illuminate\Support\Facades\Route;

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
use App\Http\Controllers\Student\VideoController as StudentVideoController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Student\CartController as StudentCartController;
use App\Http\Controllers\Student\ExerciseController as StudentExerciseController;
use App\Http\Controllers\Student\ReviewController as StudentReviewController;
use App\Http\Controllers\Student\RequestScheduleController as StudentRequestScheduleController;
use App\Http\Controllers\Student\NotificationController as StudentNotificationController;

use App\Http\Controllers\Cms\ClassController as ClassController;

/*
|--------------------------------------------------------------------------
| Students Route
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'student', 'middleware' => 'student-handling'], function () {

    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/', [StudentDashboardController::class, 'index']);
        Route::get('total-class', [StudentDashboardController::class, 'total_class']);
        Route::get('total-video', [StudentDashboardController::class, 'total_video']);
        Route::get('total-booking', [StudentDashboardController::class, 'total_booking']);
        Route::get('history-booking', [StudentDashboardController::class, 'history_booking']);
        Route::get('history-booking', [StudentDashboardController::class, 'history_booking']);
        Route::get('rest-session', [StudentDashboardController::class, 'rest_session']);
        Route::get('upcoming', [StudentDashboardController::class, 'upcoming']);
        Route::get('student-booking-month', [StudentDashboardController::class, 'student_booking_month']);
        Route::get('my-course', [StudentDashboardController::class, 'my_course']);
        Route::get('progress-class', [StudentDashboardController::class, 'progress_class']);
        Route::get('summary-course', [StudentDashboardController::class, 'summary_course']);
        Route::post('save-tour/{id}', [StudentDashboardController::class, 'save_tour']);
    });

    Route::group(['prefix' => 'invoice'], function () {
        Route::get('/', [StudentInvoiceController::class, 'index']);
        Route::post('dt', [StudentInvoiceController::class, 'dt']);
        Route::get('detail/{id}', [StudentInvoiceController::class, 'detail']);
    });

    Route::group(['prefix' => 'schedule'], function () {
        Route::get('/', [StudentScheduleController::class, 'index']);
        Route::get('get-total-class/{student_id}', [StudentScheduleController::class, 'get_total_class']);
        Route::get('student-rating', [StudentScheduleController::class, 'student_rating']);

        Route::group(['prefix' => 'regular-class'], function () {
            Route::get('/', [StudentScheduleController::class, 'regular_class']);
            Route::get('{coach_schedule_id}', [StudentScheduleController::class, 'coach_schedule']);
            Route::post('list-reschedule',[StudentScheduleController::class, 'list_reschedule']);
            Route::post('booking', [StudentScheduleController::class, 'booking']);
            Route::post('reschedule', [StudentScheduleController::class, 'reschedule']);
            Route::post('new-reschedule', [StudentScheduleController::class, 'new_reschedule']);
        });

        Route::group(['prefix' => 'special-class'], function () {
            Route::get('/', [StudentScheduleController::class, 'special_class']);
            Route::get('{coach_schedule_id}', [StudentScheduleController::class, 'coach_schedule']);
            Route::post('booking', [StudentScheduleController::class, 'booking']);
            Route::post('reschedule', [StudentScheduleController::class, 'reschedule']);
        });

        Route::group(['prefix' => 'master-lesson'], function () {
            Route::get('/', [StudentScheduleController::class, 'master_lesson']);
            Route::post('booking/{id}', [StudentScheduleController::class, 'booking_master_lesson']);
        });

        Route::group(['prefix' => 'coach-list'], function () {
            Route::get('/', [StudentScheduleController::class, 'coach_list']);
            Route::get('new', [StudentScheduleController::class, 'coach_list_new']);
        });

        Route::group(['prefix' => 'request'], function () {
            Route::post('/', [StudentRequestScheduleController::class, 'store']);
            Route::get('/list', [StudentRequestScheduleController::class, 'request_list']);
            Route::post('dt', [StudentRequestScheduleController::class, 'dt']);
            Route::post('/single', [StudentRequestScheduleController::class, 'single_request']);
            Route::get('/classroom', [StudentRequestScheduleController::class, 'classroom']);
            Route::get('/list/{id}', [StudentRequestScheduleController::class, 'show']);
        });

        Route::get('/print', [StudentScheduleController::class, 'print']);
    });

    Route::group(['prefix' => 'my-class'], function () {
        Route::get('/', [StudentMyClassController::class, 'index']);
        Route::post('booking/dt', [StudentMyClassController::class, 'booking_dt']);
        Route::post('rating', [StudentMyClassController::class, 'rating']);
        Route::post('last-class/dt', [StudentMyClassController::class, 'last_class_dt']);
        Route::post('review/{id}', [StudentMyClassController::class, 'review']);
        Route::put('reschedule/{id}', [StudentMyClassController::class, 'reschedule']);
        Route::get('class-active/{id}', [StudentMyClassController::class, 'class_active']);
        Route::get('checkin/{id}', [StudentMyClassController::class, 'checkin']);
        Route::get('get-review/{id}', [StudentMyClassController::class, 'get_review']);
    });

    Route::group(['prefix' => 'new-package'], function () {
        Route::get('/', [StudentNewPackageController::class, 'index']);
        Route::get('get-special-offer', [StudentNewPackageController::class, 'get_special_offer']);
        Route::get('get-special-offer-detail/{id}', [StudentNewPackageController::class, 'special_offer_detail']);
        Route::get('get-package', [StudentNewPackageController::class, 'get_package']);
        Route::get('get-sub-classroom-category', [StudentNewPackageController::class, 'get_sub_classroom_category']);
        Route::get('sub-classroom-category/{sub_classroom_category_id}', [StudentNewPackageController::class, 'get_classroom_by_sub_category_id']);
        Route::get('get-session-video', [StudentNewPackageController::class, 'get_session_video']);
        Route::get('get-master-lesson', [StudentNewPackageController::class, 'get_master_lesson']);
        Route::get('classroom-category/{classroom_category_id}', [StudentNewPackageController::class, 'get_classroom_by_category_id']);
    });

    Route::group(['prefix' => 'my-video'], function () {
        Route::get('/', [StudentVideoController::class, 'index']);
        Route::get('get-video', [StudentVideoController::class, 'get_video']);
        Route::get('get-sub-classroom-category', [StudentVideoController::class, 'get_sub_classroom_category']);
        Route::get('video-detail/{session_video_id}', [StudentVideoController::class, 'video_detail']);
    });

    Route::group(['prefix' => 'theory'], function () {
        Route::group(['prefix' => 'theory-class'], function () {
            Route::get('/', [StudentTheoryController::class, 'index']);
            Route::get('get-theory', [StudentTheoryController::class, 'get_theory']);
            Route::get('get-class/{student_id}', [StudentTheoryController::class, 'get_class']);
            Route::get('filter_theory', [StudentTheoryController::class, 'filter_theory']);
        });
    });

    Route::group(['prefix' => 'exercise'], function () {
        Route::get('/', [StudentExerciseController::class, 'index']);
        Route::get('get-exercise', [StudentExerciseController::class, 'get_exercise']);
        Route::get('get-collection/{collection_id}', [StudentExerciseController::class, 'get_collection']);
        Route::get('get-class/{student_id}', [StudentExerciseController::class, 'get_class']);
        Route::get('get-result-exercise/{id}', [StudentExerciseController::class, 'get_result']);
        Route::post('exercise-file', [StudentExerciseController::class, 'exercise_file']);
        Route::post('update/{collection_id}', [StudentExerciseController::class, 'update']);
        Route::post('store', [StudentExerciseController::class, 'store']);
        Route::delete('exercise-file/{id}', [StudentExerciseController::class, 'exercise_file_delete']);
        Route::delete('exercise-file/delete/{id}', [StudentExerciseController::class, 'file_delete']);
    });

    Route::group(['prefix' => 'review'], function () {
        Route::get('/', [StudentReviewController::class, 'index']);
        Route::get('get-review/{id}', [StudentReviewController::class, 'get_review']);
        Route::post('dt', [StudentReviewController::class, 'dt']);
        Route::post('dt-new', [StudentReviewController::class, 'dt_new']);
    });

    Route::group(['prefix' => 'notification'], function () {
        Route::get('/', [StudentNotificationController::class, 'index']);
        Route::post('dt', [StudentNotificationController::class, 'dt']);
    });

    Route::group(['prefix' => 'notification'], function () {
        Route::get('/', [StudentNotificationController::class, 'index']);
        Route::post('dt', [StudentNotificationController::class, 'dt']);
    });

    Route::post('profile/{id}', [StudentProfileController::class,'update']);
    Route::post('profile/change-password/{id}', [StudentProfileController::class,'change_password']);
    Route::get('profile', [StudentProfileController::class,'index']);

    Route::get('package-detail/{session_video_id}', [StudentPackageDetailController::class, 'index']);

    Route::post('add-to-cart',[StudentCartController::class, 'store']);
    Route::get('get-cart/{student_id}',[StudentCartController::class, 'get_cart']);
    Route::delete('delete-cart/{cart_id}',[StudentCartController::class, 'delete_cart']);

    Route::post('/event/{event_id}/add-to-cart',[StudentCartController::class, 'event']);
    Route::post('/video-course/{video_course_id}/add-to-cart', [StudentCartController::class, 'video_course']);
    Route::get('/class/{classroom_id}/detail', [ClassController::class, 'detail']);
    Route::post('/class/add-to-cart', [ClassController::class, 'store']);
});
