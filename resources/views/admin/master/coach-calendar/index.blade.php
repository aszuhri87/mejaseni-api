@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-2 text-left" style="padding-right: 0 !important">
                <img src="{{$data->image_url}}" width="100px" height="100px" class="rounded">
            </div>
            <div class="col-lg-6">
                <p class="font-weight-bold font-size-h3" style="margin-bottom: 0 !important">{{$data->name}}</p>
                <p class="text-muted" style="margin-bottom: 0 !important">{{$data->expertise}}</p>
                <div class="row mt-2">
                    <div class="col-2" style="padding-right: 0 !important">
                        <span class="svg-icon svg-icon-primary svg-icon-4x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Clothes/Crown.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24"/>
                                <path d="M11.2600599,5.81393408 L2,16 L22,16 L12.7399401,5.81393408 C12.3684331,5.40527646 11.7359848,5.37515988 11.3273272,5.7466668 C11.3038503,5.7680094 11.2814025,5.79045722 11.2600599,5.81393408 Z" fill="#000000" opacity="0.3"/>
                                <path d="M12.0056789,15.7116802 L20.2805786,6.85290308 C20.6575758,6.44930487 21.2903735,6.42774054 21.6939717,6.8047378 C21.8964274,6.9938498 22.0113578,7.25847607 22.0113578,7.535517 L22.0113578,20 L16.0113578,20 L2,20 L2,7.535517 C2,7.25847607 2.11493033,6.9938498 2.31738608,6.8047378 C2.72098429,6.42774054 3.35378194,6.44930487 3.7307792,6.85290308 L12.0056789,15.7116802 Z" fill="#000000"/>
                            </g>
                        </svg><!--end::Svg Icon--></span>
                    </div>
                    <div class="col-4" style="padding-left: 0 !important">
                        <p class="text-muted" style="margin-bottom: 0 !important">Total Kelas</p>
                        <p class="h2" id="total-class" style="margin-bottom: 0 !important">50</p>
                    </div>
                    <div class="col-2" style="padding-right: 0 !important">
                        <span class="svg-icon svg-icon-primary svg-icon-4x">
                            <!--begin::Svg Icon | path:/metronic/theme/html/demo1/dist/assets/media/svg/icons/Media/Equalizer.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <rect fill="#000000" opacity="0.3" x="13" y="4" width="3" height="16" rx="1.5"></rect>
                                    <rect fill="#000000" x="8" y="9" width="3" height="11" rx="1.5"></rect>
                                    <rect fill="#000000" x="18" y="11" width="3" height="9" rx="1.5"></rect>
                                    <rect fill="#000000" x="3" y="13" width="3" height="7" rx="1.5"></rect>
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>
                    </div>
                    <div class="col-4" style="padding-left: 0 !important">
                        <p class="text-muted" style="margin-bottom: 0 !important">Total Rating</p>
                        <p class="h2" id="total-rating" style="margin-bottom: 0 !important">4.7</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 text-center">
                <p>Kelas Aktif</p>
                <div>
                    <span class="display-3">11</span>
                </div>
            </div>
            <div class="col-lg-2 text-center">
                <p>Sisa Pertemuan</p>
                <div>
                    <span class="display-3">3</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
               <h5>Kelas Aktif</h5>
            </div>
            <div class="card-body">

            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col text-center">
                        <a href="#" class="btn btn-primary btn-pill btn-light-primary btn-rounded">View List</a>
                    </div>
                </div>
                <div id='calendar' class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

@include('admin.master.coach-calendar.modal')
@endsection

@push('script')
    @include('admin.master.coach-calendar.script')
@endpush
