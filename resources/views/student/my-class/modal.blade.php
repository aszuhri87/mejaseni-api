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
                        <div class="col-6">
                            <div class="form-group">
                                <label>Class</label>
                                <h5 id="classroom-name"></h5>
                            </div>
                            <div class="form-group">
                                <label>Date</label>
                                <h5 id="date"></h5>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Coach</label>
                                <h5 id="coach-name"></h5>
                            </div>
                            <div class="form-group">
                                <label>Time</label>
                                <h5 id="time"></h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <div id="reschedule">
                        <button type="submit" class="btn btn-outline-primary btn-loading-basic">Schedule</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="modal-review" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-review" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Berikan Review</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Class</label>
                                <h5 id="review-classroom-name"></h5>
                            </div>
                            <div class="form-group">
                                <label>Date</label>
                                <h5 id="review-date"></h5>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Coach</label>
                                <h5 id="review-coach-name"></h5>
                            </div>
                            <div class="form-group">
                                <label>Time</label>
                                <h5 id="review-time"></h5>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12 text-center">
                            <input id="input-id" name="rating" type="number" class="kv-ltr-theme-svg-star rating-loading" dir="ltr" data-size="md">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                            <span>Masukkan Komentar Anda</span>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <textarea name="commentar" id="commentar" cols="30" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <div id="btn-send-review">
                        <button type="submit" class="btn btn-primary btn-loading-rating">Kirim Review</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="modal-rating-class" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-rating-class" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Berikan Review Kelas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="classroom_id" id="rating-classroom-id">
                    <div class="row">
                        <div class="col-12 text-center">
                            <input id="input-rating-class" name="rating_class" type="number" class="kv-ltr-theme-svg-star rating-loading" dir="ltr" data-size="md">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                            <span>Masukkan Komentar Anda</span>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <textarea name="description" id="rating-commentar" cols="30" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <div id="btn-send-review">
                        <button type="submit" class="btn btn-primary btn-loading-rating">Kirim Review</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
