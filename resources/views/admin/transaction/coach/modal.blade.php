<!-- Modal-->
<div class="modal" id="modal-confirm" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="" id="form-confirm">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card card-custom bg-light-secondary card-stretch gutter-b p-7">
                        <div class="row">
                            <div class="col-6">
                                <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                                    <span class="text-muted">Nama Coach</span>
                                    <p class="text-dark mb-1 font-size-lg coach-name-place">-</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                                    <span class="text-muted">Nama Pemilik Rekening</span>
                                    <p class="text-dark mb-1 font-size-lg name-account-place">-</p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                                    <span class="text-muted">Bank</span>
                                    <p class="text-dark mb-1 font-size-lg bank-place">-</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                                    <span class="text-muted">No Rekening</span>
                                    <p class="text-dark mb-1 font-size-lg bank-number-place">-</p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                                    <span class="text-muted">Total</span>
                                    <p class="text-dark mb-1 font-size-lg total-place">-</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex flex-column flex-grow-1 font-weight-bold">
                                    <span class="text-muted">Tanggal</span>
                                    <p class="text-dark mb-1 font-size-lg date-place">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-5">
                        <label>
                            Bukti Transfer
                            <span class="text-danger">*</span>
                        </label>
                        <div id="image"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-primary btn-loading">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
