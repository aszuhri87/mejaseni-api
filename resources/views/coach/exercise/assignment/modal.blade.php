<!-- Modal-->
<div class="modal" id="modal-assignment" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-assignment" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Exercise</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="classroom_id" id="classroom-id">
                    <input type="hidden" name="session_id" id="session-id">
                    <div class="row">
                        <div class="col-12 col-sm-12 col-lg-6 col-xl-6">
                            <div class="form-group">
                                <label>
                                    Name
                                    <span class="text-danger d-none">*</span>
                                </label>
                                <input required type="text" name="name" class="form-control" placeholder="Name" />
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-lg-6 col-xl-6">
                            <div class="form-group">
                                <label>
                                    Durasi Pengerjaan / Hari
                                    <span class="text-danger d-none">*</span>
                                </label>
                                <input required type="number" min="1" name="due_time" class="form-control" placeholder="Durasi Pengerjaan" />
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-lg-12 col-xl-12">
                            <div class="form-group">
                                <label>
                                    Keterangan Materi
                                    <span class="text-danger d-none">*</span>
                                </label>
                                <textarea name="description" class="form-control" placeholder="Keterangan Materi"
                                    rows="4"></textarea>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-lg-12 col-xl-12">
                            <div class="form-group">
                                <label>
                                    File
                                    <span class="text-danger d-none">*</span>
                                </label>
                                <div class="dropzone dropzone-default dropzone-primary" id="kt_dropzone_2">
                                    <div class="dropzone-msg dz-message needsclick">
                                        <h3 class="dropzone-msg-title">Drop files here or click to upload.</h3>
                                    </div>
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
