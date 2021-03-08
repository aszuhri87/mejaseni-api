@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class=" container-fluid ">
            <div class="card card-custom">
                <div class="card-header card-header-tabs-line nav-tabs-line-3x">
                    <div class="card-toolbar">
                        <ul class="nav nav-tabs nav-bold nav-tabs-line nav-tabs-line-3x">
                            {{-- account --}}
                            <li class="nav-item mr-3">
                                <a class="nav-link active" data-toggle="tab" href="#account-form" id="account">
                                    <span class="nav-icon">
                                        <span class="svg-icon"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Shopping/ATM.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <rect fill="#000000" opacity="0.3" x="2" y="4" width="20" height="5" rx="1"/>
                                                <path d="M5,7 L8,7 L8,21 L7,21 C5.8954305,21 5,20.1045695 5,19 L5,7 Z M19,7 L19,19 C19,20.1045695 18.1045695,21 17,21 L11,21 L11,7 L19,7 Z" fill="#000000"/>
                                            </g>
                                        </svg><!--end::Svg Icon--></span>
                                    </span>
                                    <span class="nav-text font-size-lg">Rekening</span>
                                </a>
                            </li>
                            {{-- end account --}}


                        </ul>
                    </div>
                </div>

                <div class="card-body">
                    <form class="form" id="kt_form">
                        <div class="tab-content">

                            {{-- account form --}}
                            <div class="tab-pane show active px-7" id="account-form" role="tabpanel">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-2"></div>
                                        <div class="col-xl-7">
                                            <div class="form-group row">
                                                <label class="col-form-label col-3 text-lg-right text-left">Nama Bank</label>
                                                <div class="col-9">
                                                    <input type="text" name="bank" @if ($bank_account) value="{{$bank_account->bank}}" @endif
                                                        class="form-control form-control-lg" required
                                                        placeholder="Nama Bank" id="bank"/>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label col-3 text-lg-right text-left">Nomor Rekening</label>
                                                <div class="col-9">
                                                    <input type="text" name="bank_number" @if ($bank_account) value="{{$bank_account->bank_number}}" @endif
                                                        class="form-control form-control-lg" required
                                                        placeholder="Nomor Rekening" id="bank-number" />
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label col-3 text-lg-right text-left">Nama Pemilik Akun</label>
                                                <div class="col-9">
                                                    <input type="text" name="name_account" @if ($bank_account) value="{{$bank_account->name_account}}" @endif
                                                        class="form-control form-control-lg" required
                                                        placeholder="Name Account" id="name_account" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- submit --}}
                                <div class="card-footer pb-0">
                                    <div class="row">
                                        <div class="col-xl-2"></div>
                                        <div class="col-xl-7">
                                            <div class="row">
                                                <div class="col-3"></div>
                                                <div class="col-9">
                                                    <div class="d-flex">
                                                        <a href="javascript:void(0)" class="btn btn-clean font-weight-bold">Batal</a>
                                                        <button type="submit" class="btn btn-primary btn-icon w-auto px-2 waves-effect width-md waves-light btn-loading-profile-coach ml-1">Simpan Perubahan</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- end submit --}}
                            </div>
                            {{-- end account form --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    @include('coach.bank_account.script')
@endpush
