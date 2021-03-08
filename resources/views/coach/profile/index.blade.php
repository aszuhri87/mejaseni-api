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
                                    <span class="nav-icon"><span class="svg-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                                viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24" />
                                                    <path
                                                        d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z"
                                                        fill="#000000" fill-rule="nonzero" />
                                                    <path
                                                        d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z"
                                                        fill="#000000" opacity="0.3" />
                                                </g>
                                            </svg>
                                        </span>
                                    </span>
                                    <span class="nav-text font-size-lg">Account</span>
                                </a>
                            </li>
                            {{-- end account --}}

                            {{-- change password --}}
                            <li class="nav-item mr-3">
                                <a class="nav-link" data-toggle="tab" href="#change-password-form" id="password">
                                    <span class="nav-icon"><span class="svg-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                                viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <path
                                                        d="M4,4 L11.6314229,2.5691082 C11.8750185,2.52343403 12.1249815,2.52343403 12.3685771,2.5691082 L20,4 L20,13.2830094 C20,16.2173861 18.4883464,18.9447835 16,20.5 L12.5299989,22.6687507 C12.2057287,22.8714196 11.7942713,22.8714196 11.4700011,22.6687507 L8,20.5 C5.51165358,18.9447835 4,16.2173861 4,13.2830094 L4,4 Z"
                                                        fill="#000000" opacity="0.3" />
                                                    <path
                                                        d="M12,11 C10.8954305,11 10,10.1045695 10,9 C10,7.8954305 10.8954305,7 12,7 C13.1045695,7 14,7.8954305 14,9 C14,10.1045695 13.1045695,11 12,11 Z"
                                                        fill="#000000" opacity="0.3" />
                                                    <path
                                                        d="M7.00036205,16.4995035 C7.21569918,13.5165724 9.36772908,12 11.9907452,12 C14.6506758,12 16.8360465,13.4332455 16.9988413,16.5 C17.0053266,16.6221713 16.9988413,17 16.5815,17 C14.5228466,17 11.463736,17 7.4041679,17 C7.26484009,17 6.98863236,16.6619875 7.00036205,16.4995035 Z"
                                                        fill="#000000" opacity="0.3" />
                                                </g>
                                            </svg>
                                        </span>
                                    </span>
                                    <span class="nav-text font-size-lg">Change Password</span>
                                </a>
                            </li>
                            {{-- end change password --}}

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
                                                <label class="col-form-label col-3 text-lg-right text-left">Avatar</label>
                                                <div class="col-9">
                                                    <div class="image-input image-input-empty image-input-outline"
                                                        id="kt_user_edit_avatar">
                                                        <div class="image-input-wrapper image">
                                                            @if(!empty(Auth::guard('coach')->user()->image))
                                                                <img src="{{ $path }}{{Auth::guard('coach')->user()->image}}" class="img-profile-edit rounded" style="width:194px !important; height:194px !important;">
                                                            @else
                                                                <img src="{{ url('/assets/images/profile.png') }}" class="img-profile-edit rounded" style="width:194px !important; height:194px !important;">
                                                            @endif
                                                        </div>

                                                        <label
                                                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                            data-action="change" data-toggle="tooltip" title=""
                                                            data-original-title="Change avatar">
                                                            <i class="fa fa-pen icon-sm text-muted"></i>
                                                            <input type="file" name="profile_avatar"
                                                                accept=".png, .jpg, .jpeg" class="upload" />
                                                            <input type="hidden" name="profile_avatar_remove" />
                                                        </label>

                                                        <span
                                                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                            data-action="cancel" data-toggle="tooltip"
                                                            title="Cancel avatar">
                                                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                        </span>

                                                        <span
                                                            class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                            data-action="remove" data-toggle="tooltip"
                                                            title="Remove avatar">
                                                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label col-3 text-lg-right text-left">Full Name</label>
                                                <div class="col-9">
                                                    <input type="text" name="name" value="{{ Auth::guard('coach')->user()->name }}"
                                                        class="form-control form-control-lg" required
                                                        placeholder="Full Name" id="name"/>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label col-3 text-lg-right text-left">Username</label>
                                                <div class="col-9">
                                                    <input type="text" name="username" value="{{ Auth::guard('coach')->user()->username }}"
                                                        class="form-control form-control-lg" required
                                                        placeholder="Username" id="username" />
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label col-3 text-lg-right text-left">Email</label>
                                                <div class="col-9">
                                                    <input type="email" name="email" value="{{ Auth::guard('coach')->user()->email }}"
                                                        class="form-control form-control-lg" required
                                                        placeholder="Email" id="email" />
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-form-label col-3 text-lg-right text-left">Work / Exprtise</label>
                                                <div class="col-9">
                                                    <select name="expertise" id="expertise">
                                                        <option value="" selected>Pilih Expertise</option>
                                                        @foreach ($expertises as $key => $value)
                                                            @if (Auth::guard('coach')->user()->expertise_id == $value->id)
                                                                <option value="{{$value->id}}" selected>{{ $value->name }}</option>
                                                            @else
                                                                <option value="{{$value->id}}">{{ $value->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
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

                            {{-- change password form --}}
                            <div class="tab-pane px-7" id="change-password-form" role="tabpanel">
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-xl-2"></div>
                                        <div class="col-xl-7">

                                            {{-- current password --}}
                                            <div class="form-group row">
                                                <label class="col-form-label col-4 text-lg-right text-left">Password</label>
                                                <div class="col-8">
                                                    <input type="password" name="current_password" id="current_password" class="form-control form-control-lg mb-1" placeholder="Password" />
                                                </div>
                                            </div>

                                            {{-- password --}}
                                            <div class="form-group row">
                                                <label class="col-form-label col-4 text-lg-right text-left">New Password</label>
                                                <div class="col-8">
                                                    <input type="password" name="password" id="new_password" class="form-control form-control-lg" placeholder="New Password" />
                                                </div>
                                            </div>

                                            {{-- confirm password --}}
                                            <div class="form-group row">
                                                <label class="col-form-label col-4 text-lg-right text-left">Confirm New Password</label>
                                                <div class="col-8">
                                                    <input type="password" name="password_confirmation" id="confirm_password" class="form-control form-control-lg" placeholder="Confirm New Password"/>
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
                            {{-- end change password form --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    @include('coach.profile.script')
@endpush
