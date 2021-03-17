<!-- Modal-->
<div class="modal" id="modal-session-video" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-session-video" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Video</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>
                            Category Class
                            <span class="text-danger">*</span>
                        </label>
                        <select name="classroom_category_id" id="classroom-category"></select>
                        <span class="text-small ml-1 text-danger required-classroom-category" style="display: none">&#8226; Harus diisi</span>
                    </div>
                    <div class="form-group">
                        <label>
                            Sub Category Class
                            <span class="text-danger">*</span>
                        </label>
                        <select name="sub_classroom_category_id" id="sub-classroom-category"></select>
                        <span class="text-small ml-1 text-danger required-classroom-category" style="display: none">&#8226; Harus diisi</span>
                    </div>
                    <div class="form-group">
                        <label>
                            Nama
                            <span class="text-danger">*</span>
                        </label>
                        <input required type="text" name="name" class="form-control" placeholder="Nama"/>
                    </div>
                    <div class="form-group">
                        <label>
                            Biaya Paket
                            <span class="text-danger">*</span>
                        </label>
                        <input required type="text" name="price" class="form-control" placeholder="Biaya Paket"/>
                    </div>
                    <div class="form-group">
                        <label>
                            Deskripsi
                            <span class="text-danger">*</span>
                        </label>
                        <textarea name="description" class="form-control" placeholder="Deskripsi" rows="4"></textarea>
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
                            Coach
                            <span class="text-danger">*</span>
                        </label>
                        <select name="coach_id" id="coach-select"></select>
                        <span class="text-small ml-1 text-danger required-classroom-category" style="display: none">&#8226; Harus diisi</span>
                    </div>
                    <div class="form-group">
                        <label>
                            Expertise
                            <span class="text-danger">*</span>
                        </label>
                        <select name="expertise_id" id="expertise-select"></select>
                        <span class="text-small ml-1 text-danger required-classroom-category" style="display: none">&#8226; Harus diisi</span>
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
