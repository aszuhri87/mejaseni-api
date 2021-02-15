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
                                <div class="form-group select-sub-category" style="display: none;">
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
                                            <input type="radio" value="1" name="package_type" class="radio-package"/>
                                            <span></span>
                                            Special
                                        </label>
                                        <label class="radio radio-lg">
                                            <input type="radio" value="2" name="package_type" class="radio-package"/>
                                            <span></span>
                                            Reguler
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group mt-10 switch-sub-package" style="display: none;">
                                    <span class="switch switch-sm">
                                        <label>
                                            <input type="checkbox" id="switch-sub-package" name="switch_sub"/>
                                            <span></span>
                                        </label>
                                        Select Sub Package
                                    </span>
                                    <div class="radio-inline select-sub-package mt-2" style="display: none;">
                                        <label class="radio radio-lg">
                                            <input type="radio" value="1" name="sub_package_type"/>
                                            <span></span>
                                            Basic
                                        </label>
                                        <label class="radio radio-lg">
                                            <input type="radio" value="2" name="sub_package_type"/>
                                            <span></span>
                                            Intermediate
                                        </label>
                                        <label class="radio radio-lg">
                                            <input type="radio" value="3" name="sub_package_type"/>
                                            <span></span>
                                            Advanced
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
                                Name Class
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
                                                Session
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input required type="text" name="session" class="form-control" placeholder="Session"/>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label>
                                                Duration / Session
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input required type="text" name="duration" class="form-control" placeholder="Duration"/>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>
                                                Price
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input required type="text" name="price" class="form-control" placeholder="Price"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>
                                Description
                                <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" placeholder="Description" name="description" id="" rows="4"></textarea>
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
