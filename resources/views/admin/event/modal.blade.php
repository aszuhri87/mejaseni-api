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
                            Category Class
                            <span class="text-danger">*</span>
                        </label>
                        <select name="classroom_category_id" id="classroom-category"></select>
                        <span class="text-small ml-1 text-danger required-classroom-category" style="display: none">&#8226; Harus diisi</span>
                    </div>
                    <div class="form-group">
                        <label>
                            Title
                            <span class="text-danger">*</span>
                        </label>
                        <input required type="text" name="title" class="form-control" placeholder="Title"/>
                    </div>
                    <div class="form-group">
                        <label>Date<span class="text-danger">*</span></label>
                        <input type="text" name="date" id="date-range" class="form-control select_daterange" readonly required placeholder="Date" style="width: 100% !important">
                        <span class="text-small ml-1 text-danger required-date-range" style="display: none">&#8226; Harus diisi</span>
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


<div class="modal" id="modal-participants" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Participants</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-end align-items-center">
                    <div class="my-toolbar d-flex">
                        <div class="form-group ml-1">
                            <div class="input-icon">
                                <input type="text" class="form-control" id="searchParticipants" placeholder="Search..." />
                                <span>
                                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                <path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero"/>
                                            </g>
                                        </svg>
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-separate table-head-custom mb-0 pb-0" width="100%" id="init-participants-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th width="25%">Image</th>
                            <th width="30%">Name</th>
                            <th width="20%">Email</th>
                            <th width="15%">Phone</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
