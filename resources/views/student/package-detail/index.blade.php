@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <h5 class="text-primary">{{$data->sub_classroom_category_name}}</h5>
                <h3 class="mt-5">{{$data->name}}</h3>
                <p class="mt-5">Mentored by {{$data->coach_name}}</p>
                <p class="mt-5"><h2 class="text-primary">Rp. {{number_format($data->price)}}</h2></p>
                <button class="btn btn-outline-primary mt-3 btn-cart" data-id="{{$data->id}}">
                    <span class="svg-icon"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"/>
                            <path d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                            <path d="M3.5,9 L20.5,9 C21.0522847,9 21.5,9.44771525 21.5,10 C21.5,10.132026 21.4738562,10.2627452 21.4230769,10.3846154 L17.7692308,19.1538462 C17.3034221,20.271787 16.2111026,21 15,21 L9,21 C7.78889745,21 6.6965779,20.271787 6.23076923,19.1538462 L2.57692308,10.3846154 C2.36450587,9.87481408 2.60558331,9.28934029 3.11538462,9.07692308 C3.23725479,9.02614384 3.36797398,9 3.5,9 Z M12,17 C13.1045695,17 14,16.1045695 14,15 C14,13.8954305 13.1045695,13 12,13 C10.8954305,13 10,13.8954305 10,15 C10,16.1045695 10.8954305,17 12,17 Z" fill="#000000"/>
                        </g>
                    </svg></span>
                    Add To Cart
                </button>
            </div>
            <div class="col-lg-6">
                <img src="{{$data->image_url}}" class="rounded" style="height: 200px !important;box-shadow: 0 15px 20px -20px #7F16A7;" width="100%">
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
                            <p>{{$data->description}}</p>
                        </div>
                    </div>
                    {{-- end learning --}}

                    {{-- video-couse --}}
                    <div class="tab-pane show" id="tab-video-couse" role="tabpanel">
                        @if (count($data->video) > 0)
                            @foreach ($data->video as $key => $item)
                                <div class="row mt-5">
                                    <div class="col-lg-3">
                                        @if ($item->is_youtube)
                                            {{-- <iframe width="100%" height="150px" src="{{$item->url}}"></iframe> --}}
                                            <img src="{{asset('assets/images/thumbnail.png')}}" class="rounded" height="150px" width="100%">
                                        @else
                                            <img src="{{asset('assets/images/thumbnail-lock.png')}}" class="rounded" height="150px" width="100%">
                                        @endif
                                    </div>
                                    <div class="col-lg-9">
                                        <p><h4>#{{$key+1}} {{$item->name}}</h4></p>
                                        {{-- <p>This course is for beginners on the Piano, and so starts with the basics, but quickly moves onto learning about the different kinds of chords and how they are constructed, gradually getting more and more advanced as you progress. You don't just learn about chords though. Along the way you will learn about different kinds of rhythms, playing patterns and techniques that can be applied to the chords. By the end you'll be able to play the piano by reading a chord sheet, but sound like a pro!</p> --}}
                                    </div>
                                </div>
                                <div class="row mt-5">
                                    <div class="col-lg-9 offset-3">
                                        <hr>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="form-group mt-5">
                                <h5 class="text-muted">Video Not Available</h5>
                            </div>
                        @endif
                    </div>
                    {{-- end video-couse --}}

                    {{-- material --}}
                    <div class="tab-pane show" id="tab-material" role="tabpanel">
                        @if (count($data->file_video) > 0)
                            <div class="row mt-5">
                            @foreach ($data->file_video as $key => $value)
                                <div class="col-lg-4 mb-5">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-3">
                                                    <img src="{{asset('assets/images/pdf-file-extension.png')}}" width="50px" height="50px">
                                                </div>
                                                <div class="col-9">
                                                    <p style="margin-bottom: 0 !important"><strong>{{$value->name}}</strong></p>
                                                </div>
                                            </div>
                                            <div class="row mt-5">
                                                <div class="col text-justify overflow-auto" style="height:80px !important">
                                                    {{$value->description}}
                                                </div>
                                            </div>
                                            <div class="row mt-5">
                                                <div class="col">
                                                    <table class="bordered-less">
                                                        <tr>
                                                            <td width="100px"><span class="text-muted">Coach</span></td>
                                                            <td>{{$data->coach_name}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="100px"><span class="text-muted">Upload At</span></td>
                                                            <td>{{ date('d F Y',strtotime($value->updated_at)) }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            {{-- <div class="row mt-5">
                                                <div class="col-12">
                                                    <a target="_blank" href="${data.file_url}" class="btn btn-primary">
                                                        <span class="svg-icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <rect x="0" y="0" width="24" height="24"/>
                                                                <path d="M2,13 C2,12.5 2.5,12 3,12 C3.5,12 4,12.5 4,13 C4,13.3333333 4,15 4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 C2,15 2,13.3333333 2,13 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                                <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 8.000000) rotate(-180.000000) translate(-12.000000, -8.000000) " x="11" y="1" width="2" height="14" rx="1"/>
                                                                <path d="M7.70710678,15.7071068 C7.31658249,16.0976311 6.68341751,16.0976311 6.29289322,15.7071068 C5.90236893,15.3165825 5.90236893,14.6834175 6.29289322,14.2928932 L11.2928932,9.29289322 C11.6689749,8.91681153 12.2736364,8.90091039 12.6689647,9.25670585 L17.6689647,13.7567059 C18.0794748,14.1261649 18.1127532,14.7584547 17.7432941,15.1689647 C17.3738351,15.5794748 16.7415453,15.6127532 16.3310353,15.2432941 L12.0362375,11.3779761 L7.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000004, 12.499999) rotate(-180.000000) translate(-12.000004, -12.499999) "/>
                                                            </g>
                                                        </svg></span>
                                                        Download
                                                    <a>
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        @else
                            <div class="form-group mt-5">
                                <h5 class="text-muted">Materi Not Available</h5>
                            </div>
                        @endif
                    </div>
                    {{-- end material --}}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- @include('student.package-detail.modal') --}}
@endsection

@push('script')
    @include('student.package-detail.script')
@endpush
