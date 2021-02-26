<!-- Modal-->
<div class="modal" id="modal-invoice" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-invoice" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Invoice</h5>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="modal-reschedule" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-reschedule" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Booking Kelas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Class</label>
                                <h5 id="classroom-name"></h5>
                            </div>
                            <div class="form-group">
                                <label>Date</label>
                                <h5 id="date"></h5>
                            </div>
                            <div class="form-group">
                                <label>Time</label>
                                <h5 id="time"></h5>
                            </div>
                            <div class="form-group">
                                <label>Coach</label>
                                <h5 id="coach-name"></h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <div id="reschedule">
                        <button type="submit" class="btn btn-outline-primary btn-loading-basic">Reschedule</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
