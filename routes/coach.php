<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Coach Controller
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Coach\DashboardController;
use App\Http\Controllers\Coach\ProfileController as CoachProfileController;
use App\Http\Controllers\Coach\BankAccountController as CoachBankAccountController;
use App\Http\Controllers\Coach\TransactionController as CoachTransactionController;
use App\Http\Controllers\Coach\TheoryController as CoachTheoryController;
use App\Http\Controllers\Coach\Schedule\ScheduleController as CoachScheduleController;
use App\Http\Controllers\Coach\Exercise\AssignmentController;
use App\Http\Controllers\Coach\Exercise\ReviewAssignmentController;
use App\Http\Controllers\Coach\Notification\NotificationController as CoachNotificationController;
use App\Http\Controllers\Coach\Schedule\RequestScheduleController as CoachRequestScheduleController;

/*
|--------------------------------------------------------------------------
| Coaches Route
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'coach', 'middleware' => 'coach-handling'], function () {
    Route::group(['prefix' => 'dashboard'], function () {

        Route::get('summary-course-chart', [DashboardController::class, 'summary_course_chart']);
        Route::get('incomes-chart', [DashboardController::class, 'incomes_chart']);
        Route::get('side-summary-course', [DashboardController::class, 'side_summary_course']);
        Route::post('dt-last-class', [DashboardController::class, 'dt_last_class']);
        Route::post('closest-schedule', [DashboardController::class, 'closest_schedule']);
        Route::get('coach-show-review-last-class/{id}/{student_schedule_id}', [DashboardController::class, 'coach_show_review_last_class']);
        Route::get('show-last-class/{id}/{student_schedule_id}', [DashboardController::class, 'show_last_class']);
        Route::post('review-last-class/{id}&{student_schedule_id}', [DashboardController::class, 'review_last_class']);
        Route::delete('cancle/schedule/{id}', [DashboardController::class, 'cancle_schedule']);
        Route::resource('review-assignment', ReviewAssignmentController::class);
        Route::resource('/', DashboardController::class);
    });

    Route::group(['prefix' => 'theory'], function () {

        Route::post('file', [CoachTheoryController::class, 'theory_file']);
        Route::delete('file/{id}', [CoachTheoryController::class, 'theory_file_delete']);
        Route::get('list/{classroom_id}/{session_id}', [CoachTheoryController::class, 'theory_list']);
        Route::get('download/{id}', [CoachTheoryController::class, 'theory_download']);
        Route::get('/', [CoachTheoryController::class, 'index']);
        Route::post('/', [CoachTheoryController::class, 'store']);
        Route::put('/{id}', [CoachTheoryController::class, 'update']);
        Route::delete('/{id}', [CoachTheoryController::class, 'destroy']);
        // Route::resource('/', CoachTheoryController::class);
    });

    Route::group(['prefix' => 'exercise'], function () {

        Route::post('assignment/file', [AssignmentController::class, 'assignment_file']);
        Route::delete('assignment/file/{id}', [AssignmentController::class, 'assignment_file_delete']);
        Route::get('assignment/list/{classroom_id}&{session_id}', [AssignmentController::class, 'assignment_list']);
        Route::get('assignment/download/{id}', [AssignmentController::class, 'assignment_download']);
        Route::resource('assignment', AssignmentController::class);

        Route::post('review-assignment/file', [ReviewAssignmentController::class, 'assignment_file']);
        Route::delete('review-assignment/file/{id}', [ReviewAssignmentController::class, 'assignment_file_delete']);
        Route::get('review-assignment/list/{classroom_id}/{session_id}', [ReviewAssignmentController::class, 'assignment_list']);
        Route::get('review-assignment/download/{id}', [ReviewAssignmentController::class, 'assignment_download']);
        Route::post('review-assignment/dt/{classroom_id}/{session_id}', [ReviewAssignmentController::class, 'dt']);
        Route::get('review-assignment', [ReviewAssignmentController::class, 'index']);
        Route::get('review-assignment/{id}', [ReviewAssignmentController::class, 'show']);
        Route::get('review-assignment/{id}/edit', [ReviewAssignmentController::class, 'edit']);
        Route::post('review-assignment', [ReviewAssignmentController::class, 'store']);
        Route::put('review-assignment/{id}', [ReviewAssignmentController::class, 'update']);
        Route::delete('review-assignment/{id}', [ReviewAssignmentController::class, 'destroy']);
        // Route::resource('review-assignment', ReviewAssignmentController::class);
    });

    Route::get('schedule', [CoachScheduleController::class, 'index']);
    Route::get('schedule/all', [CoachScheduleController::class, 'all']);
    Route::get('schedule/{id}', [CoachScheduleController::class, 'show']);
    Route::get('schedule-show/{id}', [CoachScheduleController::class, 'show_edit']);
    Route::post('schedule', [CoachScheduleController::class, 'store']);
    Route::post('schedule/{id}', [CoachScheduleController::class, 'update']);
    Route::post('schedule/update/{id}', [CoachScheduleController::class, 'update_time']);
    Route::post('schedule/delete/{id}', [CoachScheduleController::class, 'delete']);
    Route::get('schedule-print', [CoachScheduleController::class, 'print']);

    Route::get('schedule-request/list', [CoachRequestScheduleController::class, 'request_list']);
    Route::post('schedule-request/dt', [CoachRequestScheduleController::class, 'dt']);
    Route::resource('schedule-request', CoachRequestScheduleController::class);

    Route::group(['prefix' => 'withdraw'], function () {
        Route::post('/detail/dt', [CoachTransactionController::class, 'withdraw_dt']);
        Route::get('/detail', [CoachTransactionController::class, 'withdraw']);
        Route::get('/get-balance', [CoachTransactionController::class, 'get_balance']);
        Route::post('/request', [CoachTransactionController::class, 'store']);
    });

    Route::get('/notification', [CoachNotificationController::class, 'index']);
    Route::post('/notification/dt', [CoachNotificationController::class, 'dt']);

    Route::post('profile/{id}', [CoachProfileController::class,'update']);
    Route::post('profile/change-password/{id}', [CoachProfileController::class,'change_password']);
    Route::get('profile', [CoachProfileController::class,'index']);

    Route::group(['prefix' => 'bank-account'], function () {
        Route::get('/', [CoachBankAccountController::class, 'index']);
        Route::post('{id}', [CoachBankAccountController::class, 'update']);
    });
});
