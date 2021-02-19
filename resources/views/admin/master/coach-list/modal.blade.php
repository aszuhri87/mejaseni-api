<!-- Modal-->
<div class="modal" id="modal-list" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-list" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jadwal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>
                                    Tanggal
                                    <span class="text-danger d-none">*</span>
                                </label>
                                <input required type="text" name="date" class="form-control datepicker" placeholder="Tanggal"/>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>
                                    Waktu
                                    <span class="text-danger d-none">*</span>
                                </label>
                                <input required type="text" name="time" class="form-control timepicker" placeholder="Waktu"/>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>
                                    Tipe Kelas
                                    <span class="text-danger d-none">*</span>
                                </label>
                                <div class="radio-inline mt-2">
                                    <label class="radio radio-lg">
                                        <input type="radio" value="1" name="class_type" id="radio1" required class="radio-package"/>
                                        <span></span>
                                        Package Class
                                    </label>
                                    <label class="radio radio-lg">
                                        <input type="radio" value="2" name="class_type" id="radio2" required class="radio-package"/>
                                        <span></span>
                                        Master Lesson
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input-package-class" style="display: none;">
                        <hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>
                                        Class
                                        <span class="text-danger d-none">*</span>
                                    </label>
                                    <select name="class" id="class"></select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>
                                        Coach
                                        <span class="text-danger d-none">*</span>
                                    </label>
                                    <input type="hidden" value="{{$data->id}}">
                                    <input type="text" class="form-control" value="{{$data->name}}" disabled>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>
                                        Media
                                        <span class="text-danger d-none">*</span>
                                    </label>
                                    <select name="platform" id="platform"></select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>
                                        Link Media
                                        <span class="text-danger d-none">*</span>
                                    </label>
                                    <input type="text" name="link_media" class="form-control" id="link_media" placeholder="Link Media Meeting">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input-master-lesson" style="display: none;">
                        <hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>
                                        Category Class
                                        <span class="text-danger d-none">*</span>
                                    </label>
                                    <select name="category" id="category"></select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>
                                        Sub Category Class
                                        <span class="text-danger d-none">*</span>
                                    </label>
                                    <select name="sub_category" id="sub-category">
                                        <option value="">Pilih Sub Category</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label>
                                        Nama Class
                                        <span class="text-danger d-none">*</span>
                                    </label>
                                    <input type="text" name="class_name" id="class-name" class="form-control" placeholder="Nama Class">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>
                                        Upload Poster Acara
                                        <span class="text-danger d-none">*</span>
                                    </label>
                                    <input type="file" name="class_name" id="class-name" class="form-control dropify">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>
                                                Slot Siswa
                                                <span class="text-danger d-none">*</span>
                                            </label>
                                            <input type="text" name="quota_student" id="quota-student" class="form-control" placeholder="Slot Siswa">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>
                                                Harga (Per Siswa)
                                                <span class="text-danger d-none">*</span>
                                            </label>
                                            <input type="number" name="price" id="price" class="form-control" placeholder="Harga Per Siswa">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>
                                                Media
                                                <span class="text-danger d-none">*</span>
                                            </label>
                                            <select name="platform_master_lesson" id="platform-master-lesson"></select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>
                                                Link Media
                                                <span class="text-danger d-none">*</span>
                                            </label>
                                            <input type="text" name="link_media" class="form-control" id="link-media-master-lesson" placeholder="Link Media Meeting">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
