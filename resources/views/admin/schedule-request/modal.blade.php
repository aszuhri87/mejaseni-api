<!-- Modal-->
<div class="modal" id="modal-schedule-request" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-schedule-request" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Schedule Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Coach<span class="text-danger">*</span></label>
                        <select name="coach_id" id="select-coach">
                            <option value="">Pilih Coach</option>
                        </select>
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
