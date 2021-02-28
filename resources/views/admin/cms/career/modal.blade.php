<!-- Modal-->
<div class="modal" id="modal-career" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-career" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Career</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Type</label>
                        <div class="radio-inline">
                            <label class="radio">
                                <input checked type="radio" name="type" value="1"/>
                                <span></span>
                                Internal Team
                            </label>
                            <label class="radio">
                                <input type="radio" name="type" value="2"/>
                                <span></span>
                                Professional Coach
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            Title
                            <span class="text-danger">*</span>
                        </label>
                        <input required type="text" name="title" class="form-control" placeholder="Title"/>
                    </div>
                    <div class="form-group">
                        <label>
                            Placement
                            <span class="text-danger">*</span>
                        </label>
                        <input required type="text" name="placement" class="form-control" placeholder="Placement"/>
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

<div class="modal" id="modal-job-description" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-job-description" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Deskripsi Pekerjaan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                            <div class="input-group">
                                <input required type="hidden" name="career_id"/>
                                <input type="text" class="form-control" name="description" placeholder="Keterangan "/>
                                <div class="input-group-append">
                                    <button class="btn btn-primary btn-loading" type="submit">Tambah</button>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <table class="table table-separate table-head-custom mb-0 pb-0" width="100%" id="init-job-description-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th width="90%">Keterangan</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="modal" id="modal-job-requirement" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-job-requirement" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Deskripsi Persyaratan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                            <div class="input-group">
                                <input required type="hidden" name="career_id"/>
                                <input type="text" class="form-control" name="description" placeholder="Keterangan "/>
                                <div class="input-group-append">
                                    <button class="btn btn-primary btn-loading" type="submit">Tambah</button>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <table class="table table-separate table-head-custom mb-0 pb-0" width="100%" id="init-job-requirement-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th width="90%">Keterangan</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                </div>
            </form>
        </div>
    </div>
</div>

