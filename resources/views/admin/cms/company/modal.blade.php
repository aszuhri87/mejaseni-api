<!-- Modal-->
<div class="modal" id="modal-company" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-company" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Company</h5>
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
                            Email
                            <span class="text-danger">*</span>
                        </label>
                        <input required type="email" name="email" class="form-control" placeholder="Email"/>
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
                            Vision
                            <span class="text-danger">*</span>
                        </label>
                        <textarea required name="vision" class="form-control" placeholder="Vision" rows="6"></textarea>
                    </div>
                    <div class="form-group">
                        <label>
                            Mission
                            <span class="text-danger">*</span>
                        </label>
                        <textarea required name="mission" class="form-control" placeholder="Mission" rows="6"></textarea>
                    </div>
                    <div class="form-group">
                        <label>
                            Embeded Map URL
                            <span class="text-danger">*</span>
                        </label>
                        <textarea required name="maps_url" class="form-control" placeholder="https://www.google.com/maps/embed?pb=<source>" rows="6"></textarea>
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
