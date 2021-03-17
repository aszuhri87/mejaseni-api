<!-- Modal-->
<div class="modal" id="modal-question" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-question" autocomplete="off">
                <input type="hidden" name="id">
                <div class="modal-header">
                    <h5 class="modal-title">Question</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>
                            Name
                        </label>
                        <input required type="text" name="name" class="form-control" placeholder="Name"/>
                    </div>
                    <div class="form-group">
                        <label>
                            Phone
                        </label>
                        <input required type="text" name="phone" class="form-control" placeholder="Phone"/>
                    </div>
                    <div class="form-group">
                        <label>
                            Email
                        </label>
                        <input required type="email" name="email" class="form-control" placeholder="Email"/>
                    </div>
                    <div class="form-group">
                        <label>
                            Message
                        </label>
                        <textarea required name="question" class="form-control" placeholder="Message" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>
                    <button type="button" class="btn btn-primary btn-reply">Reply</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="modal-reply" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-reply" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Answer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>
                            To
                        </label>
                        <input required type="email" name="email" class="form-control" placeholder="Email"/>
                    </div>
                    <div class="form-group">
                        <label>
                            Answer
                        </label>
                        <textarea required name="answer" class="form-control" placeholder="Answer" rows="6"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>
                    <button type="submit" class="btn btn-primary btn-loading">Reply</button>
                </div>
            </form>
        </div>
    </div>
</div>