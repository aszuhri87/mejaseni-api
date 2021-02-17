<!-- Modal-->
<div class="modal" id="modal-session-video-detail" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="form-session-video-detail" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Materi Video</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="session_video_id" value="{{$data->id}}">
                    <div class="form-group">
                        <label>
                            Nama
                            <span class="text-danger">*</span>
                        </label>
                        <input required type="text" name="name" class="form-control" placeholder="Nama"/>
                    </div>
                    <div class="form-group">
                        <span class="switch switch-sm">
                            <label>
                                <input type="checkbox" id="switch-youtube" name="is_youtube"/>
                                <span></span>
                            </label>
                            Video Youtube
                        </span>
                    </div>
                    <div class="form-group file-upload" style="display: none;">
                        <label>
                            File
                            <span class="text-danger d-none">*</span>
                        </label>
                        <div class="dropzone dropzone-default dropzone-primary" id="kt_dropzone_2">
                            <div class="dropzone-msg dz-message needsclick">
                                <h3 class="dropzone-msg-title">Drop files here or click to upload.</h3>
                            </div>
                        </div>
                    </div>
                    <div class="form-group url-input" style="display: none;">
                        <label>
                            Youtube Embed Url
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="url" id="i-url" class="form-control" placeholder="Youtube Embed Url"/>
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
