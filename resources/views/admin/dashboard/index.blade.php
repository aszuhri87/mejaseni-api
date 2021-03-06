@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body" style="min-height: 500px">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div class="my-title">
                        <h4 class="m-0 p-0">Summary Course</h4>
                    </div>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center mb-5">
                    {{-- <div>
                        <h4>2021</h4>
                    </div>
                    <div>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-outline-primary btn-icon btn-sm">
                                <i class="flaticon2-left-arrow"></i>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-icon btn-sm">
                                <i class="flaticon2-right-arrow"></i>
                            </button>
                        </div>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="button" class="btn btn-outline-primary btn-sm">Yearly</button>
                            <button type="button" class="btn btn-outline-primary btn-sm">Montly</button>
                            <button type="button" class="btn btn-outline-primary btn-sm">Weekly</button>
                        </div>
                    </div> --}}
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-12">
                    <!--begin::Block-->
                    <div class="bg-light-warning p-8 rounded-xl flex-grow-1">
                        <!--begin::Item-->
                        <h6>Statistik Kelas</h6>
                        <div class="d-flex align-items-center mb-5 mt-3">
                            <div class="symbol symbol-circle symbol-white symbol-30 flex-shrink-0 mr-3">
                                <div class="symbol-label">
                                    <span class="svg-icon svg-icon-md svg-icon-warning">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Shopping/Cart3.svg-->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <path
                                                    d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z"
                                                    fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                <path
                                                    d="M3.5,9 L20.5,9 C21.0522847,9 21.5,9.44771525 21.5,10 C21.5,10.132026 21.4738562,10.2627452 21.4230769,10.3846154 L17.7692308,19.1538462 C17.3034221,20.271787 16.2111026,21 15,21 L9,21 C7.78889745,21 6.6965779,20.271787 6.23076923,19.1538462 L2.57692308,10.3846154 C2.36450587,9.87481408 2.60558331,9.28934029 3.11538462,9.07692308 C3.23725479,9.02614384 3.36797398,9 3.5,9 Z M12,17 C13.1045695,17 14,16.1045695 14,15 C14,13.8954305 13.1045695,13 12,13 C10.8954305,13 10,13.8954305 10,15 C10,16.1045695 10.8954305,17 12,17 Z"
                                                    fill="#000000" />
                                            </g>
                                        </svg>
                                        <!--end::Svg Icon-->
                                    </span>
                                </div>
                            </div>
                            <div>
                                <div class="font-size-sm font-weight-bold total-kelas">{{$statistic['classroom']['total']}}</div>
                                <div class="font-size-sm text-muted">Total Kelas</div>
                            </div>
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-circle symbol-white symbol-30 flex-shrink-0 mr-3">
                                <div class="symbol-label">
                                    <span class="svg-icon svg-icon-md svg-icon-primary">
                                        <!--begin::Svg Icon | path:assets/media/svg/icons/Shopping/Cart3.svg-->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <path
                                                    d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z"
                                                    fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                <path
                                                    d="M3.5,9 L20.5,9 C21.0522847,9 21.5,9.44771525 21.5,10 C21.5,10.132026 21.4738562,10.2627452 21.4230769,10.3846154 L17.7692308,19.1538462 C17.3034221,20.271787 16.2111026,21 15,21 L9,21 C7.78889745,21 6.6965779,20.271787 6.23076923,19.1538462 L2.57692308,10.3846154 C2.36450587,9.87481408 2.60558331,9.28934029 3.11538462,9.07692308 C3.23725479,9.02614384 3.36797398,9 3.5,9 Z M12,17 C13.1045695,17 14,16.1045695 14,15 C14,13.8954305 13.1045695,13 12,13 C10.8954305,13 10,13.8954305 10,15 C10,16.1045695 10.8954305,17 12,17 Z"
                                                    fill="#000000" />
                                            </g>
                                        </svg>
                                        <!--end::Svg Icon-->
                                    </span>
                                </div>
                            </div>
                            <div>
                                <div class="font-size-sm font-weight-bold total-kelas">{{$statistic['classroom']['sold']}}</div>
                                <div class="font-size-sm text-muted">Total Terjual</div>
                            </div>
                        </div>
                        <!--end::Item-->

                        <hr>

                        <!--begin::Item-->
                        <h6>Statistik Video</h6>
                        <div class="d-flex align-items-center mb-5 mt-3">
                            <div class="symbol symbol-circle symbol-white symbol-30 flex-shrink-0 mr-3">
                                <div class="symbol-label">
                                    <span class="svg-icon svg-icon-md svg-icon-warning">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <path
                                                    d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z"
                                                    fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                <path
                                                    d="M3.5,9 L20.5,9 C21.0522847,9 21.5,9.44771525 21.5,10 C21.5,10.132026 21.4738562,10.2627452 21.4230769,10.3846154 L17.7692308,19.1538462 C17.3034221,20.271787 16.2111026,21 15,21 L9,21 C7.78889745,21 6.6965779,20.271787 6.23076923,19.1538462 L2.57692308,10.3846154 C2.36450587,9.87481408 2.60558331,9.28934029 3.11538462,9.07692308 C3.23725479,9.02614384 3.36797398,9 3.5,9 Z M12,17 C13.1045695,17 14,16.1045695 14,15 C14,13.8954305 13.1045695,13 12,13 C10.8954305,13 10,13.8954305 10,15 C10,16.1045695 10.8954305,17 12,17 Z"
                                                    fill="#000000" />
                                            </g>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div>
                                <div class="font-size-sm font-weight-bold total-kelas">{{$statistic['video']['total']}}</div>
                                <div class="font-size-sm text-muted">Total Video</div>
                            </div>
                        </div>
                        <!--end::Item-->

                        <!--begin::Item-->
                        <div class="d-flex align-items-center mb-5">
                            <div class="symbol symbol-circle symbol-white symbol-30 flex-shrink-0 mr-3">
                                <div class="symbol-label">
                                    <span class="svg-icon svg-icon-md svg-icon-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <path d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z"
                                                    fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                <path
                                                    d="M3.5,9 L20.5,9 C21.0522847,9 21.5,9.44771525 21.5,10 C21.5,10.132026 21.4738562,10.2627452 21.4230769,10.3846154 L17.7692308,19.1538462 C17.3034221,20.271787 16.2111026,21 15,21 L9,21 C7.78889745,21 6.6965779,20.271787 6.23076923,19.1538462 L2.57692308,10.3846154 C2.36450587,9.87481408 2.60558331,9.28934029 3.11538462,9.07692308 C3.23725479,9.02614384 3.36797398,9 3.5,9 Z M12,17 C13.1045695,17 14,16.1045695 14,15 C14,13.8954305 13.1045695,13 12,13 C10.8954305,13 10,13.8954305 10,15 C10,16.1045695 10.8954305,17 12,17 Z"
                                                    fill="#000000" />
                                            </g>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div>
                                <div class="font-size-sm font-weight-bold total-kelas">{{$statistic['video']['sold']}}</div>
                                <div class="font-size-sm text-muted">Total Terjual</div>
                            </div>
                        </div>
                        <!--end::Item-->
                    </div>
                    <!--end::Block-->
                    </div>
                    <div class="col-md-9 col-sm-12">
                        <div id="chart_3" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .vl {
        border-left: 1px solid rgb(206, 206, 206);
        height: 100%;
    }
</style>

<div class="card mt-5">
    <div class="card-header">
        <h4 class="m-0 p-0">Most Frequent</h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <h4 class="m-0 p-0 mb-5">Kelas</h4>
                <!--begin::Table-->
                <div class="table-responsive">
                    <table class="table table-head-custom table-head-bg table-borderless table-vertical-center">
                        <tbody>
                            @foreach ($classrooms as $classroom)
                            <tr>
                                <td class="pl-0 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-50 flex-shrink-0 mr-4">
                                            <div class="symbol-label"
                                                style="background-image: url('{{$classroom->image_url}}')">
                                            </div>
                                        </div>
                                        <div>
                                            <a href="javascript:void(0);" class="text-dark-75 font-weight-bolder mb-1 font-size-lg">
                                                {{$classroom->name}}
                                            </a>
                                            <span class="text-muted font-weight-bold d-block d-flex align-items-center">
                                                {{$classroom->category}}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted font-weight-bolder d-block font-size-lg">{{$classroom->classroom_count}} Terjual</span>
                                </td>
                                <td></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!--end::Table-->
            </div>
            <div class="col-6">
                <h4 class="m-0 p-0 mb-5">Video</h4>
                <!--begin::Table-->
                <div class="table-responsive">
                    <table class="table table-head-custom table-head-bg table-borderless table-vertical-center">
                        <tbody>
                            @foreach ($videos as $video)
                            <tr>
                                <td class="pl-0 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-50 flex-shrink-0 mr-4">
                                            <div class="symbol-label"
                                                style="background-image: url('assets/images/video-placeholder.png')">
                                            </div>
                                        </div>
                                        <div>
                                            <a href="javascript:void(0);" class="text-dark-75 font-weight-bolder mb-1 font-size-lg">
                                                {{$video->name}}
                                            </a>
                                            <span class="text-muted font-weight-bold d-block d-flex align-items-center">
                                                {{$video->category}}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted font-weight-bolder d-block font-size-lg">{{$video->video_count}} Terjual</span>
                                </td>
                                <td></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!--end::Table-->
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
    @include('admin.dashboard.script')
@endpush
