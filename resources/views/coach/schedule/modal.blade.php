<!-- Modal-->
<div class="modal" id="modal-schedule" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-schedule" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Schedule</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" style="display: none !important">
                        <div class="col-12 select-classroom">
                            <div class="form-group">
                                <label>
                                    Class
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="classroom_id" id="classroom"></select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Tanggal<span class="text-danger">*</span></label>
                                <input type="text" name="date" class="form-control datepicker-with-stardate" required placeholder="Date" style="width: 100% !important">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Waktu Mulai / Jam Mulai<span class="text-danger">*</span></label>
                                <input type="text" name="time" class="form-control timepicker" required placeholder="Time" style="width: 100% !important">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="form-group">
                                <label>Durasi<span class="text-danger">*</span></label>
                                <input type="text" disabled class="form-control" id="duration" value="-" style="width: 100% !important">
                            </div>
                        </div>
                    </div>
                    <div class="form-group d-none">
                        <label>Tipe Kelas</label>
                        <div class="radio-inline mt-2">
                            <label class="radio radio-lg">
                                <input type="radio" value="1" checked name="type_class" class="type-class"/>
                                <span></span>
                                Package
                            </label>
                            <label class="radio radio-lg">
                                <input type="radio" value="2" name="type_class" class="type-class"/>
                                <span></span>
                                Master Lesson
                            </label>
                        </div>
                    </div>
                    {{-- <hr> --}}
                    <div class="row form-package">
                        <div class="col-12">
                            <div class="form-group">
                                <label>
                                    Media
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="platform_id" id="platform"></select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>Link Media<span class="text-danger">*</span></label>
                                <div class="name">
                                    <input type="text" class="form-control" name="platform_link" required placeholder="Nama">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row form-master-lesson" style="display: none">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Nama<span class="text-danger">*</span></label>
                                <div class="name">
                                    <input type="text" class="form-control" name="name" placeholder="Nama">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-loading">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal-->
<div class="modal" id="modal-schedule-detail" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-schedule-detail" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Schedule Detail</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card card-custom bg-light-secondary card-stretch gutter-b p-7 mt-0">
                        <div class="d-flex align-items-between">
                            <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                                <span class="text-muted">Class</span>
                                <p class="text-dark mb-1 font-size-lg class-name">-</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                                    <span class="text-muted">Package</span>
                                    <p class="text-dark mb-1 font-size-lg package-name">-</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                                    <span class="text-muted">Sesi</span>
                                    <p class="text-dark mb-1 font-size-lg session-place">-</p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-40 symbol-light-success mr-5">
                                        <span class="symbol-label">
                                            <img src="" width="40" height="40" class="align-self-center rounded coach-image" alt=""/>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                                        <span class="text-muted">Coach</span>
                                        <p class="text-dark mb-1 font-size-lg coach-name">-</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                                    <span class="text-muted">Siswa</span>
                                    <p class="text-dark mb-1 font-size-lg student-name">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row p-7">
                        <div class="col-6">
                            <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                                <span class="text-muted">Tanggal</span>
                                <p class="text-dark mb-1 font-size-lg date-place">-</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                                <span class="text-muted">Waktu</span>
                                <p class="text-dark mb-1 font-size-lg time-place">-</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn function-btn btn-delete btn-danger" style="display: none">Hapus</button>
                    <button type="button" class="btn function-btn btn-edit btn-success" style="display: none">Ubah Link Media</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal-->
<div class="modal" id="modal-schedule-detail-ml" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="card card-custom bg-light-secondary card-stretch gutter-b p-7">
                    <div class="d-flex align-items-between">
                        <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                            <span class="text-muted">Class</span>
                            <p class="text-dark mb-1 font-size-lg ml-class-name">-</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                                <span class="text-muted">Package</span>
                                <p class="text-dark mb-1 font-size-lg ml-package-name">Master Lesson</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                                <span class="text-muted">Slot</span>
                                <p class="text-dark mb-1 font-size-lg ml-slot-place">-</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row" id="ml-coach-place">
                    </div>
                </div>
                <div class="row p-7">
                    <div class="col-6">
                        <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                            <span class="text-muted">Tanggal</span>
                            <p class="text-dark mb-1 font-size-lg ml-date-place">-</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                            <span class="text-muted">Waktu</span>
                            <p class="text-dark mb-1 font-size-lg ml-time-place">-</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal-schedule-request-detail" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Request Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="card card-custom bg-light-secondary card-stretch gutter-b p-7 mt-0 mb-0">
                    <div class="d-flex align-items-between">
                        <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                            <span class="text-muted">Class</span>
                            <p class="text-dark mb-1 font-size-lg request-class-name">-</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                                <span class="text-muted">Siswa</span>
                                <p class="text-dark mb-1 font-size-lg request-student-name">-</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                                <span class="text-muted">Tanggal</span>
                                <p class="text-dark mb-1 font-size-lg request-date-place">-</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                                <span class="text-muted">Waktu</span>
                                <p class="text-dark mb-1 font-size-lg request-time-place">-</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                                <span class="text-muted">Status</span>
                                <p class="mb-1 font-size-lg request-status-place">-</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                                <span class="text-muted">Type</span>
                                <p class="mb-1 font-size-lg request-reschedule-place">-</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn request-function-btn btn-confirm-request btn-success">Konfirmasi</button>
                <button type="button" class="btn request-function-btn btn-decline-request btn-danger">Tolak</button>
                <button type="button" class="btn btn-secondary btn-back-request" data-dismiss="modal">Kembali</button>
            </div>
        </div>
    </div>
</div>
