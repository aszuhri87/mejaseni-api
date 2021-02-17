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
                    <div class="form-group col-12">
                        <label>
                            Password
                            <span class="text-danger d-none">*</span>
                        </label>
                        <input required type="password" name="password" class="form-control" placeholder="Password" />
                    </div>
                    <div class="form-group  col-12">
                        <label>Tipe Admin</label>

                        <div class="radio-inline">
                            @foreach ($role as $key => $item)
                                <label class="radio">
                                    <input type="radio" selected name="role[]" value="{{ $key }}">
                                    <span></span>{{ $item }}
                                </label>
                            @endforeach
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
