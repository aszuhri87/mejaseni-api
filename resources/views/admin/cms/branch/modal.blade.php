<!-- Modal-->
<div class="modal" id="modal-branch" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-branch" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Branch</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>
                            Name
                            <span class="text-danger">*</span>
                        </label>
                        <input required type="text" name="name" class="form-control" placeholder="Name"/>
                    </div>
                    <div class="form-group">
                        <label>
                            Telephone
                            <span class="text-danger">*</span>
                        </label>
                        <input required type="text" name="telephone" class="form-control" placeholder="Telephone"/>
                    </div>
                    <div class="form-group">
                        <label>
                            Address
                            <span class="text-danger">*</span>
                        </label>
                        <textarea required name="address" class="form-control" placeholder="Address" rows="4"></textarea>
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
