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
                    <input type="hidden" name="student_id" value="{{Auth::guard('student')->user()->id}}" id="classroom_id">
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
                                    Sisa Pertemuan
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-loading-basic">Konfirmasi Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="modal-reschedule" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-reschedule" autocomplete="off">
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <div id="btn-reschedule">
                        <button type="submit" class="btn btn-primary btn-loading-basic">Reschedule</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
