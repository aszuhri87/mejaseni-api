<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var init_table;

            $(document).ready(function() {
                formSubmit();
                initAction();
                getTheory();
                getClass();
                initDatePicker();
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
                    KTApp.block('#kt_body', {
                        overlayColor: '#000000',
                        state: 'primary',
                        opacity: 0.5,
                        message: 'Processing...'
                    });
                    getTheory($(this).val())
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
                            KTApp.block('#kt_body', {
                                overlayColor: '#000000',
                                state: 'primary',
                                opacity: 0.5,
                                message: 'Processing...'
                            });
                            getTheory(classroom_id,date_start,date_end)
                        }
                    }
                })
            },
            formSubmit = () => {
                $('#form-invoice').submit(function(event){
                    event.preventDefault();

                    btn_loading('start')
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: $(this).serialize(),
                    })
                    .done(function(res, xhr, meta) {
                        toastr.success(res.message, 'Success')
                        init_table.draw(false);
                        hideModal('modal-invoice');
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading('stop')
                    });
                });
            },
            getTheory = (classroom_id,date_start,date_end) =>{
                $.ajax({
                    url: `{{ url('student/theory/get-theory') }}`,
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
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-3">
                                                    <img src="{{asset('assets/images/pdf-file-extension.png')}}" width="50px" height="50px">
                                                </div>
                                                <div class="col-8">
                                                    <p style="margin-bottom: 0 !important"><strong>${data.theory_name}</strong></p>
                                                    <span class="text-muted">${data.classroom_name}</span>
                                                </div>
                                            </div>
                                            <div class="row mt-5">
                                                <div class="col text-justify overflow-auto" style="height:80px !important">
                                                    ${data.description}
                                                </div>
                                            </div>
                                            <div class="row mt-5">
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
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="row mt-5">
                                                <div class="col-12">
                                                    <a target="_blank" href="${data.file_url}" class="btn btn-primary">
                                                        <span class="svg-icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <rect x="0" y="0" width="24" height="24"/>
                                                                <path d="M2,13 C2,12.5 2.5,12 3,12 C3.5,12 4,12.5 4,13 C4,13.3333333 4,15 4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 C2,15 2,13.3333333 2,13 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                                <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 8.000000) rotate(-180.000000) translate(-12.000000, -8.000000) " x="11" y="1" width="2" height="14" rx="1"/>
                                                                <path d="M7.70710678,15.7071068 C7.31658249,16.0976311 6.68341751,16.0976311 6.29289322,15.7071068 C5.90236893,15.3165825 5.90236893,14.6834175 6.29289322,14.2928932 L11.2928932,9.29289322 C11.6689749,8.91681153 12.2736364,8.90091039 12.6689647,9.25670585 L17.6689647,13.7567059 C18.0794748,14.1261649 18.1127532,14.7584547 17.7432941,15.1689647 C17.3738351,15.5794748 16.7415453,15.6127532 16.3310353,15.2432941 L12.0362375,11.3779761 L7.70710678,15.7071068 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000004, 12.499999) rotate(-180.000000) translate(-12.000004, -12.499999) "/>
                                                            </g>
                                                        </svg></span>
                                                        Download
                                                    <a>
                                                </div>
                                            </div>
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
                                                <h4 class="text-muted">Theory Not Available</h4>
                                            </div>
                                        </div>
                                    </div>
                            `
                        }
                        KTApp.unblock('#kt_body');
                        $('.theory').html(element);
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
                    url: `{{ url('student/theory/get-class') }}/{{Auth::guard('student')->user()->id}}`,
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
