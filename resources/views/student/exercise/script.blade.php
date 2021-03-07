<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var docsDropzone;
            var arr_path = [];
            var init_collection_file = [];
            $(document).ready(function() {
                formSubmit();
                initAction();
                getExercise();
                getClass();
                initDatePicker();
                initDropzone();
            });

            const initDatePicker = () => {
                if (KTUtil.isRTL()) {
                    arrows = {
                        leftArrow: '<i class="la la-angle-right"></i>',
                        rightArrow: '<i class="la la-angle-left"></i>'
                    }
                } else {
                    arrows = {
                        leftArrow: '<i class="la la-angle-left"></i>',
                        rightArrow: '<i class="la la-angle-right"></i>'
                    }
                }
                $('.datepicker').datepicker({
                    rtl: KTUtil.isRTL(),
                    todayHighlight: true,
                    orientation: "bottom left",
                    templates: arrows,
                    format:'d MM yyyy'
                });
            },
            initAction = () => {
                $(document).on('change','#filter-class',function(event){
                    event.preventDefault();
                    KTApp.blockPage({
                        overlayColor: '#000000',
                        state: 'primary',
                        opacity: 0.5,
                        message: 'Processing...'
                    });
                    getExercise($(this).val())
                });

                $(document).on('change','#date_end',function(event){
                    event.preventDefault();
                    let date_end = $(this).val();
                    let date_start = $('#date_start').val();
                    let classroom_id = $('#filter-class').val();
                    if(date_end && date_start){
                        if(moment(date_end).isBefore(date_start)){
                            return toastr.error('Filter date invalid', 'Failed')
                        }else{
                            KTApp.blockPage({
                                overlayColor: '#000000',
                                state: 'primary',
                                opacity: 0.5,
                                message: 'Processing...'
                            });
                            getExercise(classroom_id,date_start,date_end)
                        }
                    }
                });

                $(document).on('click','.btn-detail',function(event){
                    event.preventDefault();
                    let collection_id = $(this).data('collection_id');

                    $('#form-exercise').trigger("reset");
                    $('#form-exercise').attr('action',`{{url('student/exercise/update')}}/${collection_id}`);
                    $('#form-exercise').attr('method','POST');

                    $('#title-exercise').html('Edit');

                    $.ajax({
                        url: `{{url('student/exercise/get-collection')}}/${collection_id}`,
                        type: 'GET',
                    })
                    .done(function(res, xhr, meta) {
                        if(res.status == 200){
                            init_collection_file = [];
                            docsDropzone.removeAllFiles();
                            $.each(res.data.collection_files, function(index, data){
                                var mockFile = {
                                    name: data.file_url,
                                    collection_file_id: data.id,
                                    accepted: true
                                };

                                init_collection_file.push(data.id);

                                docsDropzone.files.push(mockFile);
                                docsDropzone.emit("addedfile", mockFile);
                                docsDropzone.emit("thumbnail", mockFile, data.file_url);
                                docsDropzone.emit("complete", mockFile);
                            })
                            $('#description').val(res.data.description);
                        }
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {

                    });
                    showModal('modal-exercise');
                });

                $(document).on('click','.btn-submit',function(event){
                    event.preventDefault();
                    init_collection_file = [];
                    docsDropzone.removeAllFiles();
                    let name = $(this).data('name');
                    let assignment_id = $(this).data('assignment_id');

                    $('#form-exercise').trigger("reset");
                    $('#form-exercise').attr('action',`{{url('student/exercise/store')}}`);
                    $('#form-exercise').attr('method','POST');

                    $('#name').val(name);
                    $('#assignment_id').val(assignment_id);
                    $('#title-exercise').html('Upload');

                    showModal('modal-exercise');
                });

                $(document).on('click','.btn-see-result',function(event){
                    event.preventDefault();
                    let collection_id = $(this).data('collection_id');
                    getResult(collection_id);
                    showModal('modal-result');
                });
            },
            formSubmit = () => {
                $('#form-exercise').submit(function(event){
                    event.preventDefault();
                    let form_data = new FormData(this)

                    if(arr_path.length > 0){
                        $.each(arr_path, function(index, data){
                            form_data.append('file[]', arr_path[index]['path']);
                        })
                    }

                    btn_loading_exercise('start')
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: form_data,
                        contentType: false,
                        cache: false,
                        processData: false,
                    })
                    .done(function(res, xhr, meta) {
                        if(res.status == 200){
                            toastr.success(res.message, 'Success')
                            arr_path = [];
                            hideModal('modal-exercise');
                            getExercise();
                        }
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading_exercise('stop', 'Kirim Excercise')
                    });
                });
            },
            getResult = (collection_id) => {
                $.ajax({
                    url: `{{ url('student/exercise/get-result-exercise') }}/${collection_id}`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    if(res.status == 200){
                        let element = ``;
                        let star = ``;

                        $.each(res.data.file, function(index, data){
                            element += `<a href="${data.file_url}" class="btn btn-outline-primary mr-2" target="_blank">${data.name}</a>`;
                        });
                        for (i = 0; i < res.data.star; i++) {
                            star += `
                                <span class="svg-icon svg-icon-warning svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-02-01-052524/theme/html/demo1/dist/../src/media/svg/icons/General/Star.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24"/>
                                        <path d="M12,18 L7.91561963,20.1472858 C7.42677504,20.4042866 6.82214789,20.2163401 6.56514708,19.7274955 C6.46280801,19.5328351 6.42749334,19.309867 6.46467018,19.0931094 L7.24471742,14.545085 L3.94038429,11.3241562 C3.54490071,10.938655 3.5368084,10.3055417 3.92230962,9.91005817 C4.07581822,9.75257453 4.27696063,9.65008735 4.49459766,9.61846284 L9.06107374,8.95491503 L11.1032639,4.81698575 C11.3476862,4.32173209 11.9473121,4.11839309 12.4425657,4.36281539 C12.6397783,4.46014562 12.7994058,4.61977315 12.8967361,4.81698575 L14.9389263,8.95491503 L19.5054023,9.61846284 C20.0519472,9.69788046 20.4306287,10.2053233 20.351211,10.7518682 C20.3195865,10.9695052 20.2170993,11.1706476 20.0596157,11.3241562 L16.7552826,14.545085 L17.5353298,19.0931094 C17.6286908,19.6374458 17.263103,20.1544017 16.7187666,20.2477627 C16.5020089,20.2849396 16.2790408,20.2496249 16.0843804,20.1472858 L12,18 Z" fill="#000000"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                            `;
                        }

                        $('#file-collection').html(element);
                        $('#description-collection').html(res.data.description);
                        $('#coach-name').html(res.data.coach_name);
                        $('#star').html(star);
                        $('#description-feedback').html(res.data.feedback_description);
                        $('#img-coach').html(`<img src="${res.data.image}" alt="" width="100px" height="100px" class="rounded">`);


                    }
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            },
            getExercise = (classroom_id,date_start,date_end) =>{
                $.ajax({
                    url: `{{ url('student/exercise/get-exercise') }}`,
                    type: `GET`,
                    data:{
                        classroom_id:classroom_id,
                        date_start:date_start,
                        date_end:date_end,
                    }
                })
                .done(function(res, xhr, meta) {
                    if(res.status == 200){
                        let element = ``;
                        if(res.data.length > 0){
                            $.each(res.data, function(index, data){
                                element += `
                                <div class="col-lg-4 mb-5">
                                    <div class="card">
                                        <div class="card-header ribbon ribbon-clip ribbon-right">
                                            `;
                                            if(data.status_collection==1 && data.status_collection_feedback==0){
                                                element += `
                                                    <div class="ribbon-target" style="top: 12px;">
                                                        <span class="ribbon-inner bg-warning"></span>Reviewed
                                                    </div>
                                                    `;
                                            }
                                            else if(data.status_collection==1 && data.status_collection_feedback==1){
                                                element += `
                                                    <div class="ribbon-target" style="top: 12px;">
                                                        <span class="ribbon-inner bg-success"></span>Done
                                                    </div>
                                                    `;
                                            }
                                        element += `
                                            <div class="row">
                                                <div class="col-12 d-flex">
                                                    <div>
                                                        <img src="{{asset('assets/images/play.png')}}" width="50px" height="50px">
                                                    </div>
                                                    <div class="ml-5">
                                                        <p style="margin-bottom: 0 !important"><strong>${data.name}</strong></p>
                                                        <span class="text-muted">${data.classroom_name}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-5">
                                                <div class="col text-justify overflow-auto" style="height:80px !important">
                                                    ${data.description}
                                                </div>
                                            </div>
                                            <div class="row mb-5">
                                                <div class="col">
                                                    <table class="bordered-less">
                                                        <tr>
                                                            <td width="100px"><span class="text-muted">Coach</span></td>
                                                            <td>${data.coach_name}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="100px"><span class="text-muted">Upload At</span></td>
                                                            <td>${moment(data.upload_date).format('DD MMMM YYYY')}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="100px"><span class="text-muted">Due Date</span></td>
                                                            <td>${moment(data.due_date).format('DD MMMM YYYY')}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            `;
                                            if(data.status_collection == 0){
                                                if(moment(moment().format('YYYY-MM-DD')).isAfter(moment(data.due_date).format('YYYY-MM-DD'))){
                                                    element += `
                                                        <div class="row">
                                                            <div class="col-12 d-flex justify-content-end">
                                                                <button type="button" class="btn btn-danger">Not Submited</button>
                                                            </div>
                                                        </div>
                                                    `;
                                                }
                                                else{
                                                    element += `
                                                        <div class="row">
                                                            <div class="col-12 d-flex justify-content-end">
                                                                <button type="button" class="btn btn-primary btn-submit" data-assignment_id="${data.assignment_id}" data-name="${data.name}">Submit</button>
                                                            </div>
                                                        </div>
                                                    `;
                                                }
                                            }else{
                                                if(data.status_collection_feedback == 0){
                                                    element += `
                                                    <div class="row">
                                                        <div class="col-12 d-flex justify-content-end">
                                                            <button type="button" class="btn btn-secondary btn-detail" data-collection_id="${data.collection_id}">Detail</button>
                                                        </div>
                                                    </div>
                                                    `;
                                                }else{
                                                    element += `
                                                    <div class="row">
                                                        <div class="col-12 d-flex justify-content-end">
                                                            <button type="button" class="btn btn-success btn-see-result" data-collection_id="${data.collection_id}">See Result</button>
                                                        </div>
                                                    </div>
                                                    `;
                                                }
                                            }
                                            element +=`
                                        </div>
                                    </div>
                                </div>
                                `;
                            })
                        }
                        else{
                            element = `
                                    <div class="col-12">
                                        <div class="card" style="width:100%">
                                            <div class="card-body text-center">
                                                <h4 class="text-muted">Exercise Not Available</h4>
                                            </div>
                                        </div>
                                    </div>
                            `
                        }
                        KTApp.unblockPage();
                        $('.exercise').html(element);
                    }
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            },
            getClass = () => {
                $.ajax({
                    url: `{{ url('student/exercise/get-class') }}/{{Auth::guard('student')->user()->id}}`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    element = `<option value="">Pilih Kelas</option>`;

                    $.each(res.data, function(index, data){
                        element += `<option value="${data.classroom_id}">${data.classroom_name}</option>`;
                    });

                    $('#filter-class').html(element);
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            }

            const initDropzone = () => {
                docsDropzone = new Dropzone("#kt_dropzone_2", {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('student/exercise/exercise-file') }}",
                    paramName: "file",
                    maxFiles: 10,
                    maxFilesize: 2,
                    uploadMultiple: true,
                    addRemoveLinks: true,
                    init: function() {
                        this.on("maxfilesexceeded", function(file) {
                            this.removeAllFiles();
                            this.addFile(file);
                        });
                    },
                    success: function(file, response) {
                        arr_path.push({
                            id: file.upload.uuid,
                            path_id: response.data.id,
                            path: response.data.path
                        })
                        $('.dz-remove').addClass('dz-style-custom');
                    },
                    removedfile: function(file) {
                        if(init_collection_file.length > 0){
                            $.each(init_collection_file, function(index, value) {
                                if (file.collection_file_id == value) {
                                    Swal.fire({
                                        title: 'Delete File?',
                                        text: "Deleted File will be permanently lost!",
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#7F16A7',
                                        confirmButtonText: 'Yes, Delete',
                                    }).then(function (result) {
                                        if (result.value) {
                                            $.ajax({
                                                url: `{{ url('student/exercise/exercise-file/delete') }}/${value}` ,
                                                type: 'DELETE',
                                                dataType: 'json',
                                            })
                                            .done(function(res, xhr, meta) {
                                                var _ref;
                                                return (_ref = file.previewElement) != null ? _ref.parentNode
                                                    .removeChild(file.previewElement) : void 0;
                                            })
                                            .fail(function(res, error) {
                                                toastr.error(res.responseJSON.message,
                                                    'Failed')
                                            })
                                        }
                                    })
                                    $('.swal2-title').addClass('justify-content-center')
                                }
                            });
                        }

                        $.each(arr_path, function(index, arr_data) {
                            if (file.upload.uuid == arr_data['id']) {
                                arr_path.splice(index, 1);
                                $.ajax({
                                    url: "{{ url('student/exercise/exercise-file') }}/" +
                                        arr_data['path_id'],
                                    type: 'DELETE',
                                    dataType: 'json',
                                })
                                .done(function(res, xhr, meta) {
                                    // var _ref;
                                    // return (_ref = file.previewElement) != null ? _ref.parentNode
                                    //     .removeChild(file.previewElement) : void 0;
                                })
                                .fail(function(res, error) {
                                    toastr.error(res.responseJSON.message,
                                        'Failed')
                                })
                            }
                        })

                        var _ref;
                        return (_ref = file.previewElement) != null ? _ref.parentNode
                            .removeChild(file.previewElement) : void 0;

                    },
                });
            }
        };

        return {
            init: function(){
                _componentPage();
            }
        }

    }();

    document.addEventListener('DOMContentLoaded', function() {
        Page.init();
    });

</script>
