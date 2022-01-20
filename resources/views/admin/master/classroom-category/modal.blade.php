<!-- Modal-->
<div class="modal" id="modal-classroom-category" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-classroom-category" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Classroom Category</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>
                            Name
                            <span class="text-danger d-none">*</span>
                        </label>
                        <input required type="text" name="name" class="form-control" placeholder="Name"/>
                    </div>
                    <div class="form-group">
                        <label>
                            Description
                            <span class="text-danger">*</span>
                        </label>
                        <textarea required name="description" class="form-control" placeholder="Description" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <label>
                            Picture
                            <span class="text-danger">*</span>
                        </label>
                        <div id="image"></div>
                    </div>
                    <div class="form-group">
                        <label>
                            Coach Video
                        </label>
                        <select name="profile_coach_video_id" id="profile-coach-video"></select>
                    </div>
                    <div class="form-group">
                        <label>
                            Nomor Urut
                        </label>
                        <input type="number" name="number" class="form-control" placeholder="Number"/>
                    </div>
                    <span class="switch switch-sm">
                        <label>
                            <input type="checkbox" id="switch-first" name="first"/>
                            <span></span>
                        </label>
                        Tampilkan pertama kali
                    </span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-loading">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
