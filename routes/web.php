<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Global Controller
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ProviderController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Transaction\DokuController;
use App\Http\Controllers\Transaction\CartController;
use App\Http\Controllers\Transaction\PaymentController;

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
use App\Http\Controllers\Admin\Master\ProfileVideoCoachController;
use App\Http\Controllers\Admin\Transaction\StudentController as TransactionStudentController;
use App\Http\Controllers\Admin\Schedule\ScheduleController;
use App\Http\Controllers\Admin\Review\VideoController;
use App\Http\Controllers\Admin\Review\ClassController as ReviewClassController;
use App\Http\Controllers\Admin\ReportTransaction\TransactionStudentController as ReviewTransactionStudentController;

/*
|--------------------------------------------------------------------------
| Coach Controller
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Coach\DashboardController;
use App\Http\Controllers\Coach\TheoryController as CoachTheoryController;
use App\Http\Controllers\Coach\Schedule\ScheduleController as CoachScheduleController;
use App\Http\Controllers\Coach\Exercise\AssignmentController;
use App\Http\Controllers\Coach\Exercise\ReviewAssignmentController;
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
use App\Http\Controllers\Student\NotificationController as StudentNotificationController;


/*
|--------------------------------------------------------------------------
| CMS Admin Controller
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\Cms\CompanyController as CompanyController;
use App\Http\Controllers\Admin\Cms\BranchController as BranchController;
use App\Http\Controllers\Admin\Cms\ProgramController as ProgramController;
use App\Http\Controllers\Admin\Cms\EventController as EventController;
use App\Http\Controllers\Admin\Cms\NewsController as NewsController;
use App\Http\Controllers\Admin\Cms\PrivacyPolicyController as PrivacyPolicyAdminController;
use App\Http\Controllers\Admin\Cms\FaqController as FaqAdminController;
use App\Http\Controllers\Admin\Cms\TeamController as TeamController;
use App\Http\Controllers\Admin\Cms\CareerController as CareerAdminController;
use App\Http\Controllers\Admin\Cms\JobDescriptionController as JobDescriptionController;
use App\Http\Controllers\Admin\Cms\JobRequirementController as JobRequirementController;
use App\Http\Controllers\Admin\Cms\WorkingHourController as WorkingHourController;
use App\Http\Controllers\Admin\Cms\GaleryController as GaleryController;
use App\Http\Controllers\Admin\Cms\SocialMediaController as SocialMediaController;
use App\Http\Controllers\Admin\Cms\MarketPlaceController as MarketPlaceController;
use App\Http\Controllers\Admin\Cms\QuestionController as QuestionController;
use App\Http\Controllers\Admin\Cms\CoachReviewController as CoachReviewController;



/*
|--------------------------------------------------------------------------
| CMS Controller
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Cms\HomePageController as HomePageController;
use App\Http\Controllers\Cms\ClassController as ClassController;
use App\Http\Controllers\Cms\StoreController as StoreController;
use App\Http\Controllers\Cms\NewsEventController as NewsEventController;
use App\Http\Controllers\Cms\AboutController as AboutController;
use App\Http\Controllers\Cms\PrivacyPolicyController as PrivacyPolicyController;
use App\Http\Controllers\Cms\TosController as TosController;
use App\Http\Controllers\Cms\FaqController as FaqController;
use App\Http\Controllers\Cms\CareerController as CareerController;
use App\Http\Controllers\Cms\CareerDetailController as CareerDetailController;
use App\Http\Controllers\Cms\StoreDetailController as StoreDetailController;
use App\Http\Controllers\Cms\EventListController as EventListController;
use App\Http\Controllers\Cms\EventDetailController as EventDetailController;
use App\Http\Controllers\Cms\NewsListController as NewsListController;
use App\Http\Controllers\Cms\NewsDetailController as NewsDetailController;




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
Route::get('/class/classroom_category/{category_id}/sub_classroom_category', [ClassController::class, 'get_sub_category']);
Route::get('/classroom_category/sub_classroom_category/{sub_category_id}/classroom', [ClassController::class, 'get_classroom']);
Route::get('/class/{classroom_category_id}/sub_classroom_category/{sub_classroom_category_id}/package/{package}', [ClassController::class, 'get_package']);
Route::get('/class/{classroom_id}/coachs', [ClassController::class, 'get_coach']);
Route::get('/class/{classroom_id}/tools', [ClassController::class, 'get_tools']);
Route::get('/class/{classroom_id}/description', [ClassController::class, 'get_description']);
Route::get('/master-lesson/{master_lesson_id}/guest-star', [ClassController::class, 'get_guest_start']);
Route::get('/master-lesson/{master_lesson_id}/detail', [ClassController::class, 'get_master_lesson']);


Route::get('/store', [StoreController::class, 'index']);
Route::post('/store/search', [StoreController::class, 'search']);
Route::get('/video-course/{video_course_id}/detail', [StoreDetailController::class, 'index']);
Route::get('/classroom_category/{category_id}/sub_classroom_category', [StoreController::class, 'get_sub_category']);
Route::get('/classroom_category/sub_classroom_category/{sub_category_id}/video-course', [StoreController::class, 'get_video_courses']);



Route::get('/news-event', [NewsEventController::class, 'index']);
Route::get('/event-list', [EventListController::class, 'index']);
Route::get('/event/{event_id}/detail', [EventDetailController::class, 'index']);

Route::get('/news-list',[NewsListController::class, 'index']);
Route::post('/news-list/search', [NewsListController::class, 'search']);
Route::get('/news/{news_id}/detail',[NewsDetailController::class, 'index']);

Route::get('/about', [AboutController::class, 'index']);
Route::get('/privacy-policy', [PrivacyPolicyController::class, 'index']);
Route::get('/tos', [TosController::class, 'index']);


Route::get('/faq', [FaqController::class, 'index']);
Route::post('/question',[QuestionController::class,'store']);

Route::get('/career', [CareerController::class, 'index']);
Route::get('/career/{id}/detail', [CareerDetailController::class, 'index']);
Route::post('/notifications/payments', [PaymentController::class, 'notification']);

Route::group(['middleware' => ['guest-handling']], function () {

    Route::get('email-verification/check/{token}',[RegisterController::class, 'email_verification']);

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
        Route::get('/student-cart', [CartController::class, 'data']);
        Route::get('/cancel-payment/{id}', [PaymentController::class, 'cancel_payment']);
        Route::get('/waiting-payment/{id}', [PaymentController::class, 'waiting']);
        Route::get('/payment-success', [PaymentController::class, 'success']);
        Route::get('/redirect-payment-success', [PaymentController::class, 'redirect']);
    });

    /*
    |--------------------------------------------------------------------------
    | Admins Route
    |--------------------------------------------------------------------------
    */

    Route::group(['prefix' => 'admin', 'middleware' => 'admin-handling'], function () {
        Route::get('dashboard', [AdminDashboard::class, 'index']);
        Route::get('cart-dashboard', [AdminDashboard::class, 'cart_data']);

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

                Route::get('master-lesson/guest-star/{id}', [MasterLessonController::class, 'get_guest_star']);
                Route::delete('master-lesson/guest-star/{id}', [MasterLessonController::class, 'destroy_guest_star']);
                Route::post('master-lesson/update/{id}', [MasterLessonController::class, 'update']);
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
            Route::post('student/actived/{id}', [StudentController::class, 'actived']);
            Route::post('student/verified/{id}', [StudentController::class, 'verified']);
            Route::resource('student', StudentController::class);

            Route::post('media-conference/dt', [PlatformController::class, 'dt']);
            Route::post('media-conference/update/{id}', [PlatformController::class, 'update']);
            Route::resource('media-conference', PlatformController::class);

            Route::get('theory', [TheoryController::class, 'index']);
            Route::post('theory', [TheoryController::class, 'store']);
            Route::delete('theory/{id}', [TheoryController::class, 'destroy']);
            Route::post('theory/dt', [TheoryController::class, 'dt']);
            Route::post('theory/update/{id}', [TheoryController::class, 'update']);

            Route::post('profile-video-coach/dt', [ProfileVideoCoachController::class, 'dt']);
            Route::post('profile-video-coach/update/{id}', [ProfileVideoCoachController::class, 'update']);
            Route::resource('profile-video-coach', ProfileVideoCoachController::class);

            Route::post('expertise/dt', [ExpertiseController::class, 'dt']);
            Route::resource('expertise', ExpertiseController::class);
        });

        Route::group(['prefix' => 'report'], function () {
            Route::group(['prefix' => 'review'], function () {
                Route::group(['prefix' => 'video'], function () {
                    Route::get('/', [VideoController::class, 'index']);
                    Route::get('{id}', [VideoController::class, 'detail']);
                    Route::post('dt', [VideoController::class, 'dt']);
                    Route::post('dt/{id}', [VideoController::class, 'dt_detail']);
                    Route::post('dt/{id}', [VideoController::class, 'dt_detail']);
                    Route::post('print-excel/{id}', [VideoController::class, 'print_excel']);
                    Route::post('print-pdf/{id}', [VideoController::class, 'print_pdf']);
                });

                Route::group(['prefix' => 'class'], function () {
                    Route::get('/', [ReviewClassController::class, 'index']);
                    Route::get('{id}', [ReviewClassController::class, 'detail']);
                    Route::post('dt', [ReviewClassController::class, 'dt']);
                    Route::post('dt/{id}', [ReviewClassController::class, 'dt_detail']);
                    Route::post('print-excel/{id}', [ReviewClassController::class, 'print_excel']);
                    Route::post('print-pdf/{id}', [ReviewClassController::class, 'print_pdf']);
                });
            });

            Route::group(['prefix' => 'transaction'], function () {
                Route::group(['prefix' => 'student'], function () {
                    Route::get('/', [ReviewTransactionStudentController::class, 'index']);
                    Route::post('dt', [ReviewTransactionStudentController::class, 'dt']);
                });
            });
        });

        Route::group(['prefix' => 'transaction'], function () {
            Route::get('student', [TransactionStudentController::class, 'index']);
            Route::post('student/dt', [TransactionStudentController::class, 'dt']);
            Route::get('student/detail/{id}', [TransactionStudentController::class, 'detail']);
        });

        Route::get('schedule/master-lesson/{id}', [ScheduleController::class, 'show_master_lesson']);
        Route::post('schedule/master-lesson/update/{id}', [ScheduleController::class, 'update_time_master_lesson']);

        Route::get('schedule', [ScheduleController::class, 'index']);
        Route::get('schedule/all', [ScheduleController::class, 'all']);
        Route::get('schedule/{id}', [ScheduleController::class, 'show']);
        Route::get('schedule-show/{id}', [ScheduleController::class, 'show_edit']);
        Route::post('schedule', [ScheduleController::class, 'store']);
        Route::post('schedule/{id}', [ScheduleController::class, 'update']);
        Route::post('schedule/update/{id}', [ScheduleController::class, 'update_time']);
        Route::post('schedule/confirm/{id}', [ScheduleController::class, 'confirm']);
        Route::post('schedule/delete/{id}', [ScheduleController::class, 'delete']);

        Route::group(['prefix' => 'report'], function () {

            Route::group(['prefix' => 'transaction'], function () {
                Route::get('student', [ScheduleController::class, 'index']);
                Route::post('student/excel', [TransactionStudentController::class, 'excel']);
                Route::post('student/dt', [TransactionStudentController::class, 'dt']);
            });
        });

        Route::group(['prefix' => 'cms'], function () {
            Route::post('company/dt', [CompanyController::class, 'dt']);
            Route::resource('company', CompanyController::class);

            Route::post('branch/dt', [BranchController::class, 'dt']);
            Route::resource('branch', BranchController::class);

            Route::post('program/dt', [ProgramController::class, 'dt']);
            Route::post('program/update/{id}', [ProgramController::class, 'update']);
            Route::resource('program', ProgramController::class);

            Route::post('event/dt', [EventController::class, 'dt']);
            Route::post('event/update/{id}', [EventController::class, 'update']);
            Route::resource('event', EventController::class);

            Route::post('news/dt', [NewsController::class, 'dt']);
            Route::post('news/update/{id}', [NewsController::class, 'update']);
            Route::resource('news', NewsController::class);

            Route::post('privacy-policy/dt', [PrivacyPolicyAdminController::class, 'dt']);
            Route::post('privacy-policy-item/dt', [PrivacyPolicyAdminController::class, 'dt_item']);
            Route::put('privacy-policy-item/{id}', [PrivacyPolicyAdminController::class, 'update_item']);
            Route::resource('privacy-policy', PrivacyPolicyAdminController::class);

            Route::post('faq/dt', [FaqAdminController::class, 'dt']);
            Route::resource('faq', FaqAdminController::class);

            Route::post('team/dt', [TeamController::class, 'dt']);
            Route::post('team/update/{id}', [TeamController::class, 'update']);
            Route::resource('team', TeamController::class);

            Route::post('career/dt', [CareerAdminController::class, 'dt']);
            Route::resource('career', CareerAdminController::class);

            Route::post('career/{career_id}/job-description/dt', [JobDescriptionController::class, 'dt']);
            Route::resource('job-description', JobDescriptionController::class);

            Route::post('working-hour/dt', [WorkingHourController::class, 'dt']);
            Route::resource('working-hour', WorkingHourController::class);

            Route::post('galery/dt', [GaleryController::class, 'dt']);
            Route::post('galery/update/{id}', [GaleryController::class, 'update']);
            Route::resource('galery', GaleryController::class);

            Route::post('social-media/dt', [SocialMediaController::class, 'dt']);
            Route::post('social-media/update/{id}', [SocialMediaController::class, 'update']);
            Route::resource('social-media', SocialMediaController::class);

            Route::post('marketplace/dt', [MarketPlaceController::class, 'dt']);
            Route::post('marketplace/update/{id}', [MarketPlaceController::class, 'update']);
            Route::resource('marketplace', MarketPlaceController::class);

            Route::post('question/dt', [QuestionController::class, 'dt']);
            Route::resource('question', QuestionController::class);

            Route::post('coach-review/dt', [CoachReviewController::class, 'dt']);
            Route::post('coach-review/coach_dt', [CoachReviewController::class, 'coach_dt']);
            Route::post('coach-review/{coach_id}', [CoachReviewController::class, 'store']);
            Route::resource('coach-review', CoachReviewController::class);
            

        });
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
        Route::get('theory/download/{id}', [CoachTheoryController::class, 'theory_download']);
        Route::resource('theory', CoachTheoryController::class);

        Route::group(['prefix' => 'exercise'], function () {

            Route::post('assignment/file', [AssignmentController::class, 'assignment_file']);
            Route::delete('assignment/file/{id}', [AssignmentController::class, 'assignment_file_delete']);
            Route::get('assignment/list/{classroom_id}/{session_id}', [AssignmentController::class, 'assignment_list']);
            Route::get('assignment/download/{id}', [AssignmentController::class, 'assignment_download']);
            Route::resource('assignment', AssignmentController::class);

            Route::post('review-assignment/file', [ReviewAssignmentController::class, 'assignment_file']);
            Route::delete('review-assignment/file/{id}', [ReviewAssignmentController::class, 'assignment_file_delete']);
            Route::get('review-assignment/list/{classroom_id}/{session_id}', [ReviewAssignmentController::class, 'assignment_list']);
            Route::get('review-assignment/download/{id}', [ReviewAssignmentController::class, 'assignment_download']);
            Route::post('review-assignment/dt/{classroom_id}/{session_id}', [ReviewAssignmentController::class, 'dt']);
            Route::resource('review-assignment', ReviewAssignmentController::class);

        });

        Route::get('schedule/master-lesson/{id}', [CoachScheduleController::class, 'show_master_lesson']);
        Route::post('schedule/master-lesson/update/{id}', [CoachScheduleController::class, 'update_time_master_lesson']);

        Route::get('schedule', [CoachScheduleController::class, 'index']);
        Route::get('schedule/all', [CoachScheduleController::class, 'all']);
        Route::get('schedule/{id}', [CoachScheduleController::class, 'show']);
        Route::get('schedule-show/{id}', [CoachScheduleController::class, 'show_edit']);
        Route::post('schedule', [CoachScheduleController::class, 'store']);
        Route::post('schedule/{id}', [CoachScheduleController::class, 'update']);
        Route::post('schedule/update/{id}', [CoachScheduleController::class, 'update_time']);
        Route::post('schedule/confirm/{id}', [CoachScheduleController::class, 'confirm']);
        Route::post('schedule/delete/{id}', [CoachScheduleController::class, 'delete']);

    });

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
            Route::get('student-booking-week', [StudentDashboardController::class, 'student_booking_week']);
            Route::get('my-course', [StudentDashboardController::class, 'my_course']);
            Route::get('progress-class', [StudentDashboardController::class, 'progress_class']);
            Route::get('summary-course', [StudentDashboardController::class, 'summary_course']);
        });

        Route::group(['prefix' => 'invoice'], function () {
            Route::get('/', [StudentInvoiceController::class, 'index']);
            Route::get('dt', [StudentInvoiceController::class, 'dt']);
            Route::get('detail/{id}', [StudentInvoiceController::class, 'detail']);
        });

        Route::group(['prefix' => 'schedule'], function () {
            Route::get('/', [StudentScheduleController::class, 'index']);
            Route::get('get-total-class/{student_id}', [StudentScheduleController::class, 'get_total_class']);
            Route::get('student-rating', [StudentScheduleController::class, 'student_rating']);

            Route::group(['prefix' => 'regular-class'], function () {
                Route::get('/', [StudentScheduleController::class, 'regular_class']);
                Route::get('{coach_schedule_id}', [StudentScheduleController::class, 'coach_schedule']);
                Route::post('booking', [StudentScheduleController::class, 'booking']);
                Route::post('reschedule', [StudentScheduleController::class, 'reschedule']);
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
            });
        });

        Route::group(['prefix' => 'my-class'], function () {
            Route::get('/',[StudentMyClassController::class, 'index']);
            Route::post('booking/dt',[StudentMyClassController::class, 'booking_dt']);
            Route::post('rating',[StudentMyClassController::class, 'rating']);
            Route::post('last-class/dt',[StudentMyClassController::class, 'last_class_dt']);
            Route::post('review/{id}',[StudentMyClassController::class, 'review']);
            Route::put('reschedule/{id}',[StudentMyClassController::class, 'reschedule']);
            Route::get('class-active/{id}',[StudentMyClassController::class, 'class_active']);
            Route::get('checkin/{id}',[StudentMyClassController::class, 'checkin']);
            Route::get('get-review/{id}',[StudentMyClassController::class, 'get_review']);
        });

        Route::group(['prefix' => 'new-package'], function () {
            Route::get('/',[StudentNewPackageController::class, 'index']);
            Route::get('get-package',[StudentNewPackageController::class, 'get_package']);
            Route::get('get-sub-classroom-category', [StudentNewPackageController::class, 'get_sub_classroom_category']);
            Route::get('sub-classroom-category/{sub_classroom_category_id}',[StudentNewPackageController::class, 'get_classroom_by_sub_category_id']);
            Route::get('get-session-video', [StudentNewPackageController::class, 'get_session_video']);
            Route::get('classroom-category/{classroom_category_id}',[StudentNewPackageController::class, 'get_classroom_by_category_id']);
        });

        Route::group(['prefix' => 'theory'], function () {
            Route::group(['prefix' => 'theory-class'], function () {
                Route::get('/', [StudentTheoryController::class, 'index']);
                Route::get('get-theory', [StudentTheoryController::class, 'get_theory']);
                Route::get('get-class/{student_id}', [StudentTheoryController::class, 'get_class']);
                Route::get('filter_theory', [StudentTheoryController::class, 'filter_theory']);
            });

            Route::group(['prefix' => 'video-class'], function () {
                Route::get('/', [StudentVideoController::class, 'index']);
                Route::get('get-video', [StudentVideoController::class, 'get_video']);
                Route::get('video-detail/{session_video_id}', [StudentVideoController::class, 'video_detail']);
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
        });

        Route::group(['prefix' => 'notification'], function () {
            Route::get('/', [StudentNotificationController::class, 'index']);
            Route::post('dt', [StudentNotificationController::class, 'dt']);
        });

        Route::post('profile/{id}', [StudentProfileController::class,'update']);
        Route::post('profile/change-password/{id}', [StudentProfileController::class,'change_password']);
        Route::get('profile', [StudentProfileController::class,'index']);

        Route::get('package-detail/{session_video_id}',[StudentPackageDetailController::class, 'index']);

        Route::post('add-to-cart',[StudentCartController::class, 'store']);
        Route::get('get-cart/{student_id}',[StudentCartController::class, 'get_cart']);
        Route::delete('delete-cart/{cart_id}',[StudentCartController::class, 'delete_cart']);

        Route::post('/event/{event_id}/add-to-cart',[StudentCartController::class, 'event']);
        Route::get('/class/{classroom_id}/detail', [ClassController::class, 'detail']);
        Route::post('/class/add-to-cart', [ClassController::class, 'store']);
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
        Route::get('get-guest-star', [PublicController::class, 'get_guest_star']);
    });
});

Route::get('fire', function () {
    event(new \App\Events\PaymentNotification(true));
    return 'oke';
});

Route::get('welcome', function () {
    return view('welcome');
});

Route::get('video', function () {
    return view('video');
});
