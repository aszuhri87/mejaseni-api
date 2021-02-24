<!-- Modal-->
<div class="modal" id="modal-exercise" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-exercise" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <span id="title-exercise"></span> Exercise</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="name" id="name">
                    <input type="hidden" name="assignment_id" id="assignment_id">
                    <div class="form-group d-block">
                        <label>
                            File
                        </label>
                        <div class="dropzone dropzone-default dropzone-primary" id="kt_dropzone_2">
                            <div class="dropzone-msg dz-message needsclick">
                                <h3 class="dropzone-msg-title">Drop files here or click to upload.</h3>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            Description
                        </label>
                        <textarea name="description" id="description" placeholder="Masukan keterangan" required class="form-control" cols="30" rows="6"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-btn-submit">
                        <button type="submit" class="btn btn-primary btn-loading-exercise">
                            Kirim Exercise
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="modal-result" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Result Exercise</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div id="file-collection">

                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div id="description-collection">

                        </div>
                    </div>
                </div>
                <div class="separator separator-dashed mt-8 mb-5"></div>
                <div class="row">
                    <div class="col-2" id="img-coach">

                    </div>
                    <div class="col-10">
                        <h4 id="coach-name"></h4>
                        <div id="star"></div>

                        <p id="description-feedback">

                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="form-btn-submit">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
