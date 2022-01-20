<!-- Modal-->
<div class="modal" id="modal-class-detail" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Review Class</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>
                                Name
                            </label><br>
                            <h5 id="name"></h5>
                        </div>
                        <div class="form-group">
                            <label>
                                Date
                            </label><br>
                            <h5 id="date"></h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>
                                Classroom
                            </label><br>
                            <h5 id="classroom">{{$classroom->name}}</h5>
                        </div>
                        <div class="form-group">
                            <label>
                                Time
                            </label><br>
                            <h5 id="time"></h5>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 d-flex justify-content-center">
                        <input id="input-id" name="rating" type="number" class="kv-ltr-theme-svg-star rating-loading" dir="ltr" data-size="md">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <textarea name="description" id="description" cols="30" rows="5" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
