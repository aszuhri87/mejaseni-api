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
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Tanggal<span class="text-danger">*</span></label>
                                <input type="text" name="date" class="form-control datepicker" required placeholder="Date" style="width: 100% !important">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>Waktu<span class="text-danger">*</span></label>
                                <input type="text" name="time" class="form-control timepicker" required placeholder="Time" style="width: 100% !important">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
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
                    <hr>
                    <div class="row form-package">
                        <div class="col-sm-12 parent-sub-category">
                            <div class="form-group">
                                <label>
                                    Category Class
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="classroom_category_id" id="classroom-category"></select>
                                <span class="text-small ml-1 text-danger required-classroom-category" style="display: none">&#8226; Harus diisi</span>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12 select-sub-category" style="display: none;">
                            <div class="form-group">
                                <label>
                                    Sub Category Class
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="sub_classroom_category_id" id="sub-classroom-category"></select>
                            </div>
                        </div>
                        <div class="col-12 select-classroom">
                            <div class="form-group">
                                <label>
                                    Class
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="classroom_id" id="classroom"></select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>
                                    Coach
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="coach_id" id="coach"></select>
                            </div>
                        </div>
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
                                    <input type="text" class="form-control" name="name" required placeholder="Nama">
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
