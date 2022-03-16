<?php

use App\Http\Controllers\Auth\LoginController;
/*
|--------------------------------------------------------------------------
| Global Controller
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Auth\ProviderController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Cms\AboutController as AboutController;
use App\Http\Controllers\Cms\CareerController as CareerController;
use App\Http\Controllers\Cms\CareerDetailController as CareerDetailController;
use App\Http\Controllers\Cms\ClassController as ClassController;
use App\Http\Controllers\Cms\EventDetailController as EventDetailController;
/*
|--------------------------------------------------------------------------
| CMS Controller
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Cms\EventListController as EventListController;
use App\Http\Controllers\Cms\FaqController as FaqController;
use App\Http\Controllers\Cms\GaleryController as GaleryDetailController;
use App\Http\Controllers\Cms\HomePageController as HomePageController;
use App\Http\Controllers\Cms\NewsDetailController as NewsDetailController;
use App\Http\Controllers\Cms\NewsEventController as NewsEventController;
use App\Http\Controllers\Cms\NewsListController as NewsListController;
use App\Http\Controllers\Cms\PrivacyPolicyController as PrivacyPolicyController;
use App\Http\Controllers\Cms\StoreController as StoreController;
use App\Http\Controllers\Cms\StoreDetailController as StoreDetailController;
use App\Http\Controllers\Cms\TosController as TosController;
use App\Http\Controllers\Cms\VideoCourseController as VideoCourseController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Transaction\CartController;
use App\Http\Controllers\Transaction\PaymentController;
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

/*
|--------------------------------------------------------------------------
| CMS
|--------------------------------------------------------------------------
*/

Route::get('/', [HomePageController::class, 'index']);
Route::get('/class', [ClassController::class, 'index']);
Route::get('/class/{category?}', [ClassController::class, 'index']);
Route::get('/class/classroom_category/{category_id}/sub_classroom_category/{sub_category_id}', [ClassController::class, 'get_sub_category']);
Route::get('/classroom_category/sub_classroom_category/{sub_category_id}/classroom', [ClassController::class, 'get_classroom']);
Route::get('/class/{classroom_category_id}/sub_classroom_category/{sub_classroom_category_id}/package/{package}', [ClassController::class, 'get_package']);
Route::get('/class/{classroom_id}/coachs', [ClassController::class, 'get_coach']);
Route::get('/class/{classroom_id}/tools', [ClassController::class, 'get_tools']);
Route::get('/class/{classroom_id}/description', [ClassController::class, 'get_description']);
Route::get('/master-lesson/{master_lesson_id}/guest-star', [ClassController::class, 'get_guest_start']);
Route::get('/master-lesson/{master_lesson_id}/detail', [ClassController::class, 'get_master_lesson']);

Route::get('/video-course', [VideoCourseController::class, 'index']);
Route::post('/video-course/search', [VideoCourseController::class, 'search']);
Route::get('/video-course/{video_course_id}/detail', [StoreDetailController::class, 'index']);
Route::get('/video-course/videos/{id}', [StoreDetailController::class, 'get_videos']);
Route::get('/classroom_category/{category_id}/sub_classroom_category', [VideoCourseController::class, 'get_sub_category']);
Route::get('/classroom_category/sub_classroom_category/{sub_category_id}/video-course', [VideoCourseController::class, 'get_video_courses']);

Route::get('/store', [StoreController::class, 'index']);

Route::get('/news-event', [NewsEventController::class, 'index']);
Route::get('/event-list', [EventListController::class, 'index']);
Route::get('/event-list/paging', [EventListController::class, 'paging']);
Route::get('/event/{event_id}/detail', [EventDetailController::class, 'index']);
Route::get('/news-list', [NewsListController::class, 'index']);
Route::get('/news/{news_id}/detail', [NewsDetailController::class, 'index']);

Route::get('/news-list', [NewsListController::class, 'index']);
Route::post('/news-list/search', [NewsListController::class, 'search']);
Route::get('/news/{news_id}/detail', [NewsDetailController::class, 'index']);

Route::get('/about', [AboutController::class, 'index']);
Route::get('/privacy-policy', [PrivacyPolicyController::class, 'index']);
Route::get('/tos', [TosController::class, 'index']);

Route::get('/faq', [FaqController::class, 'index']);
Route::post('/question', [QuestionController::class, 'store']);

Route::get('/career', [CareerController::class, 'index']);
Route::post('/career', [CareerController::class, 'store']);
Route::get('/career/{id}/detail', [CareerDetailController::class, 'index']);
Route::post('/notifications/payments', [PaymentController::class, 'notification']);

Route::get('/galery/{id}/detail', [GaleryDetailController::class, 'index']);

Route::group(['middleware' => ['guest-handling']], function () {
    Route::get('email-verification/check/{token}', [RegisterController::class, 'email_verification']);

    Route::get('forgot-password', [RegisterController::class, 'index_forgot_password']);
    Route::post('forgot-password', [RegisterController::class, 'forgot_password']);

    Route::get('login', [LoginController::class, 'index_login']);
    Route::post('login', [LoginController::class, 'login']);

    Route::get('register', [RegisterController::class, 'index_register']);
    Route::post('register', [RegisterController::class, 'register']);

    Route::get('register-success', [RegisterController::class, 'registration_success']);
    Route::get('reset-password-success', [RegisterController::class, 'reset_password_success']);

    Route::get('auth/{provider}', [ProviderController::class, 'redirect_provider']);
    Route::get('auth/{provider}/callback', [ProviderController::class, 'callback_provider']);
});

Route::group(['middleware' => ['auth-handling']], function () {
    Route::get('logout', [LoginController::class, 'logout']);
    Route::post('media/file', [MediaController::class, 'file_upload']);
    Route::delete('media/file/{id}', [MediaController::class, 'file_delete']);
    Route::get('/notification', [NotificationController::class, 'notification']);

    /*
    |--------------------------------------------------------------------------
    | Payment Route
    |--------------------------------------------------------------------------
    */

    Route::group(['middleware' => ['student-handling']], function () {
        Route::get('/cart', [CartController::class, 'index']);
        Route::delete('/cart/{id}', [CartController::class, 'destroy']);
        Route::post('/cart-store', [CartController::class, 'store']);
        Route::post('/cart-payment', [CartController::class, 'payment']);
        Route::post('/cart-payment-zero', [CartController::class, 'zero_payment']);
        Route::get('/student-cart', [CartController::class, 'data']);
        Route::get('/cancel-payment/{id}', [PaymentController::class, 'cancel_payment']);
        Route::get('/waiting-payment/{id}', [PaymentController::class, 'waiting']);
        Route::get('/check-payment/{id}', [PaymentController::class, 'check_payment']);
        Route::get('/payment-success', [PaymentController::class, 'success']);
        Route::get('/redirect-payment-success', [PaymentController::class, 'redirect']);
    });

    include base_path('routes/admin.php');

    include base_path('routes/coach.php');

    include base_path('routes/student.php');

    include base_path('routes/public.php');
});

Route::get('redirect-blank', function () {
    return view('cms.transaction.payment-success.redirect');
});

Route::get('redirect-failed', function () {
    return view('cms.transaction.waiting-payment.failed');
});

Route::get('--version', function () {
    return '1.1.2';
});
