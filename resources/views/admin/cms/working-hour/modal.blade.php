<!-- Modal-->
<div class="modal" id="modal-working-hour" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-working-hour" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Working Hour</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>
                            Day
                            <span class="text-danger">*</span>
                        </label>
                        <input required type="text" name="day" readonly class="form-control" placeholder="Day"/>
                    </div>
                    <div class="form-group">
                        <label>Open<span class="text-danger">*</span></label>
                        <input type="text" readonly name="open" class="form-control timepicker" required placeholder="Time" style="width: 100% !important">
                    </div>
                    <div class="form-group">
                        <label>Close<span class="text-danger">*</span></label>
                        <input type="text" readonly name="close" class="form-control timepicker" required placeholder="Time" style="width: 100% !important">
                    </div>
                    <div class="form-group">
                        <label>
                            Status Dibuka
                            <span class="text-danger">*</span>
                        </label>
                        <span class="switch switch-icon">
                            <label>
                                <input type="checkbox" checked="checked" name="is_closed"/>
                                <span></span>
                            </label>
                        </span>
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
