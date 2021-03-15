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

            "my-class" => '<span class="svg-icon menu-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Clothes/Crown.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24"/>
                        <path d="M11.2600599,5.81393408 L2,16 L22,16 L12.7399401,5.81393408 C12.3684331,5.40527646 11.7359848,5.37515988 11.3273272,5.7466668 C11.3038503,5.7680094 11.2814025,5.79045722 11.2600599,5.81393408 Z" fill="#000000" opacity="0.3"/>
                        <path d="M12.0056789,15.7116802 L20.2805786,6.85290308 C20.6575758,6.44930487 21.2903735,6.42774054 21.6939717,6.8047378 C21.8964274,6.9938498 22.0113578,7.25847607 22.0113578,7.535517 L22.0113578,20 L16.0113578,20 L2,20 L2,7.535517 C2,7.25847607 2.11493033,6.9938498 2.31738608,6.8047378 C2.72098429,6.42774054 3.35378194,6.44930487 3.7307792,6.85290308 L12.0056789,15.7116802 Z" fill="#000000"/>
                    </g>
                </svg><!--end::Svg Icon--></span>',

            "review" => '<span class="svg-icon menu-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Communication/Group-chat.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24"/>
                        <path d="M16,15.6315789 L16,12 C16,10.3431458 14.6568542,9 13,9 L6.16183229,9 L6.16183229,5.52631579 C6.16183229,4.13107011 7.29290239,3 8.68814808,3 L20.4776218,3 C21.8728674,3 23.0039375,4.13107011 23.0039375,5.52631579 L23.0039375,13.1052632 L23.0206157,17.786793 C23.0215995,18.0629336 22.7985408,18.2875874 22.5224001,18.2885711 C22.3891754,18.2890457 22.2612702,18.2363324 22.1670655,18.1421277 L19.6565168,15.6315789 L16,15.6315789 Z" fill="#000000"/>
                        <path d="M1.98505595,18 L1.98505595,13 C1.98505595,11.8954305 2.88048645,11 3.98505595,11 L11.9850559,11 C13.0896254,11 13.9850559,11.8954305 13.9850559,13 L13.9850559,18 C13.9850559,19.1045695 13.0896254,20 11.9850559,20 L4.10078614,20 L2.85693427,21.1905292 C2.65744295,21.3814685 2.34093638,21.3745358 2.14999706,21.1750444 C2.06092565,21.0819836 2.01120804,20.958136 2.01120804,20.8293182 L2.01120804,18.32426 C1.99400175,18.2187196 1.98505595,18.1104045 1.98505595,18 Z M6.5,14 C6.22385763,14 6,14.2238576 6,14.5 C6,14.7761424 6.22385763,15 6.5,15 L11.5,15 C11.7761424,15 12,14.7761424 12,14.5 C12,14.2238576 11.7761424,14 11.5,14 L6.5,14 Z M9.5,16 C9.22385763,16 9,16.2238576 9,16.5 C9,16.7761424 9.22385763,17 9.5,17 L11.5,17 C11.7761424,17 12,16.7761424 12,16.5 C12,16.2238576 11.7761424,16 11.5,16 L9.5,16 Z" fill="#000000" opacity="0.3"/>
                    </g>
                </svg><!--end::Svg Icon--></span>',

            "invoice" => '<span class="svg-icon menu-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Wallet.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24"/>
                        <circle fill="#000000" opacity="0.3" cx="20.5" cy="12.5" r="1.5"/>
                        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) " x="3" y="3" width="18" height="7" rx="1"/>
                        <path d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z" fill="#000000"/>
                    </g>
                </svg><!--end::Svg Icon--></span>',

            "new-package" => '<span class="svg-icon menu-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Box1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24"/>
                        <polygon fill="#000000" opacity="0.3" points="6 3 18 3 20 6.5 4 6.5"/>
                        <path d="M6,5 L18,5 C19.1045695,5 20,5.8954305 20,7 L20,19 C20,20.1045695 19.1045695,21 18,21 L6,21 C4.8954305,21 4,20.1045695 4,19 L4,7 C4,5.8954305 4.8954305,5 6,5 Z M9,9 C8.44771525,9 8,9.44771525 8,10 C8,10.5522847 8.44771525,11 9,11 L15,11 C15.5522847,11 16,10.5522847 16,10 C16,9.44771525 15.5522847,9 15,9 L9,9 Z" fill="#000000"/>
                    </g>
                </svg><!--end::Svg Icon--></span>',

            "notification" => '<span class="svg-icon menu-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Notifications1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <path d="M17,12 L18.5,12 C19.3284271,12 20,12.6715729 20,13.5 C20,14.3284271 19.3284271,15 18.5,15 L5.5,15 C4.67157288,15 4,14.3284271 4,13.5 C4,12.6715729 4.67157288,12 5.5,12 L7,12 L7.5582739,6.97553494 C7.80974924,4.71225688 9.72279394,3 12,3 C14.2772061,3 16.1902508,4.71225688 16.4417261,6.97553494 L17,12 Z" fill="#000000"/>
                    <rect fill="#000000" opacity="0.3" x="10" y="16" width="4" height="4" rx="2"/>
                </g>
            </svg><!--end::Svg Icon--></span>',

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
                    [
                        'key' => 'profile-video-coach',
                        'title' => 'Profile Video Coach',
                        'url' => 'admin/master/profile-video-coach',
                        'permission' => 'profile_video_coach'
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
                        'url' => 'admin/transaction/coach',
                        'permission' => 'coach'
                    ],
                    [
                        'key' => 'student',
                        'title' => 'Student',
                        'url' => 'admin/transaction/student',
                        'permission' => 'student'
                    ],
                ],
            ],
            [
                'key' => 'event',
                'title' => 'Event',
                'url' => 'admin/event',
                'permission' => 'event',
                'icon' => $this->icon('master'),
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
                        'url' => '#',
                        'permission' => 'review',
                        'children' => [
                            [
                                'key' => 'coach',
                                'title' => 'Coach',
                                'url' => 'admin/report/review/coach',
                                'permission' => 'coach'
                            ],
                            [
                                'key' => 'student',
                                'title' => 'Student',
                                'url' => 'admin/report/review/student',
                                'permission' => 'student'
                            ],
                            [
                                'key' => 'class',
                                'title' => 'Kelas',
                                'url' => 'admin/report/review/class',
                                'permission' => 'class'
                            ],
                            [
                                'key' => 'video',
                                'title' => 'Video',
                                'url' => 'admin/report/review/video',
                                'permission' => 'video'
                            ],
                        ]
                    ],
                    [
                        'key' => 'transaction-report',
                        'title' => 'Transaksi',
                        'url' => '#',
                        'permission' => 'transaction',
                        'children' => [
                            [
                                'key' => 'coach',
                                'title' => 'Coach',
                                'url' => 'admin/report/transaction/coach',
                                'permission' => 'coach'
                            ],
                            [
                                'key' => 'student',
                                'title' => 'Student',
                                'url' => 'admin/report/transaction-report/student',
                                'permission' => 'student'
                            ],
                        ]
                    ],
                ],
            ],
            [
                'key' => 'cms',
                'title' => 'CMS',
                'url' => '#',
                'permission' => 'cms',
                'icon' => $this->icon('master'),
                'children' => [
                    [
                        'key' => 'branch',
                        'title' => 'Branch',
                        'url' => 'admin/cms/branch',
                        'permission' => 'branch'
                    ],
                    [
                        'key' => 'career',
                        'title' => 'Career',
                        'url' => 'admin/cms/career',
                        'permission' => 'career'
                    ],
                    [
                        'key' => 'company',
                        'title' => 'Company',
                        'url' => 'admin/cms/company',
                        'permission' => 'company'
                    ],
                    [
                        'key' => 'coach-review',
                        'title' => 'Coach Review',
                        'url' => 'admin/cms/coach-review',
                        'permission' => 'coach-review'
                    ],
                    [
                        'key' => 'event',
                        'title' => 'Event',
                        'url' => 'admin/cms/event',
                        'permission' => 'event'
                    ],
                    [
                        'key' => 'faq',
                        'title' => 'FAQ',
                        'url' => 'admin/cms/faq',
                        'permission' => 'faq'
                    ],
                    [
                        'key' => 'galery',
                        'title' => 'Galery',
                        'url' => 'admin/cms/galery',
                        'permission' => 'galery'
                    ],
                    [
                        'key' => 'marketplace',
                        'title' => 'Market Place',
                        'url' => 'admin/cms/marketplace',
                        'permission' => 'marketplace'
                    ],
                    [
                        'key' => 'news',
                        'title' => 'News',
                        'url' => 'admin/cms/news',
                        'permission' => 'news'
                    ],
                    [
                        'key' => 'privacy-policy',
                        'title' => 'Privacy Policy',
                        'url' => 'admin/cms/privacy-policy',
                        'permission' => 'privacy-policy'
                    ],
                    [
                        'key' => 'program',
                        'title' => 'Program',
                        'url' => 'admin/cms/program',
                        'permission' => 'program'
                    ],
                    [
                        'key' => 'team',
                        'title' => 'Team',
                        'url' => 'admin/cms/team',
                        'permission' => 'team'
                    ],
                    [
                        'key' => 'social-media',
                        'title' => 'Social Media',
                        'url' => 'admin/cms/social-media',
                        'permission' => 'social-media'
                    ],
                    [
                        'key' => 'working-hour',
                        'title' => 'Working Hour',
                        'url' => 'admin/cms/working-hour',
                        'permission' => 'working-hour'
                    ],
                    [
                        'key' => 'question',
                        'title' => 'Question',
                        'url' => 'admin/cms/question',
                        'permission' => 'question'
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
                'key' => 'notification',
                'title' => 'Notification',
                'url' => 'student/notification',
                'permission' => 'student_notification',
                'icon' => $this->icon('notification'),
            ],
            [
                'key' => 'schedule',
                'title' => 'Schedule',
                'url' => 'student/schedule',
                'permission' => 'student_schedule',
                'icon' => $this->icon('schedule'),
            ],
            [
                'key' => 'my-class',
                'title' => 'My Class',
                'url' => 'student/my-class',
                'permission' => 'student_class',
                'icon' => $this->icon('my-class'),
            ],
            [
                'key' => 'theory',
                'title' => 'Materi',
                'permission' => 'student_theory',
                'url' => '#',
                'icon' => $this->icon('theory'),
                'children' => [
                    [
                        'key' => 'theory-class',
                        'title' => 'Materi Kelas',
                        'url' => 'student/theory/theory-class',
                        'permission' => 'student_theory',
                    ],
                    [
                        'key' => 'video-class',
                        'title' => 'Video Kelas',
                        'url' => 'student/theory/video-class',
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
                'icon' => $this->icon('review'),
            ],
            [
                'key' => 'new-package',
                'title' => 'Buy New Package',
                'url' => 'student/new-package',
                'permission' => 'student_new_package',
                'icon' => $this->icon('new-package'),
            ],
            [
                'key' => 'invoice',
                'title' => 'Invoice List',
                'url' => 'student/invoice',
                'permission' => 'student_invoice',
                'icon' => $this->icon('invoice'),
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
                        'key' => 'review-assignment',
                        'title' => 'Review Assignment',
                        'url' => 'coach/exercise/review-assignment',
                        'permission' => 'review_assignment'
                    ],
                ],
            ],
            [
                'key' => 'notification',
                'title' => 'Notification',
                'url' => 'coach/notification',
                'permission' => 'notification',
                'icon' => $this->icon('theory'),
            ],
        ];
    }
}
