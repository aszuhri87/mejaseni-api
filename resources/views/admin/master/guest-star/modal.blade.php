<!-- Modal-->
<div class="modal" id="modal-guest-star" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-guest-star" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Guest Star</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <span class="switch switch-sm">
                            <label>
                                <input type="checkbox" id="switch-coach" name="is_coach"/>
                                <span></span>
                            </label>
                            Data dari Coach
                        </span>
                    </div>
                    <hr>
                    <div class="guest-place">
                        <div class="text-center mb-3">
                            <span class="text-muted"> Input Avatar </span>
                        </div>
                        <div class="d-flex justify-content-center">
                            <div class="image-input image-input-empty image-input-outline" id="kt_user_edit_avatar">
                                <div class="image-input-wrapper image">
                                    <img src="{{ url('/assets/images/profile.png') }}" id="image-preview" class="img-profile-edit rounded" style="width:194px !important; height:194px !important;">
                                </div>
                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                    data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                    <input type="hidden" name="file" id="i-file-upload">
                                    <input type="file" id="file-upload" accept=".png, .jpg, .jpeg" class="upload"/>
                                </label>
                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                    data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                </span>

                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                    data-action="remove" data-toggle="tooltip" title="Remove avatar">
                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                </span>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <span class="form-text text-muted">Allowed file types: png, jpg, jpeg.</span>
                        </div>
                        <div class="form-group mt-2">
                            <label>
                                Name
                                <span class="text-danger">*</span>
                            </label>
                            <input required type="text" name="name" id="i-name" class="form-control" placeholder="Name"/>
                        </div>
                        <div class="form-group">
                            <label>
                                Expertise
                                <span class="text-danger">*</span>
                            </label>
                            <select name="expertise_id" id="expertise-select"></select>
                            <span class="text-small ml-1 text-danger required-classroom-category" style="display: none">&#8226; Harus diisi</span>
                        </div>
                        <div class="form-group">
                            <label>
                                Deskripsi
                                <span class="text-danger d-none">*</span>
                            </label>
                            <textarea name="description" class="form-control" id="i-description" placeholder="Deskripsi" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="coach-place" style="display: none">
                        <div class="form-group">
                            <label>
                                Coach
                                <span class="text-danger">*</span>
                            </label>
                            <select name="coach_id" id="coach-select"></select>
                            <span class="text-small ml-1 text-danger required-classroom-category" style="display: none">&#8226; Harus diisi</span>
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
