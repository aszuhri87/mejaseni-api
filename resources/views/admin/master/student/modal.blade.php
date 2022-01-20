<!-- Modal-->
<div class="modal" id="modal-student" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form id="form-student" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-4 ">
                            <div class="text-center mb-3">
                                <span class="text-muted"> Input Avatar </span>
                            </div>
                            <div class="image-input image-input-empty image-input-outline"
                                id="kt_user_edit_avatar" style="margin-left: 20%;">
                                <div class="image-input-wrapper image">
                                    <img src="{{ url('/assets/images/profile.png') }}" class="img-profile-edit rounded" style="width:194px !important; height:194px !important;">
                                </div>

                                <label
                                    class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                    data-action="change" data-toggle="tooltip" title=""
                                    data-original-title="Change avatar">
                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                    <input type="file" name="profile_avatar"
                                        accept=".png, .jpg, .jpeg" class="upload"/>
                                </label>

                                <span
                                    class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                    data-action="cancel" data-toggle="tooltip"
                                    title="Cancel avatar">
                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                </span>

                                <span
                                    class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                    data-action="remove" data-toggle="tooltip"
                                    title="Remove avatar">
                                    <i class="ki ki-bold-close icon-xs text-muted"></i>
                                </span>
                            </div>
                            <div class="text-center mt-3">
                                <span class="form-text text-muted">Allowed file types: png, jpg, jpeg.</span>
                            </div>
                        </div>
                        <div class="col-8 d-flex">
                            <div class="row" style="width: 100% !important">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Nama<span class="text-danger">*</span></label>
                                        <div class="name">
                                            <input type="text" class="form-control" name="name" id="name" required placeholder="Nama Lengkap">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Username<span class="text-danger">*</span></label>
                                        <div class="username">
                                            <input type="text" class="form-control" name="username" id="username" required placeholder="Username">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-6">
                                    <label>
                                        Expertise
                                        <span class="text-danger d-none">*</span>
                                    </label>
                                    <select class="form-control" id="expertise" name="expertise">
                                        @foreach ($expertise as $key => $item)
                                            <option value="{{ $key }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Email<span class="text-danger">*</span></label>
                                        <div class="email">
                                            <input type="text" class="form-control" name="email" id="email" required placeholder="Email">
                                        </div>
                                    </div>
                                </div>
                                <div class="password-setting col-12">

                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label>Phone<span class="text-danger">*</span></label>
                                        <div class="phone">
                                            <input type="text" class="form-control" name="phone" id="phone" required placeholder="Phone">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <hr> --}}
                    {{-- <div class="row">
                        <div class="col-6">
                            <table class="table table-separate table-head-custom mb-0 pb-0" width="100%" id="init-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th width="30%">Nama</th>
                                        <th width="20%">Jumlah Class</th>
                                        <th width="20%">Expertise</th>
                                        <th width="20%">Status</th>
                                        <th width="10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-6">
                            <table class="table table-separate table-head-custom mb-0 pb-0" width="100%" id="init-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th width="30%">Nama</th>
                                        <th width="20%">Jumlah Class</th>
                                        <th width="20%">Expertise</th>
                                        <th width="20%">Status</th>
                                        <th width="10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-loading">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="modal_permission" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" id="form_permission">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">student</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-xs" id="tree-table" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-left" width="30%">Menu</th>
                                    <th width="5%">Akses</th>
                                    <th width="5%">Lihat</th>
                                    <th width="5%">Tambah</th>
                                    <th width="5%">Edit</th>
                                    <th width="5%">Hapus</th>
                                    <th width="5%">Print</th>
                                    <th >Lain-lain</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <div class="init-btn-loading">
                        <button type="submit" class="btn btn-primary btn-loading">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
