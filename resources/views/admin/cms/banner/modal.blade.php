<!-- Modal-->
<div class="modal" id="modal-banner" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-banner" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Banner</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>
                            Title
                            <span class="text-danger">*</span>
                        </label>
                        <input required type="text" name="title" class="form-control" placeholder="Title"/>
                    </div>
                    <div class="form-group">
                        <label>
                            Deskripsi
                            <span class="text-danger">*</span>
                        </label>
                        <textarea required name="description" class="form-control" placeholder="Description" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <label>
                            Image
                            <span class="text-danger">*</span>
                        </label>
                        <div id="image"></div>
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
