@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <h5 class="text-primary">Seni Musik</h5>
                <h3 class="mt-5">Jazz Piano - Ultimate Beginners Course for Piano & Keyboard</h3>
                <p class="mt-5">Mentored by Rinady Imam</p>
                <p class="mt-5"><h2 class="text-primary">Rp. 499.000</h2></p>
                <button class="btn btn-primary mt-3 btn-buy">Buy Course</button>
            </div>
            <div class="col-lg-6">
                <img src="https://mejaseni.yk1.s3.gmedia.id/media/UeSNulAy5goyVikDwkLCLh7HZZ4Uduz3Dz4cp6RO.webp" class="rounded" style="height: 200px !important;box-shadow: 0 15px 20px -20px #7F16A7;" width="100%">
            </div>
        </div>
        <div class="row" style="margin-top: 3% !important">
            <div class="col">
                <div class="card-toolbar">
                    <ul class="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
                        {{-- learning --}}
                        <li class="nav-item mr-3">
                            <a class="nav-link active" data-toggle="tab" href="#tab-learning">
                                <span class="nav-icon">
                                    <span class="svg-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <path d="M4.875,20.75 C4.63541667,20.75 4.39583333,20.6541667 4.20416667,20.4625 L2.2875,18.5458333 C1.90416667,18.1625 1.90416667,17.5875 2.2875,17.2041667 C2.67083333,16.8208333 3.29375,16.8208333 3.62916667,17.2041667 L4.875,18.45 L8.0375,15.2875 C8.42083333,14.9041667 8.99583333,14.9041667 9.37916667,15.2875 C9.7625,15.6708333 9.7625,16.2458333 9.37916667,16.6291667 L5.54583333,20.4625 C5.35416667,20.6541667 5.11458333,20.75 4.875,20.75 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                            <path d="M12.9835977,18 C12.7263047,14.0909841 9.47412135,11 5.5,11 C4.98630124,11 4.48466491,11.0516454 4,11.1500272 L4,7 C4,5.8954305 4.8954305,5 6,5 L20,5 C21.1045695,5 22,5.8954305 22,7 L22,16 C22,17.1045695 21.1045695,18 20,18 L12.9835977,18 Z M19.1444251,6.83964668 L13,10.1481833 L6.85557487,6.83964668 C6.4908718,6.6432681 6.03602525,6.77972206 5.83964668,7.14442513 C5.6432681,7.5091282 5.77972206,7.96397475 6.14442513,8.16035332 L12.6444251,11.6603533 C12.8664074,11.7798822 13.1335926,11.7798822 13.3555749,11.6603533 L19.8555749,8.16035332 C20.2202779,7.96397475 20.3567319,7.5091282 20.1603533,7.14442513 C19.9639747,6.77972206 19.5091282,6.6432681 19.1444251,6.83964668 Z" fill="#000000"/>
                                        </g>
                                    </svg><!--end::Svg Icon--></span>
                                </span>

                                <span class="nav-text font-size-lg">What Will You Learn</span>
                            </a>
                        </li>
                        {{-- end learning --}}

                        {{-- video-couse --}}
                        <li class="nav-item mr-3">
                            <a class="nav-link" data-toggle="tab" href="#tab-video-couse">
                                <span class="nav-icon">
                                    <span class="svg-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <rect fill="#000000" x="2" y="6" width="13" height="12" rx="2"/>
                                            <path d="M22,8.4142119 L22,15.5857848 C22,16.1380695 21.5522847,16.5857848 21,16.5857848 C20.7347833,16.5857848 20.4804293,16.4804278 20.2928929,16.2928912 L16.7071064,12.7071013 C16.3165823,12.3165768 16.3165826,11.6834118 16.7071071,11.2928877 L20.2928936,7.70710477 C20.683418,7.31658067 21.316583,7.31658098 21.7071071,7.70710546 C21.8946433,7.89464181 22,8.14899558 22,8.4142119 Z" fill="#000000" opacity="0.3"/>
                                        </g>
                                    </svg></span>
                                </span>
                                <span class="nav-text font-size-lg">Video Couse</span>
                            </a>
                        </li>
                        {{-- end video-couse --}}

                        {{-- material --}}
                        <li class="nav-item mr-3">
                            <a class="nav-link" data-toggle="tab" href="#tab-material">
                                <span class="nav-icon">
                                    <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Home/Library.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <path d="M5,3 L6,3 C6.55228475,3 7,3.44771525 7,4 L7,20 C7,20.5522847 6.55228475,21 6,21 L5,21 C4.44771525,21 4,20.5522847 4,20 L4,4 C4,3.44771525 4.44771525,3 5,3 Z M10,3 L11,3 C11.5522847,3 12,3.44771525 12,4 L12,20 C12,20.5522847 11.5522847,21 11,21 L10,21 C9.44771525,21 9,20.5522847 9,20 L9,4 C9,3.44771525 9.44771525,3 10,3 Z" fill="#000000"/>
                                            <rect fill="#000000" opacity="0.3" transform="translate(17.825568, 11.945519) rotate(-19.000000) translate(-17.825568, -11.945519) " x="16.3255682" y="2.94551858" width="3" height="18" rx="1"/>
                                        </g>
                                    </svg><!--end::Svg Icon--></span>
                                </span>

                                <span class="nav-text font-size-lg">Material</span>
                            </a>
                        </li>
                        {{-- end material --}}
                    </ul>
                </div>
                <div class="tab-content">
                    {{-- learning --}}
                    <div class="tab-pane show active" id="tab-learning" role="tabpanel">
                        <div class="form-group mt-5">
                            <p>This course is for beginners on the Piano, and so starts with the basics, but quickly moves onto learning about the different kinds of chords and how they are constructed, gradually getting more and more advanced as you progress. You don't just learn about chords though. Along the way you will learn about different kinds of rhythms, playing patterns and techniques that can be applied to the chords. By the end you'll be able to play the piano by reading a chord sheet, but sound like a pro!</p>
                            <p>I believe the best way to learn is by applying your new found knowledge, and so throughout the course you will learn famous songs or chord progressions on the Piano, relevant to the particular chord/rhythm or playing pattern you've just been learning. The course uses examples from songs by a wide variety of artists such as Adele, Sam Smith, The Commodores, Christina Perri, John Legend and Don McLean to name a few.</p>

                        </div>
                    </div>
                    {{-- end learning --}}

                    {{-- video-couse --}}
                    <div class="tab-pane show" id="tab-video-couse" role="tabpanel">
                        <div class="row mt-5">
                            <div class="col-lg-3">
                                <img src="https://mejaseni.yk1.s3.gmedia.id/media/UeSNulAy5goyVikDwkLCLh7HZZ4Uduz3Dz4cp6RO.webp" class="rounded" height="150px" width="100%">
                            </div>
                            <div class="col-lg-9">
                                <p><h4>#1 Introducing</h4></p>
                                <p>This course is for beginners on the Piano, and so starts with the basics, but quickly moves onto learning about the different kinds of chords and how they are constructed, gradually getting more and more advanced as you progress. You don't just learn about chords though. Along the way you will learn about different kinds of rhythms, playing patterns and techniques that can be applied to the chords. By the end you'll be able to play the piano by reading a chord sheet, but sound like a pro!</p>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-lg-9 offset-3">
                                <hr>
                            </div>
                        </div>
                    </div>
                    {{-- end video-couse --}}

                    {{-- material --}}
                    <div class="tab-pane show" id="tab-material" role="tabpanel">
                        <div class="form-group mt-5">
                            Material
                        </div>
                    </div>
                    {{-- end material --}}
                </div>
            </div>
        </div>
    </div>
</div>

@include('student.package-detail.modal')
@endsection

@push('script')
    @include('student.package-detail.script')
@endpush
