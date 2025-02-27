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
                            Question
                            <span class="text-danger">*</span>
                        </label>
                        <input required type="text" name="title" class="form-control" placeholder="Question"/>
                    </div>
                    <div class="form-group">
                        <label>
                            Answer
                            <span class="text-danger">*</span>
                        </label>
                        <textarea required name="description" class="form-control" placeholder="Answer" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <label>
                            Nomor Urut
                        </label>
                        <input type="number" name="number" class="form-control" placeholder="Number"/>
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
