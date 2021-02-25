<!-- Modal-->
<div class="modal" id="modal-faq" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-faq" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Frequently Asked Questions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>
                            Title
                            <span class="text-danger d-none">*</span>
                        </label>
                        <input required type="text" name="title" class="form-control" placeholder="Title"/>
                    </div>
                    <div class="form-group">
                        <label>
                            Description
                            <span class="text-danger d-none">*</span>
                        </label>
                        <textarea required name="description" class="form-control" placeholder="Description" rows="4"></textarea>
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