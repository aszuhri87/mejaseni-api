<!-- Modal-->
<div class="modal" id="modal-classroom" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-classroom" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Review Classroom</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>
                            Category
                            <span class="text-danger">*</span>
                        </label>
                        <input readonly type="text" name="category" class="form-control" placeholder="Category"/>
                    </div>
                    <div class="form-group">
                        <label>
                            Class
                            <span class="text-danger">*</span>
                        </label>
                        <select name="classroom_id" id="classroom"></select>
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
