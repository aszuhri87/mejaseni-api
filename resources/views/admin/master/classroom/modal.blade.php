<!-- Modal-->
<div class="modal" id="modal-classroom" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form-classroom" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Class</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>
                                        Category Class
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="classroom_category_id" id="classroom-category"></select>
                                    <span class="text-small ml-1 text-danger required-classroom-category" style="display: none">&#8226; Harus diisi</span>
                                </div>
                                <div class="form-group">
                                    <label>
                                        Sub Category Class
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="sub_classroom_category_id" id="sub-classroom-category"></select>
                                    <span class="text-small ml-1 text-danger required-sub-classroom-category" style="display: none">&#8226; Harus diisi</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>Package</label>
                                    <div class="radio-inline mt-2">
                                        <label class="radio radio-lg">
                                            <input type="radio" checked="checked" name="package_type"/>
                                            <span></span>
                                            Reguler
                                        </label>
                                        <label class="radio radio-lg">
                                            <input type="radio" name="package_type"/>
                                            <span></span>
                                            Special
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group mt-10">
                                    <span class="switch switch-sm">
                                        <label>
                                            <input type="checkbox" checked="checked" name="select"/>
                                            <span></span>
                                        </label>
                                        Select Sub Package
                                    </span>
                                    <div class="radio-inline mt-2">
                                        <label class="radio radio-lg">
                                            <input type="radio" name="sub_package_type"/>
                                            <span></span>
                                            Reguler
                                        </label>
                                        <label class="radio radio-lg">
                                            <input type="radio" name="sub_package_type"/>
                                            <span></span>
                                            Special
                                        </label>
                                        <label class="radio radio-lg">
                                            <input type="radio" name="sub_package_type"/>
                                            <span></span>
                                            Special
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <hr>
                        <div class="form-group">
                            <label>
                                Name
                                <span class="text-danger">*</span>
                            </label>
                            <input required type="text" name="name" class="form-control" placeholder="Name"/>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label>
                                        Picture
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div id="image">
                                        <input type="file" name="image" class="dropify" data-height="10"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>
                                                Name
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input required type="text" name="name" class="form-control" placeholder="Name"/>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>
                                                Name
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input required type="text" name="name" class="form-control" placeholder="Name"/>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>
                                                Name
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input required type="text" name="name" class="form-control" placeholder="Name"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>
                                Name
                                <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" name="" id="" rows="4"></textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <hr>
                        <div class="form-group">
                            <label>Tools</label>
                            <div class="input-group">
                                <input type="text" class="form-control tools" placeholder="Tools"/>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" id="btn-add-tools" type="button">tambah</button>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered mb-0 pb-0 mt-2" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center" width="10%">Action</th>
                                    <th width="90%">Name</th>
                                </tr>
                            </thead>
                            <tbody id="tools-tbody">

                            </tbody>
                        </table>
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
