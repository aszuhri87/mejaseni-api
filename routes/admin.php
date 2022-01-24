<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admins Controller
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Transaction\CartController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\EventController as EventController;
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
use App\Http\Controllers\Admin\Transaction\CoachController as TransactionCoachController;
use App\Http\Controllers\Admin\Schedule\ScheduleController;
use App\Http\Controllers\Admin\Schedule\ListController;
use App\Http\Controllers\Admin\Schedule\RequestScheduleController;
use App\Http\Controllers\Admin\Reporting\Review\Coach\CoachController as AdminCoachController;
use App\Http\Controllers\Admin\Reporting\Review\Coach\Detail\CoachDetailController;
use App\Http\Controllers\Admin\Reporting\Review\Student\StudentController as AdminStudentController;
use App\Http\Controllers\Admin\Reporting\Review\Student\Detail\StudentDetailController;
use App\Http\Controllers\Admin\Report\Transaction\CoachController as ReportTransactionCoachController;
use App\Http\Controllers\Admin\Career\CareerController as CareerAdminController;
use App\Http\Controllers\Admin\Career\JobDescriptionController as JobDescriptionController;
use App\Http\Controllers\Admin\Career\JobRequirementController as JobRequirementController;

use App\Http\Controllers\Admin\Review\VideoController;
use App\Http\Controllers\Admin\Review\ClassController as ReviewClassController;
use App\Http\Controllers\Admin\ReportTransaction\TransactionStudentController as ReviewTransactionStudentController;

/*
|--------------------------------------------------------------------------
| CMS Admin Controller
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\Cms\CompanyController as CompanyController;
use App\Http\Controllers\Admin\Cms\BranchController as BranchController;
use App\Http\Controllers\Admin\Cms\ProgramController as ProgramController;
use App\Http\Controllers\Admin\Cms\NewsController as NewsController;
use App\Http\Controllers\Admin\Cms\PrivacyPolicyController as PrivacyPolicyAdminController;
use App\Http\Controllers\Admin\Cms\FaqController as FaqAdminController;
use App\Http\Controllers\Admin\Cms\TeamController as TeamController;
use App\Http\Controllers\Admin\Cms\WorkingHourController as WorkingHourController;
use App\Http\Controllers\Admin\Cms\GaleryController as GaleryController;
use App\Http\Controllers\Admin\Cms\SocialMediaController as SocialMediaController;
use App\Http\Controllers\Admin\Cms\MarketPlaceController as MarketPlaceController;
use App\Http\Controllers\Admin\Cms\QuestionController as QuestionController;
use App\Http\Controllers\Admin\Cms\CoachReviewController as CoachReviewController;
use App\Http\Controllers\Admin\Cms\BannerController as BannerController;
use App\Http\Controllers\Admin\Cms\FunCreativeController as FunCreativeController;
use App\Http\Controllers\Admin\Cms\ImageGaleryController as ImageGaleryController;
use App\Http\Controllers\Admin\Cms\ReasonController as ReasonController;
use App\Http\Controllers\Admin\Cms\StoreBannerController as StoreBannerController;
use App\Http\Controllers\Admin\Cms\PassionController as PassionController;
use App\Http\Controllers\Admin\Cms\TutorialVideoController as TutorialVideoController;
use App\Http\Controllers\Admin\Cms\ClassController as ClassRoomReviewController;


/*
|--------------------------------------------------------------------------
| Admins Route
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'admin', 'middleware' => 'admin-handling'], function () {
    Route::get('dashboard', [AdminDashboard::class, 'index']);
    Route::get('cart-dashboard', [AdminDashboard::class, 'cart_data']);

    Route::group(['prefix' => 'master','middleware' => 'can:master_data'], function () {
        Route::group(['prefix' => 'courses','middleware' => 'can:courses'], function () {
            Route::post('package/dt', [PackageController::class, 'dt']);
            Route::resource('package', PackageController::class);

            Route::group(['middleware' => 'can:category_class'], function () {
                Route::post('classroom-category/dt', [ClassroomCategoryController::class, 'dt']);
                Route::post('classroom-category/update/{id}', [ClassroomCategoryController::class, 'update']);
                Route::resource('classroom-category', ClassroomCategoryController::class);
            });

            Route::group(['middleware' => 'can:sub_category_class'], function () {
                Route::post('sub-classroom-category/dt', [SubClassroomCategoryController::class, 'dt']);
                Route::post('sub-classroom-category/update/{id}', [SubClassroomCategoryController::class, 'update']);
                Route::resource('sub-classroom-category', SubClassroomCategoryController::class);
            });

            Route::group(['middleware' => 'can:class'], function () {
                Route::get('classroom/tools/ac', [ClassroomController::class, 'ac']);
                Route::get('classroom/tools/{id}', [ClassroomController::class, 'get_tools']);
                Route::delete('classroom/tools/{id}', [ClassroomController::class, 'delete_tools']);
                Route::post('classroom/dt', [ClassroomController::class, 'dt']);
                Route::post('classroom/update/{id}', [ClassroomController::class, 'update']);
                Route::resource('classroom', ClassroomController::class);
            });

            Route::group(['middleware' => 'can:video'], function () {
                Route::post('session-video/detail/dt/{id}', [TheoryVideoController::class, 'dt']);
                Route::post('session-video/detail/update/{id}', [TheoryVideoController::class, 'update']);
                Route::post('session-video/detail/store', [TheoryVideoController::class, 'store']);
                Route::delete('session-video/detail/{id}', [TheoryVideoController::class, 'destroy']);

                Route::post('session-video/file/dt/{id}', [TheoryVideoController::class, 'file_dt']);
                Route::post('session-video/file/update/{id}', [TheoryVideoController::class, 'file_update']);
                Route::post('session-video/file/store', [TheoryVideoController::class, 'file_store']);
                Route::delete('session-video/file/{id}', [TheoryVideoController::class, 'file_destroy']);

                Route::post('session-video/dt', [SessionVideoController::class, 'dt']);
                Route::post('session-video/update/{id}', [SessionVideoController::class, 'update']);
                Route::resource('session-video', SessionVideoController::class);
            });

            Route::group(['middleware' => 'can:master_lesson'], function () {
                Route::get('master-lesson/guest-star/{id}', [MasterLessonController::class, 'get_guest_star']);
                Route::delete('master-lesson/guest-star/{id}', [MasterLessonController::class, 'destroy_guest_star']);
                Route::post('master-lesson/update/{id}', [MasterLessonController::class, 'update']);
                Route::post('master-lesson/dt', [MasterLessonController::class, 'dt']);
                Route::resource('master-lesson', MasterLessonController::class);
            });
        });

        Route::group(['middleware' => 'can:guest_star'], function () {
            Route::post('guest-star/dt', [GuestStarController::class, 'dt']);
            Route::resource('guest-star', GuestStarController::class);
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

            Route::group(['prefix' => 'view-list'], function () {
                Route::get('get-class-by-coach/{id}', [CoachListController::class, 'get_class']);
                Route::get('{id}', [CoachListController::class, 'index']);
                Route::post('store', [CoachListController::class, 'store']);
            });
        });

        Route::group(['middleware' => 'can:coach'], function () {
            Route::post('coach/dt', [CoachController::class, 'dt']);
            Route::resource('coach', CoachController::class);
        });

        Route::group(['middleware' => 'can:admin'], function () {
            Route::post('admin/dt', [AdminController::class, 'dt']);
            Route::get('admin/permission/{id}', [AdminController::class, 'get_permission']);
            Route::post('admin/permission/{id}', [AdminController::class, 'set_permission']);
            Route::resource('admin', AdminController::class);
        });

        Route::group(['middleware' => 'can:student'], function () {
            Route::post('student/dt', [StudentController::class, 'dt']);
            Route::post('student/update/{id}', [StudentController::class, 'update']);
            Route::post('student/actived/{id}', [StudentController::class, 'actived']);
            Route::post('student/verified/{id}', [StudentController::class, 'verified']);
            Route::resource('student', StudentController::class);
        });

        Route::group(['middleware' => 'can:media_conference'], function () {
            Route::post('media-conference/dt', [PlatformController::class, 'dt']);
            Route::post('media-conference/update/{id}', [PlatformController::class, 'update']);
            Route::resource('media-conference', PlatformController::class);
        });

        Route::group(['middleware' => 'can:theory'], function () {
            Route::get('theory', [TheoryController::class, 'index']);
            Route::post('theory', [TheoryController::class, 'store']);
            Route::delete('theory/{id}', [TheoryController::class, 'destroy']);
            Route::post('theory/dt', [TheoryController::class, 'dt']);
            Route::post('theory/update/{id}', [TheoryController::class, 'update']);
        });

        Route::group(['middleware' => 'can:profile_video_coach'], function () {
            Route::post('profile-video-coach/dt', [ProfileVideoCoachController::class, 'dt']);
            Route::post('profile-video-coach/update/{id}', [ProfileVideoCoachController::class, 'update']);
            Route::resource('profile-video-coach', ProfileVideoCoachController::class);
        });

        Route::group(['middleware' => 'can:expertise'], function () {
            Route::post('expertise/dt', [ExpertiseController::class, 'dt']);
            Route::resource('expertise', ExpertiseController::class);
        });
    });

    Route::group(['prefix' => 'report','middleware' => 'can:reporting'], function () {
        Route::group(['prefix' => 'review','middleware' => 'can:reporting_review'], function () {
            Route::group(['prefix' => 'video','middleware' => 'can:reporting_review_video'], function () {
                Route::get('/', [VideoController::class, 'index']);
                Route::get('{id}', [VideoController::class, 'detail']);
                Route::post('dt', [VideoController::class, 'dt']);
                Route::post('dt/{id}', [VideoController::class, 'dt_detail']);
                Route::post('dt/{id}', [VideoController::class, 'dt_detail']);
                Route::post('print-excel/{id}', [VideoController::class, 'print_excel']);
                Route::post('print-pdf/{id}', [VideoController::class, 'print_pdf']);
            });

            Route::group(['prefix' => 'class','middleware' => 'can:reporting_review_class'], function () {
                Route::get('/', [ReviewClassController::class, 'index']);
                Route::get('{id}', [ReviewClassController::class, 'detail']);
                Route::post('dt', [ReviewClassController::class, 'dt']);
                Route::post('dt/{id}', [ReviewClassController::class, 'dt_detail']);
                Route::post('print-excel/{id}', [ReviewClassController::class, 'print_excel']);
                Route::post('print-pdf/{id}', [ReviewClassController::class, 'print_pdf']);
            });
        });

        Route::group(['prefix' => 'transaction','middleware' => 'can:reporting_transaction'], function () {
            Route::group(['prefix' => 'coach','middleware' => 'can:reporting_transaction_coach'], function () {
                Route::get('/', [ReportTransactionCoachController::class, 'index']);
                Route::post('dt', [ReportTransactionCoachController::class, 'dt']);
                Route::post('print-pdf', [ReportTransactionCoachController::class, 'print_pdf']);
                Route::post('print-excel', [ReportTransactionCoachController::class, 'print_excel']);
            });
        });

        Route::group(['prefix' => 'transaction-report','middleware' => 'can:reporting_transaction'], function () {
            Route::group(['prefix' => 'student','middleware' => 'can:reporting_transaction_student'], function () {
                Route::get('/', [ReviewTransactionStudentController::class, 'index']);
                Route::post('dt', [ReviewTransactionStudentController::class, 'dt']);
                Route::post('print-pdf', [ReviewTransactionStudentController::class, 'print_pdf']);
                Route::post('print-excel', [ReviewTransactionStudentController::class, 'print_excel']);
            });
        });
    });

    Route::group(['middleware' => 'can:data_transaction'], function () {
        Route::group(['prefix' => 'transaction'], function () {
            Route::group(['middleware' => 'can:data_transaction_coach'], function () {
                Route::get('coach/export/excel', [TransactionCoachController::class, 'export']);
                Route::get('coach', [TransactionCoachController::class, 'index']);
                Route::post('coach/dt', [TransactionCoachController::class, 'dt']);
                Route::post('coach/confirm/{id}', [TransactionCoachController::class, 'confirm']);
                Route::post('coach/approve/{id}', [TransactionCoachController::class, 'approve']);
            });

            Route::group(['middleware' => 'can:data_transaction_student'], function () {
                Route::get('student', [TransactionStudentController::class, 'index']);
                Route::post('student/dt', [TransactionStudentController::class, 'dt']);
                Route::get('student/detail/{id}', [TransactionStudentController::class, 'detail']);
            });
        });
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

    Route::get('schedule-list', [ListController::class, 'index']);
    Route::post('schedule-list/dt', [ListController::class, 'dt']);

    Route::get('schedule-request/list', [RequestScheduleController::class, 'request_list']);
    Route::post('schedule-request/dt', [RequestScheduleController::class, 'dt']);
    Route::resource('schedule-request', RequestScheduleController::class);

    Route::post('event/dt', [EventController::class, 'dt']);
    Route::post('event/{id}/participants/dt',[EventController::class, 'participants_dt']);
    Route::delete('cart/{id}', [CartController::class, 'destroy']);
    Route::post('event/update/{id}', [EventController::class, 'update']);
    Route::resource('event', EventController::class);

    Route::group(['prefix' => 'report','middleware' => 'can:reporting'], function () {

        Route::group(['prefix' => 'review', 'middleware' => 'can:reporting_review'], function () {

            Route::group(['prefix' => 'coach','middleware' => 'can:reporting_review_coach'], function () {
                Route::get('/', [AdminCoachController::class, 'index']);
                Route::post('dt', [AdminCoachController::class, 'dt']);
                Route::get('admin/report/review/coach/details/{id}', [AdminCoachController::class, 'dt']);
                Route::group(['prefix' => 'detail'], function () {
                    Route::get('/{id}', [CoachDetailController::class, 'index']);
                    Route::post('dt/{id}', [CoachDetailController::class, 'dt']);
                    Route::get('get-classroom/{id}', [CoachDetailController::class, 'get_classrooms']);
                });
            });

            Route::group(['prefix' => 'student','middleware' => 'can:reporting_review_student'], function () {
                Route::get('/', [AdminStudentController::class, 'index']);
                Route::post('dt', [AdminStudentController::class, 'dt']);
                Route::group(['prefix' => 'detail'], function () {
                    Route::get('/{id}', [StudentDetailController::class, 'index']);
                    Route::post('dt/{id}', [StudentDetailController::class, 'dt']);
                    Route::get('get-classroom/{id}', [StudentDetailController::class, 'get_classrooms']);
                });
            });

        });

        Route::group(['prefix' => 'transaction'], function () {
            Route::get('student', [ScheduleController::class, 'index']);
            Route::post('student/excel', [TransactionStudentController::class, 'excel']);
            Route::post('student/dt', [TransactionStudentController::class, 'dt']);
        });
    });

    Route::post('event/dt', [EventController::class, 'dt']);
    Route::post('event/{id}/participants/dt',[EventController::class, 'participants_dt']);
    Route::post('event/update/{id}', [EventController::class, 'update']);
    Route::resource('event', EventController::class);

    Route::delete('cart/{id}', [CartController::class, 'destroy']);
    Route::post('career-detail/dt', [CareerAdminController::class, 'dt_detail']);
    Route::get('career-detail/{id}', [CareerAdminController::class, 'show_detail']);

    Route::post('career/dt', [CareerAdminController::class, 'dt']);
    Route::get('/career-detail/{id}', [CareerAdminController::class, 'show_detail']);
    Route::resource('career', CareerAdminController::class);

    Route::post('career/{career_id}/job-description/dt', [JobDescriptionController::class, 'dt']);
    Route::resource('job-description', JobDescriptionController::class);

    Route::post('career/{career_id}/job-requirement/dt', [JobRequirementController::class, 'dt']);
    Route::resource('job-requirement', JobRequirementController::class);

    Route::group(['prefix' => 'cms'], function () {
        Route::post('company/dt', [CompanyController::class, 'dt']);
        Route::resource('company', CompanyController::class);

        Route::post('branch/dt', [BranchController::class, 'dt']);
        Route::resource('branch', BranchController::class);

        Route::post('program/dt', [ProgramController::class, 'dt']);
        Route::post('program/update/{id}', [ProgramController::class, 'update']);
        Route::resource('program', ProgramController::class);

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
        Route::post('question/reply',[QuestionController::class, 'reply']);
        Route::resource('question', QuestionController::class);

        Route::post('coach-review/dt', [CoachReviewController::class, 'dt']);
        Route::post('coach-review/coach_dt', [CoachReviewController::class, 'coach_dt']);
        Route::post('coach-review/{coach_id}', [CoachReviewController::class, 'store']);
        Route::resource('coach-review', CoachReviewController::class);

        Route::post('banner/dt', [BannerController::class, 'dt']);
        Route::post('banner/update/{id}', [BannerController::class, 'update']);
        Route::resource('banner', BannerController::class);

        Route::post('fun-creative/dt', [FunCreativeController::class, 'dt']);
        Route::post('fun-creative/update/{id}', [FunCreativeController::class, 'update']);
        Route::resource('fun-creative', FunCreativeController::class);


        Route::post('galery-home/dt', [ImageGaleryController::class, 'dt']);
        Route::post('galery-home/update/{id}', [ImageGaleryController::class, 'update']);
        Route::resource('galery-home', ImageGaleryController::class);

        Route::post('reason/dt', [ReasonController::class, 'dt']);
        Route::post('reason/update/{id}', [ReasonController::class, 'update']);
        Route::resource('reason', ReasonController::class);

        Route::post('store-banner/dt', [StoreBannerController::class, 'dt']);
        Route::post('store-banner/update/{id}', [StoreBannerController::class, 'update']);
        Route::resource('store-banner', StoreBannerController::class);

        Route::post('passion/dt', [PassionController::class, 'dt']);
        Route::resource('passion', PassionController::class);

        Route::post('tutorial-video/dt', [TutorialVideoController::class, 'dt']);
        Route::post('tutorial-video/update/{id}', [TutorialVideoController::class, 'update']);
        Route::resource('tutorial-video', TutorialVideoController::class);

        Route::post('class-preview/dt', [ClassRoomReviewController::class, 'dt']);
        Route::get('class-preview/{category_id}/get-classroom', [ClassRoomReviewController::class, 'get_classrooms']);
        Route::resource('class-preview', ClassRoomReviewController::class);

    });
});
