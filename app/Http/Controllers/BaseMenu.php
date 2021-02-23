<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseMenu extends Controller
{
    public function icon($name)
    {
        $icons = [
            "dashboard" => '<span class="svg-icon menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24" />
                            <path d="M2.56066017,10.6819805 L4.68198052,8.56066017 C5.26776695,7.97487373 6.21751442,7.97487373 6.80330086,8.56066017 L8.9246212,10.6819805 C9.51040764,11.267767 9.51040764,12.2175144 8.9246212,12.8033009 L6.80330086,14.9246212 C6.21751442,15.5104076 5.26776695,15.5104076 4.68198052,14.9246212 L2.56066017,12.8033009 C1.97487373,12.2175144 1.97487373,11.267767 2.56066017,10.6819805 Z M14.5606602,10.6819805 L16.6819805,8.56066017 C17.267767,7.97487373 18.2175144,7.97487373 18.8033009,8.56066017 L20.9246212,10.6819805 C21.5104076,11.267767 21.5104076,12.2175144 20.9246212,12.8033009 L18.8033009,14.9246212 C18.2175144,15.5104076 17.267767,15.5104076 16.6819805,14.9246212 L14.5606602,12.8033009 C13.9748737,12.2175144 13.9748737,11.267767 14.5606602,10.6819805 Z" fill="#000000" opacity="0.3" />
                            <path d="M8.56066017,16.6819805 L10.6819805,14.5606602 C11.267767,13.9748737 12.2175144,13.9748737 12.8033009,14.5606602 L14.9246212,16.6819805 C15.5104076,17.267767 15.5104076,18.2175144 14.9246212,18.8033009 L12.8033009,20.9246212 C12.2175144,21.5104076 11.267767,21.5104076 10.6819805,20.9246212 L8.56066017,18.8033009 C7.97487373,18.2175144 7.97487373,17.267767 8.56066017,16.6819805 Z M8.56066017,4.68198052 L10.6819805,2.56066017 C11.267767,1.97487373 12.2175144,1.97487373 12.8033009,2.56066017 L14.9246212,4.68198052 C15.5104076,5.26776695 15.5104076,6.21751442 14.9246212,6.80330086 L12.8033009,8.9246212 C12.2175144,9.51040764 11.267767,9.51040764 10.6819805,8.9246212 L8.56066017,6.80330086 C7.97487373,6.21751442 7.97487373,5.26776695 8.56066017,4.68198052 Z" fill="#000000" />
                        </g>
                    </svg>
                </span>',

            "master" => '<span class="svg-icon menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon points="0 0 24 0 24 24 0 24" />
                            <path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero" />
                            <path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3" />
                        </g>
                    </svg>
                </span>',

            "schedule" => '<span class="svg-icon menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24"/>
                        <rect fill="#000000" x="4" y="4" width="7" height="7" rx="1.5"/>
                        <path d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z" fill="#000000" opacity="0.3"/>
                    </g>
                    </svg>
                </span>',

            "theory" => '<span class="svg-icon menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24"/>
                        <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                        <rect fill="#000000" x="6" y="11" width="9" height="2" rx="1"/>
                        <rect fill="#000000" x="6" y="15" width="5" height="2" rx="1"/>
                    </g>
                    </svg>
                </span>',

            "exercise" => '<span class="svg-icon menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24"/>
                        <path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" fill="#000000" opacity="0.3"/>
                        <path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" fill="#000000"/>
                        <rect fill="#000000" opacity="0.3" x="10" y="9" width="7" height="2" rx="1"/>
                        <rect fill="#000000" opacity="0.3" x="7" y="9" width="2" height="2" rx="1"/>
                        <rect fill="#000000" opacity="0.3" x="7" y="13" width="2" height="2" rx="1"/>
                        <rect fill="#000000" opacity="0.3" x="10" y="13" width="7" height="2" rx="1"/>
                        <rect fill="#000000" opacity="0.3" x="7" y="17" width="2" height="2" rx="1"/>
                        <rect fill="#000000" opacity="0.3" x="10" y="17" width="7" height="2" rx="1"/>
                    </g>
                    </svg>
                </span>',

            "dashboard" => '<span class="svg-icon menu-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <polygon id="Bound" points="0 0 24 0 24 24 0 24" />
                            <path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" id="Shape" fill="#000000" fill-rule="nonzero" />
                            <path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" id="Path" fill="#000000" opacity="0.3" />
                        </g>
                    </svg>
                </span>',
        ];

        return $icons[$name];
    }

    public function menu_admin()
    {
        return [
            [
                'key' => 'dashboard',
                'title' => 'Dashboard',
                'url' => 'admin/dashboard',
                'permission' => 'dashboard',
                'icon' => $this->icon('dashboard'),
            ],
            [
                'key' => 'master',
                'title' => 'Master Data',
                'url' => '#',
                'permission' => 'division',
                'icon' => $this->icon('master'),
                'children' => [
                    [
                        'key' => 'courses',
                        'title' => 'Courses',
                        'url' => '#',
                        'permission' => 'courses',
                        'children' => [
                            // [
                            //     'key' => 'package',
                            //     'title' => 'Package',
                            //     'url' => 'admin/master/courses/package',
                            //     'permission' => 'package'
                            // ],
                            [
                                'key' => 'classroom-category',
                                'title' => 'Kategori Kelas',
                                'url' => 'admin/master/courses/classroom-category',
                                'permission' => 'class_category'
                            ],
                            [
                                'key' => 'sub-classroom-category',
                                'title' => 'Sub Kategori Kelas',
                                'url' => 'admin/master/courses/sub-classroom-category',
                                'permission' => 'sub_class_category'
                            ],
                            [
                                'key' => 'classroom',
                                'title' => 'Kelas',
                                'url' => 'admin/master/courses/classroom',
                                'permission' => 'classroom'
                            ],
                            [
                                'key' => 'session-video',
                                'title' => 'Video',
                                'url' => 'admin/master/courses/session-video',
                                'permission' => 'video'
                            ],
                            [
                                'key' => 'master-lesson',
                                'title' => 'Master Lesson',
                                'url' => 'admin/master/courses/master-lesson',
                                'permission' => 'master_lesson'
                            ],
                        ],
                    ],
                    [
                        'key' => 'media-conference',
                        'title' => 'Media Conference',
                        'url' => 'admin/master/media-conference',
                        'permission' => 'media_conference'
                    ],
                    [
                        'key' => 'theory',
                        'title' => 'Materi',
                        'url' => 'admin/master/theory',
                        'permission' => 'theory'
                    ],
                    [
                        'key' => 'expertise',
                        'title' => 'Expertise',
                        'url' => 'admin/master/expertise',
                        'permission' => 'expertise'
                    ],
                    [
                        'key' => 'admin',
                        'title' => 'Admin',
                        'url' => 'admin/master/admin',
                        'permission' => 'admin'
                    ],
                    [
                        'key' => 'coach',
                        'title' => 'Coach',
                        'url' => 'admin/master/coach',
                        'permission' => 'coach'
                    ],
                    [
                        'key' => 'guest-star',
                        'title' => 'Guest Star',
                        'url' => 'admin/master/guest-star',
                        'permission' => 'guest_star'
                    ],
                    [
                        'key' => 'student',
                        'title' => 'Student',
                        'url' => 'admin/master/student',
                        'permission' => 'student'
                    ],
                ],
            ],
            [
                'key' => 'transaction',
                'title' => 'Data Transaksi',
                'url' => '#',
                'permission' => 'transaction',
                'icon' => $this->icon('master'),
                'children' => [
                    [
                        'key' => 'coach',
                        'title' => 'Coach',
                        'url' => 'admin/master/coach',
                        'permission' => 'coach'
                    ],
                    [
                        'key' => 'student',
                        'title' => 'Student',
                        'url' => 'admin/master/student',
                        'permission' => 'student'
                    ],
                ],
            ],
            [
                'key' => 'schedule',
                'title' => 'Schedule',
                'url' => 'admin/schedule',
                'permission' => 'schedule',
                'icon' => $this->icon('master'),
            ],
            [
                'key' => 'report',
                'title' => 'Reporting',
                'url' => '#',
                'permission' => 'report',
                'icon' => $this->icon('master'),
                'children' => [
                    [
                        'key' => 'review',
                        'title' => 'Review',
                        'url' => 'student/master/review',
                        'permission' => 'review',
                        'children' => [
                            [
                                'key' => 'coach',
                                'title' => 'Coach',
                                'url' => 'admin/master/coach',
                                'permission' => 'coach'
                            ],
                            [
                                'key' => 'student',
                                'title' => 'Student',
                                'url' => 'admin/master/student',
                                'permission' => 'student'
                            ],
                            [
                                'key' => 'class',
                                'title' => 'Kelas',
                                'url' => 'admin/master/class',
                                'permission' => 'class'
                            ],
                            [
                                'key' => 'video',
                                'title' => 'Video',
                                'url' => 'admin/master/video',
                                'permission' => 'video'
                            ],
                        ]
                    ],
                    [
                        'key' => 'transaction',
                        'title' => 'Transaksi',
                        'url' => 'student/master/transaction',
                        'permission' => 'transaction',
                        'children' => [
                            [
                                'key' => 'coach',
                                'title' => 'Coach',
                                'url' => 'admin/master/coach',
                                'permission' => 'coach'
                            ],
                            [
                                'key' => 'student',
                                'title' => 'Student',
                                'url' => 'admin/master/student',
                                'permission' => 'student'
                            ],
                        ]
                    ],
                ],
            ],
            [
                'key' => 'notification',
                'title' => 'Notification Setting',
                'url' => '#',
                'permission' => 'notification',
                'icon' => $this->icon('master'),
                'children' => [
                    [
                        'key' => 'general',
                        'title' => 'Umum',
                        'url' => 'student/master/general',
                        'permission' => 'general'
                    ],
                    [
                        'key' => 'coach',
                        'title' => 'Coach',
                        'url' => 'admin/master/coach',
                        'permission' => 'coach'
                    ],
                    [
                        'key' => 'student',
                        'title' => 'Student',
                        'url' => 'admin/master/student',
                        'permission' => 'student'
                    ],
                ],
            ],
        ];
    }

    public function menu_student()
    {
        return [
            [
                'key' => 'dashboard',
                'title' => 'Dashboard',
                'url' => 'student/dashboard',
                'permission' => 'student_dashboard',
                'icon' => $this->icon('dashboard'),
            ],
            [
                'key' => 'schedule',
                'title' => 'Schedule',
                'url' => 'student/schedule',
                'permission' => 'student_schedule',
                'icon' => $this->icon('schedule'),
            ],
            [
                'key' => 'class',
                'title' => 'My Class',
                'url' => 'student/my-class',
                'permission' => 'student_class',
                'icon' => $this->icon('schedule'),
            ],
            [
                'key' => 'theory',
                'title' => 'Materi',
                'permission' => 'student_theory',
                'url' => '#',
                'icon' => $this->icon('theory'),
                'children' => [
                    [
                        'key' => 'class_theory',
                        'title' => 'Materi Kelas',
                        'url' => 'student/theory',
                        'permission' => 'student_theory',
                    ],
                    [
                        'key' => 'class_video',
                        'title' => 'Video Kelas',
                        'url' => 'student/video',
                        'permission' => 'student_theory',
                    ],
                ]
            ],
            [
                'key' => 'exercise',
                'title' => 'Exercise',
                'url' => 'student/exercise',
                'permission' => 'student_exercise',
                'icon' => $this->icon('exercise'),
            ],
            [
                'key' => 'review',
                'title' => 'Review',
                'url' => 'student/review',
                'permission' => 'student_review',
                'icon' => $this->icon('exercise'),
            ],
            [
                'key' => 'new_package',
                'title' => 'Buy New Package',
                'url' => 'student/new-package',
                'permission' => 'student_new_package',
                'icon' => $this->icon('exercise'),
            ],
            [
                'key' => 'invoice',
                'title' => 'Invoice List',
                'url' => 'student/invoice',
                'permission' => 'student_invoice',
                'icon' => $this->icon('exercise'),
            ],
        ];
    }

    public function menu_coach()
    {
        return [
            [
                'key' => 'dashboard',
                'title' => 'Dashboard',
                'url' => 'coach/dashboard',
                'permission' => 'dashboard',
                'icon' => $this->icon('dashboard'),
            ],
            [
                'key' => 'schedule',
                'title' => 'Schedule',
                'url' => 'coach/schedule',
                'permission' => 'schedule',
                'icon' => $this->icon('schedule'),
            ],
            [
                'key' => 'theory',
                'title' => 'Materi',
                'url' => 'coach/theory',
                'permission' => 'theory',
                'icon' => $this->icon('theory'),
            ],
            [
                'key' => 'exercise',
                'title' => 'Exercise',
                'url' => '#',
                'permission' => 'exercise',
                'icon' => $this->icon('exercise'),
                'children' => [
                    [
                        'key' => 'assignment',
                        'title' => 'Assignment',
                        'url' => 'coach/exercise/assignment',
                        'permission' => 'assignment'
                    ],
                    [
                        'key' => 'review_assignment',
                        'title' => 'Review Assignment',
                        'url' => 'coach/exercise/review-assignment',
                        'permission' => 'review_assignment'
                    ],
                ],
            ],
        ];
    }
}
