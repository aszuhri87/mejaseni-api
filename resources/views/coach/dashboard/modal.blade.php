<!-- Modal Detail-->
<div class="modal" id="modal-review-last-class" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-review-last-class" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Berikan Review</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6 col-sm-6 col-lg-6 col-xl-6">
                            <div class="d-flex align-items-center rounded p-5">
                                <!--begin::Title-->
                                <div class="d-flex flex-column flex-grow-1 mr-2">
                                    <span class="text-muted font-weight-bold">Class</span>
                                    <span
                                        class="font-weight-bold text-dark-50 text-hover-muted font-size-lg mb-1 classroom-name">-</span>
                                </div>
                                <!--end::Title-->
                            </div>
                        </div>
                        <div class="col-6 col-sm-6 col-lg-6 col-xl-6">
                            <div class="d-flex align-items-center rounded p-5">
                                <!--begin::Title-->
                                <div class="d-flex flex-column flex-grow-1 mr-2">
                                    <span class="text-muted font-weight-bold">Student</span>
                                    <span
                                        class="font-weight-bold text-dark-50 text-hover-muted font-size-lg mb-1 student-name">-</span>
                                </div>
                                <!--end::Title-->
                            </div>
                        </div>
                        <div class="col-6 col-sm-6 col-lg-6 col-xl-6">
                            <div class="d-flex align-items-center rounded p-5">
                                <!--begin::Title-->
                                <div class="d-flex flex-column flex-grow-1 mr-2">
                                    <span class="text-muted font-weight-bold">Date</span>
                                    <span
                                        class="font-weight-bold text-dark-50 text-hover-muted font-size-lg mb-1 date">-</span>
                                </div>
                                <!--end::Title-->
                            </div>
                        </div>
                        <div class="col-6 col-sm-6 col-lg-6 col-xl-6">
                            <div class="d-flex align-items-center rounded p-5">
                                <!--begin::Title-->
                                <div class="d-flex flex-column flex-grow-1 mr-2">
                                    <span class="text-muted font-weight-bold">Time</span>
                                    <span
                                        class="font-weight-bold text-dark-50 text-hover-muted font-size-lg mb-1 time">-</span>
                                </div>
                                <!--end::Title-->
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="row mb-5 overflow-auto feedback" style="height:200px !important">
                                Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptates reiciendis minima
                                error ullam quaerat quibusdam ipsam distinctio atque officiis cum non quod numquam
                                adipisci veritatis, nesciunt consequatur voluptatem nostrum aspernatur?
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="row py-5">
                                <div class="col-12 col-sm-12 col-lg-12 col-xl-12"
                                    style="display: grid;justify-content: center;">
                                    <div class="container">
                                        <div class="star-widget text-center">
                                            <input type="radio" value="5" name="rate" id="rate-5">
                                            <label for="rate-5" class="fas fa-star"></label>

                                            <input type="radio" value="4" name="rate" id="rate-4">
                                            <label for="rate-4" class="fas fa-star"></label>

                                            <input type="radio" value="3" name="rate" id="rate-3">
                                            <label for="rate-3" class="fas fa-star"></label>

                                            <input type="radio" value="2" name="rate" id="rate-2">
                                            <label for="rate-2" class="fas fa-star"></label>

                                            <input type="radio" value="1" name="rate" id="rate-1">
                                            <label for="rate-1" class="fas fa-star"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-lg-12 col-xl-12 mt-5">
                                    <blockquote class="blockquote text-center text-dark-50 font-weight-normal">
                                        <p class="mb-0">Masukkan komentar Anda tentang "pertemuan ke 5"</p>
                                    </blockquote>
                                </div>
                                <div class="col-12 col-sm-12 col-lg-12 col-xl-12">
                                    <div class="form-group mb-1">
                                        <textarea class="form-control" name="feedback" rows="8"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-loading">Kirim Exercise</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Detail-->
<div class="modal" id="modal-review" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-review" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Student Review</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-5 overflow-auto feedback-student" style="height:500px !important">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-loading">Kirim Exercise</button>
                </div>
            </form>
        </div>
    </div>
</div>
