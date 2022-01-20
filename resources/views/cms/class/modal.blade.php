<div class="modal fade pr-0" id="modal-class" tabindex="-1" aria-labelledby="loginRequiredModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3>Kelas Yang Tersedia </h3>
                <div class="py-3">
                    <div class="row" id="class-content-modal">
                        @foreach($regular_classrooms as $regular_classroom)
                            <div class="col-lg-4 col-md-12 col-sm-12 p-2">
                                <div class="card card-custom card-shadowless h-100">
                                    <div class="card-body p-0">
                                        <div class="overlay">
                                            <div class="overlay-wrapper rounded bg-light text-center">
                                                <img src="{{ isset($regular_classroom->image_url) ? $regular_classroom->image_url : '' }}" alt="" class="mw-100 h-200px" />
                                            </div>
                                        </div>
                                        <div class="text-center mt-3 mb-md-0 mb-lg-5 mb-md-0 mb-lg-5 mb-lg-0 mb-5 d-flex flex-column p-1">
                                            <a class="font-size-h5 font-weight-bolder text-dark-75 text-hover-primary mb-1">{{ isset($regular_classroom->name) ? $regular_classroom->name : '' }}</a>
                                            <div class="d-flex flex-column"> 
                                                <p>{{ isset($regular_classroom->session_total) ? $regular_classroom->session_total : '' }} Sesi | @ {{ isset($regular_classroom->session_duration) ? $regular_classroom->session_duration : '' }}menit |
                                                  <span class="fa fa-star checked mr-1" style="font-size: 15px;"></span> {{ isset($regular_classroom->rating) ? $regular_classroom->rating < 4 ? '4.6':$regular_classroom->rating : '4.6' }}
                                              </p>
                                                <span class="mt-2">Rp.@convert($regular_classroom->price)</span>                                               
                                              </div>

                                            <a class="btn btn-primary shadow mt-5" onclick="@if (Auth::guard('student')->user()){{'showModalRegisterClassroom("'.$regular_classroom->id.'")'}}@else{{'showModalLoginRequired()'}}@endif">Daftar
                                                Sekarang
                                                <img class="ml-2" src="{{ asset('cms/assets/img/svg/Sign-in.svg') }}">
                                              </a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade pr-0" id="loginRequiredModal" tabindex="-1" aria-labelledby="loginRequiredModalLabel" aria-hidden="true">
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




