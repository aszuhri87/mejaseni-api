<div class="modal fade" id="modal-apply-career" tabindex="-1" aria-labelledby="classRegisterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form-apply-career" method="POST">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="p-3">
                        <h3>Apply </h3>
                        <div class="border-line my-4"></div>
                        <div class="form-group">
                            <input type="hidden" name="career_id" value="{{Request::segment(2)}}">
                            <label class="mb-2">
                                Nama
                                <span class="text-danger">*</span>
                            </label>
                            <input required type="text" name="name" class="form-control" placeholder="Nama"/>
                        </div>
                        <div class="form-group">
                            <label class="mb-2">
                                Email
                                <span class="text-danger">*</span>
                            </label>
                            <input required type="text" name="email" class="form-control" placeholder="Email"/>
                        </div>
                        <div class="form-group">
                            <label>
                                Lampiran
                                <span class="text-danger d-none">*</span>
                            </label>
                            <input type="file"
                                class="filepond mt-2"
                                name="file"
                                multiple
                                data-allow-reorder="true"
                                data-max-file-size="2MB"
                                data-max-files="10">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
