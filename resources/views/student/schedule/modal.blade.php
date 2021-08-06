<!-- Modal-->
<div class="modal" id="modal-booking" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-booking" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Booking Kelas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="coach_schedule_id" id="coach_schedule_id">
                    <input type="hidden" name="classroom_id" id="classroom_id">
                    <input type="hidden" name="student_id" value="{{Auth::guard('student')->user()->id}}">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>
                                    Class
                                    <span class="text-danger d-none">*</span>
                                </label>
                                <h5 id="booking-classroom-name"></h5>
                            </div>
                            <div class="form-group">
                                <label>
                                    Date
                                    <span class="text-danger d-none">*</span>
                                </label>
                                <h5 id="booking-date"></h5>
                            </div>
                            <div class="form-group">
                                <label>
                                    Time
                                    <span class="text-danger d-none">*</span>
                                </label>
                                <h5 id="booking-time"></h5>
                            </div>
                            <div class="form-group">
                                <label>
                                    Coach
                                    <span class="text-danger d-none">*</span>
                                </label>
                                <h5 id="booking-coach"></h5>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>
                                    Sisa Booking
                                    <span class="text-danger d-none">*</span>
                                </label>
                                <div class="border p-5 text-center" style="width: 100px !important">
                                    <h1 id="booking-remaining" class="display2 display3-lg"></h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-primary btn-loading-basic">Konfirmasi Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="modal-cancel-schedule" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-cancel-schedule" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Booking Kelas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="coach_schedule_id" id="reschedule_coach_schedule_id">
                    <input type="hidden" name="classroom_id" id="reschedule_classroom_id">
                    <input type="hidden" name="student_id" value="{{Auth::guard('student')->user()->id}}" id="reschedule_student_id">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>
                                    Class
                                    <span class="text-danger d-none">*</span>
                                </label>
                                <h5 id="reschedule-classroom-name"></h5>
                            </div>
                            <div class="form-group">
                                <label>
                                    Date
                                    <span class="text-danger d-none">*</span>
                                </label>
                                <h5 id="reschedule-date"></h5>
                            </div>
                            <div class="form-group">
                                <label>
                                    Time
                                    <span class="text-danger d-none">*</span>
                                </label>
                                <h5 id="reschedule-time"></h5>
                            </div>
                            <div class="form-group">
                                <label>
                                    Coach
                                    <span class="text-danger d-none">*</span>
                                </label>
                                <h5 id="reschedule-coach"></h5>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>
                                    Media Conference
                                    <span class="text-danger d-none">*</span>
                                </label>
                                <h5 id="media-conference"></h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" id="btn-cancel-reschedule" class="btn btn-danger btn-loading-reschedule show-hide">Cancel Schedule</button>
                    <button type="button" class="btn btn-primary show-hide" id="btn-reschedule">Reschedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="modal-booking-master-lesson" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-booking-master-lesson" autocomplete="off">
                <div class="modal-body">
                    <input type="hidden" name="master_lesson_id" id="master-lesson-id">
                    <input type="hidden" name="student_id" value="{{Auth::guard('student')->user()->id}}">
                    <div class="row">
                        <div class="col-12">
                            <div>
                                <img src="" id="poster" alt="" width="100%" height="300px" class="rounded">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-8 text-left">
                            <h4 id="master-lesson-title"></h4>
                        </div>
                        <div class="col-4 text-right">
                            <h2 class="text-primary" id="price"></h2>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-4 text-left">
                            <div class="form-group">
                                <label>Date</label>
                                <div id="date">

                                </div>
                            </div>
                        </div>
                        <div class="col-4 text-left">
                            <div class="form-group">
                                <label>Time</label>
                                <div id="time">

                                </div>
                            </div>
                        </div>
                        <div class="col-4 text-left">
                            <div class="form-group">
                                <label>Slot</label>
                                <div id="total-booking">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 text-justify">
                            <p id="description"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <div id="btn-booking-master-lesson">
                        <button type="submit" class="btn btn-primary btn-loading-master-lesson">
                            Daftar Sekarang
                            <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Navigation/Right-2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                    <rect fill="#000000" opacity="0.3" transform="translate(8.500000, 12.000000) rotate(-90.000000) translate(-8.500000, -12.000000) " x="7.5" y="7.5" width="2" height="9" rx="1"/>
                                    <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
                                </g>
                            </svg><!--end::Svg Icon--></span>
                        </button>
                    </div>
                    <div id="to-class" style="display: none">
                        <a class="btn btn-primary" id="link">
                            <span class="svg-icon svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/Navigation/Arrow-right.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                    <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-90.000000) translate(-12.000000, -12.000000) " x="11" y="5" width="2" height="14" rx="1"/>
                                    <path d="M9.70710318,15.7071045 C9.31657888,16.0976288 8.68341391,16.0976288 8.29288961,15.7071045 C7.90236532,15.3165802 7.90236532,14.6834152 8.29288961,14.2928909 L14.2928896,8.29289093 C14.6714686,7.914312 15.281055,7.90106637 15.675721,8.26284357 L21.675721,13.7628436 C22.08284,14.136036 22.1103429,14.7686034 21.7371505,15.1757223 C21.3639581,15.5828413 20.7313908,15.6103443 20.3242718,15.2371519 L15.0300721,10.3841355 L9.70710318,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.999999, 11.999997) scale(1, -1) rotate(90.000000) translate(-14.999999, -11.999997) "/>
                                </g>
                            </svg><!--end::Svg Icon--></span>
                            Masuk Kelas
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="modal-reschedule" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-reschedule" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Reschedule</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="coach_schedule_id" id="old_coach_schedule_id">
                    <input type="hidden" name="classroom_id" id="schedule_classroom_id">
                    <input type="hidden" name="student_id" id="student_id" value="{{Auth::guard('student')->user()->id}}">
                    <div class="row">
                        <div class="col-12">
                            <ul class="list-group list-group-flush" id="ul-list-schedule">

                            </ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-primary btn-loading-basic" id="confirm-reschedule">Konfirmasi Reschedule</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal" id="modal-request-schedule" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-request-schedule" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Request Schedule</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="subtraction" id="i-subtraction">
                    <div class="form-group">
                        <label>Kelas<span class="text-danger">*</span></label>
                        <select name="classroom_id" id="select-classroom" class="form-control">
                            <option value="">Pilih Kelas</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Frekuensi Mingguan<span class="text-danger">*</span></label>
                        <select name="type" id="select-frequency" class="form-control">
                            <option value="">Pilih Frekuensi</option>
                            <option value="2">2X Seminggu</option>
                            <option value="3">3X Seminggu</option>
                            <option value="4">4X Seminggu</option>
                        </select>
                    </div>
                    <div id="time-place"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-primary btn-loading-basic">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
