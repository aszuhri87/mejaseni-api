<div class="modal fade pr-0" id="loginRequiredModal" tabindex="-1" aria-labelledby="loginRequiredModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="column-center py-3">
                <h3>Kamu belum masuk</h3>
                <a href="{{ url('login') }}" class="btn btn-primary my-4 shadow">Masuk Sekarang</a>
                <span>Belum memiliki akun? <a href="{{ url('login') }}">Daftar Sekarang</a></span>
            </div>
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="classRegisterModal" tabindex="-1" aria-labelledby="classRegisterModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form id="form-classrom">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="p-3">
                    <h3>Daftar Kelas</h3>
                    <div class="border-line my-4"></div>
                    <div class="row">
                        <div class="col-12 pb-4">
                            <img class="w-100 rounded" id="class-image" src="{{ asset('cms/assets/img/master-lesson__banner.jpg') }}" alt="">
                        </div>
                        <div class="col-6">
                            <label>Nama Kelas</label>
                            <h5 class="mt-3 mb-4" id="classroom-name">-</h5>
                            <label>Tipe Kelas</label>
                            <h5 class="mt-3 mb-4" id="classroom-type">-</h5>
                        </div>
                        <div class="col-6">
                            <label>Total Sesi</label>
                            <h5 class="mt-3 mb-4" id="classroom-session">-</h5>
                            <label>Harga</label>
                            <h5 class="mt-3 mb-4" id="classroom-price">-</h5>
                        </div>
                    </div>

                    <div class="border-line"></div>
                    <div class="event-cart">
                        <div class="cart-added pt-4">
                            <div class="success-checkmark">
                                <div class="check-icon">
                                    <span class="icon-line line-tip"></span>
                                    <span class="icon-line line-long"></span>
                                    <div class="icon-circle"></div>
                                    <div class="icon-fix"></div>
                                </div>
                            </div>
                            <span class="ml-0 ml-lg-3 mt-3 mt-lg-0 text-center text-lg-left">Telah ditambahkan ke
                            Keranjang</span>
                        </div>
                        <input type="hidden" name="classroom_id">
                        <a id="btn-add-cart" class="addtocart btn btn-white mt-4 row-center"><img class="mr-2"
                            src="{{ asset('cms/assets/img/svg/Cart3.svg') }}" alt=""> Keranjang</a>
                            <a href="{{ url('cart') }}" class="btn btn-primary shadow mt-4">Lanjutkan ke Pembayaran</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="masterLessonRegisterModal" tabindex="-1" aria-labelledby="masterLessonRegisterModalLabel"
aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form id="form-master-lesson">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="p-3">
                    <h3>Daftar Master Lesson</h3>
                    <div class="border-line my-4"></div>
                    <div class="row">
                        <div class="col-12 pb-4">
                            <img class="w-100 rounded" id="master-lesson-banner" src="{{ asset('cms/assets/img/master-lesson__banner.jpg') }}" alt="">
                        </div>
                        <div class="col-12 pb-3">
                            <label id="master-lesson-name"></label>
                            <h5 class="mt-3 mb-4" id="master-lesson-description"></h5>
                        </div>
                        <div class="col-6">
                            <label>Platform</label>
                            <h5 class="mt-3 mb-4" id="master-lesson-platform"></h5>
                        </div>
                        <div class="col-6">
                            <label>Harga</label>
                            <h5 class="mt-3 mb-4" id="master-lesson-price"></h5>
                        </div>
                    </div>

                    <div class="border-line"></div>
                    <div class="event-cart">
                        <div class="cart-added pt-4">
                            <div class="success-checkmark">
                                <div class="check-icon">
                                    <span class="icon-line line-tip"></span>
                                    <span class="icon-line line-long"></span>
                                    <div class="icon-circle"></div>
                                    <div class="icon-fix"></div>
                                </div>
                            </div>
                            <span class="ml-0 ml-lg-3 mt-3 mt-lg-0 text-center text-lg-left">Telah ditambahkan ke
                            Keranjang</span>
                        </div>
                        <input type="hidden" name="type" value="3">
                        <input type="hidden" name="master_lesson_id">
                        <a class="addMasterLessonToCart btn btn-white mt-4 row-center"><img class="mr-2"
                            src="{{ asset('cms/assets/img/svg/Cart3.svg') }}" alt=""> Keranjang</a>
                            <a href="{{ url('cart') }}" class="btn btn-primary shadow mt-4">Lanjutkan ke Pembayaran</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

