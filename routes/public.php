<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PublicController;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'public'], function () {
    Route::get('get-classroom-detail/{id}', [PublicController::class, 'classroom_detail']);
    Route::get('get-classroom-category', [PublicController::class, 'get_classroom_category']);
    Route::get('get-has-classroom-category', [PublicController::class, 'get_has_classroom_category']);
    Route::get('get-sub-classroom-category', [PublicController::class, 'get_sub_classroom_category']);
    Route::get('get-sub-classroom-category-by-category/{id}', [PublicController::class, 'get_sub_classroom_category_by_category']);
    Route::get('get-profile-coach-video', [PublicController::class, 'get_profile_coach_videos']);
    Route::get('get-sosmed', [PublicController::class, 'get_sosmed']);
    Route::get('get-package', [PublicController::class, 'get_package']);
    Route::get('get-class/{package_id}', [PublicController::class, 'get_class']);
    Route::get('get-classroom/{category_id}&{sub_category_id}', [PublicController::class, 'get_classroom']);
    Route::get('get-session/{classroom_id}', [PublicController::class, 'get_session']);
    Route::get('get-coach', [PublicController::class, 'get_coach']);
    Route::get('get-coach-by-class/{id}', [PublicController::class, 'get_coach_by_class']);
    Route::get('get-expertise', [PublicController::class, 'get_expertise']);
    Route::get('get-platform', [PublicController::class, 'get_platform']);
    Route::get('get-classroom-coach', [PublicController::class, 'get_classroom_coach']);
    Route::get('get-session-coach/{classroom_id}', [PublicController::class, 'get_session_coach']);
    Route::get('get-session-name-coach/{classroom_id}', [PublicController::class, 'get_session_name_coach']);
    Route::get('get-guest-star', [PublicController::class, 'get_guest_star']);
    Route::get('get-student', [PublicController::class, 'get_student']);
});

