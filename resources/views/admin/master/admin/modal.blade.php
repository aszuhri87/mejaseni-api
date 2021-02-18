<!-- Modal-->
<div class="modal" id="modal-admin" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-admin" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Admin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="form-group col-12">
                        <label>
                            Name
                            <span class="text-danger d-none">*</span>
                        </label>
                        <input required type="text" name="name" class="form-control" placeholder="Name" />
                    </div>
                    <div class="form-group col-12">
                        <label>
                            Username
                            <span class="text-danger d-none">*</span>
                        </label>
                        <input required type="text" name="username" class="form-control" placeholder="Username" />
                    </div>
                    <div class="form-group col-12">
                        <label>
                            Email
                            <span class="text-danger d-none">*</span>
                        </label>
                        <input required type="email" name="email" class="form-control" placeholder="Email" />
                    </div>
                    <div class="form-group col-12 change_password">
                        <span class="switch switch-sm switch-outline switch-icon switch-primary">
                            <label>
                                <input type="checkbox" class="btn-change-password" name="change_password"
                                    id="change_password" />
                                <span></span>
                                Change password!
                            </label>
                        </span>
                    </div>
                    <div id="password" class="form-group col-12">
                        <label>
                            Password
                            <span class="text-danger d-none">*</span>
                        </label>
                        <input required type="password" name="password" class="form-control" placeholder="Password" />
                    </div>
                    <div id="password_confirmation" class="form-group col-12">
                        <label>
                            Confirm Password
                            <span class="text-danger d-none">*</span>
                        </label>
                        <input type="password" name="password_confirmation" parsley-trigger="change" required
                            placeholder="Konfirmasi Password" class="form-control">
                    </div>
                    <div class="form-group col-12 tipe_admin">
                        @foreach ($role as $key => $item)
                            <span class="switch switch-sm switch-outline switch-icon switch-primary">
                                <label>
                                    <input type="checkbox" value="{{ $key }}" class="btn-change-role"
                                        name="tipe_admin" id="tipe_admin_{{ $key }}" />
                                    <span></span>
                                    {{ $item }}
                                </label>
                            </span>
                        @endforeach
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

<div class="modal" id="modal_permission" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="POST" id="form_permission">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Admin</h5>
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
                                    <th>Lain-lain</th>
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
