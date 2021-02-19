<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var init_table,
                select_class,
                select_platform,
                select_category,
                select_sub_category,
                select_platform_master_lesson;

            $(document).ready(function() {
                formSubmit();
                initAction();
                init_datetime();
                getClass();
                getPlatform();
                getCategory();
                select_class = new SlimSelect({
                    select: '#class',
                    searchPlaceholder: 'Search Class'
                });

                select_platform = new SlimSelect({
                    select: '#platform',
                    searchPlaceholder: 'Search platform'
                });

                select_platform_master_lesson = new SlimSelect({
                    select: '#platform-master-lesson',
                    searchPlaceholder: 'Search platform'
                });

                select_sub_category = new SlimSelect({
                    select:'#sub-category',
                    searchPlaceholder:'Search Sub Category'
                });

                $('.dropify').dropify();
            });

            const initAction = () => {
                $(document).on('click', '#add-btn', function(event){
                    event.preventDefault();

                    $('#form-list').trigger("reset");
                    $('#form-list').attr('action','{{url('admin/master/coach/view-list/store')}}');
                    $('#form-list').attr('method','POST');

                    showModal('modal-list');
                });

                $(document).on('click', '.btn-edit', function(event){
                    event.preventDefault();

                    var data = init_table.row($(this).parents('tr')).data();

                    $('#form-list').trigger("reset");
                    $('#form-list').attr('action', $(this).attr('href'));
                    $('#form-list').attr('method','PUT');

                    $('#form-list').find('input[name="name"]').val(data.name);

                    showModal('modal-list');
                });

                $(document).on('click', '.btn-delete', function(event){
                    event.preventDefault();
                    var url = $(this).attr('href');

                    Swal.fire({
                        title: 'Delete Classroom Category?',
                        text: "Deleted Classroom Category will be permanently lost!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#7F16A7',
                        confirmButtonText: 'Yes, Delete',
                    }).then(function (result) {
                        if (result.value) {
                            $.ajax({
                                url: url,
                                type: 'DELETE',
                                dataType: 'json',
                            })
                            .done(function(res, xhr, meta) {
                                toastr.success(res.message, 'Success')
                                init_table.draw(false);
                            })
                            .fail(function(res, error) {
                                toastr.error(res.responseJSON.message, 'Failed')
                            })
                            .always(function() { });
                        }
                    })
                    $('.swal2-title').addClass('justify-content-center')
                });

                $(document).on('change','.radio-package',function(event){
                    let value = $(this).val();
                    if(value == 1){
                        $('.input-package-class').show();
                        $('.input-master-lesson').hide();
                    }
                    else{
                        $('.input-package-class').hide();
                        $('.input-master-lesson').show();
                    }
                });

                $(document).on('change','#category',function(event){
                    event.preventDefault();
                    let value = $(this).val();

                    if(value){
                        $.ajax({
                            url: `{{ url('public/get-sub-classroom-category-by-category') }}/${value}`,
                            type: 'GET',
                        })
                        .done(function(res, xhr, meta) {
                            let element = '<option value="">Pilih Sub Category</option>';
                            $.each(res.data, function(index, data){
                                element += `<option value="${data.id}">${data.name}</option>`
                            });
                            $('#sub-category').html(element);
                            if(select_sub_category){
                                select_sub_category.destroy();
                            }
                            select_sub_category = new SlimSelect({
                                select:'#sub-category'
                            })
                        })
                        .fail(function(res, error) {
                            toastr.error(res.responseJSON.message, 'Failed')
                        })
                        .always(function() {
                            btn_loading('stop')
                        });
                    }
                    else{
                        $('#sub-category').html(`<option value="">Pilih Sub Category</option>`);
                        if(select_sub_category){
                            select_sub_category.destroy();
                        }
                        select_sub_category = new SlimSelect({
                            select:'#sub-category'
                        })
                    }
                })
            },
            formSubmit = () => {
                $('#form-list').submit(function(event){
                    event.preventDefault();

                    btn_loading('start')
                    $.ajax({
                        url: $(this).attr('action'),
                        type: $(this).attr('method'),
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                    })
                    .done(function(res, xhr, meta) {
                        if(res.data == 200){
                            toastr.success(res.message, 'Success')
                            init_table.draw(false);
                            hideModal('modal-list');
                        }
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading('stop')
                    });
                });
            },
            init_datetime = () => {
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

                $('.timepicker').timepicker({
                    minuteStep: 1,
                    showSeconds: true,
                    showMeridian: false
                });

                $('#form-list').find('input[name="time"]').on('click', function(){
                    if(!$(this).val()){
                        $(this).val(moment().format('H:mm:ss'));
                    }
                });
            },
            getClass = () => {
                $.ajax({
                    url: `{{url('admin/master/coach/view-list/get-class-by-coach')}}/{{Request::segment(5)}}`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    let element = '<option value="">Pilih Kelas</option>';
                    $.each(res.data, function(index, data){
                        element += `<option value="${data.id}">${data.name}</option>`
                    });
                    $('#class').html(element);
                    if(select_class){
                        select_class.destroy();
                    }

                    select_class = new SlimSelect({
                        select:'#class',
                        searchPlaceholder:'Seach Class'
                    })
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            },
            getPlatform = () =>{
                $.ajax({
                    url: `{{url('public/get-platform')}}`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    let element = '<option value="">Pilih Media</option>';
                    $.each(res.data, function(index, data){
                        element += `<option value="${data.id}">${data.name}</option>`
                    });
                    $('#platform').html(element);
                    $('#platform-master-lesson').html(element);

                    if(select_platform){
                        select_platform.destroy();
                    }
                    if(select_platform_master_lesson){
                        select_platform_master_lesson.destroy();
                    }

                    select_platform = new SlimSelect({
                        select:'#platform',
                        searchPlaceholder:'Seach platform'
                    })
                    select_platform_master_lesson = new SlimSelect({
                        select:'#platform-master-lesson',
                        searchPlaceholder:'Seach platform'
                    })
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            },
            getCategory = () => {
                $.ajax({
                    url: `{{url('public/get-classroom-category')}}`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    let element = '<option value="">Pilih Category Class</option>';
                    $.each(res.data, function(index, data){
                        element += `<option value="${data.id}">${data.name}</option>`
                    });
                    $('#category').html(element);
                    if(select_category){
                        select_category.destroy();
                    }

                    select_category = new SlimSelect({
                        select:'#category',
                        searchPlaceholder:'Seach category'
                    })
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
