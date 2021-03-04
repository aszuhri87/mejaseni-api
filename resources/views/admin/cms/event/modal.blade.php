<!-- Modal-->
<div class="modal" id="modal-event" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-event" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Event</h5>
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
                        <label>Date<span class="text-danger">*</span></label>
                        <input type="text" name="date" class="form-control select_daterange" readonly required placeholder="Date" style="width: 100% !important">
                    </div>
                    <div class="form-group">
                        <label>
                            Free ?
                            <span class="text-danger">*</span>
                        </label>
                        <span class="switch switch-icon">
                            <label>
                                <input type="checkbox" checked="checked" name="is_free"/>
                                <span></span>
                            </label>
                        </span>
                    </div>
                    <div class="form-group">
                        <label>
                            Price
                            <span class="text-danger">*</span>
                        </label>
                        <input required type="number" disabled name="total" class="form-control" value="0" placeholder="Price"/>
                    </div>
                    <div class="form-group">
                        <label>
                            Quota
                            <span class="text-danger">*</span>
                        </label>
                        <input required type="number" name="quota" class="form-control" min="1" placeholder="Quota"/>
                    </div>
                    <div class="form-group">
                        <label>
                            Location
                            <span class="text-danger">*</span>
                        </label>
                        <textarea required name="location" class="form-control" placeholder="Location" rows="4"></textarea>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-loading">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
