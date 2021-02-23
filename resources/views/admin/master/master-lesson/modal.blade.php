<!-- Modal-->
<div class="modal" id="modal-master-lesson" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-master-lesson" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Master Lesson</h5>
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
                        <div class="col-12">
                            <div class="form-group">
                                <label>
                                    Name
                                    <span class="text-danger">*</span>
                                </label>
                                <input required type="text" name="name" class="form-control" placeholder="Name"/>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>
                                    Slot
                                    <span class="text-danger">*</span>
                                </label>
                                <input required type="number" name="slot" class="form-control" placeholder="Slot"/>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>
                                    Harga
                                    <span class="text-danger">*</span>
                                </label>
                                <input required type="number" name="price" class="form-control" placeholder="Harga"/>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>
                                    Media
                                    <span class="text-danger">*</span>
                                </label>
                                <select name="platform_id" id="platform"></select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="form-group">
                                <label>
                                    Link Media
                                    <span class="text-danger">*</span>
                                </label>
                                <input required type="text" name="platform_link" class="form-control" placeholder="Link Media"/>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label>
                                    Poster
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="dropzone dropzone-default dropzone-primary" id="kt_dropzone_2">
                                    <div class="dropzone-msg dz-message needsclick">
                                        <h3 class="dropzone-msg-title">Drop files here or click to upload.</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label>Guest Star</label>
                        <div class="d-flex justify-content-start">
                            <select name="guest_id" id="guest-star"></select>
                            <button class="btn btn-primary" id="btn-add-guest" type="button">tambah</button>
                        </div>
                    </div>
                    <table class="table table-bordered mb-0 pb-0 mt-2" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="10%">Action</th>
                                <th width="90%">Name</th>
                            </tr>
                        </thead>
                        <tbody id="guest-tbody">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-loading">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
