<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var init_table, calendar, calendar_regular, calendar_special;

            $(document).ready(function() {
                formSubmit();
                initAction();
                initCalendarReguler();
                initCalendarSpecial();
                // renderCalendarSpecial();
            });

            const initAction = () => {
                $(document).on('click', '#add-btn', function(event){
                    event.preventDefault();

                    $('#form-schedule').trigger("reset");
                    $('#form-schedule').attr('action','{{url('admin/master/courses/classroom-category')}}');
                    $('#form-schedule').attr('method','POST');

                    showModal('modal-schedule');
                });

                $(document).on('click', '.btn-edit', function(event){
                    event.preventDefault();

                    var data = init_table.row($(this).parents('tr')).data();

                    $('#form-schedule').trigger("reset");
                    $('#form-schedule').attr('action', $(this).attr('href'));
                    $('#form-schedule').attr('method','PUT');

                    $('#form-schedule').find('input[name="name"]').val(data.name);

                    showModal('modal-schedule');
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

                $(document).on('click','.tab-regular',function(event){
                    event.preventDefault();

                });

                $(document).on('click','.tab-special',function(event){
                    event.preventDefault();

                });

                $(document).on('click','.tab-master-lesson',function(event){
                    event.preventDefault();

                });
            },
            formSubmit = () => {
                $('#form-schedule').submit(function(event){
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
                        hideModal('modal-schedule');
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                        btn_loading('stop')
                    });
                });
            },
            initCalendarSpecial = () => {
                var element = document.getElementById('calendar-special');
                if(calendar){
                    calendar.removeAllEvents();
                }
                calendar = new FullCalendar.Calendar(element, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,list'
                    },
                    locale: 'ind',
                    timeZone: 'Asia/Jakarta',
                    events: renderCalendarSpecial(),
                    eventClick: function(info) {
                        // calendarDetail(info.event.id);
                    }
                });
                calendar.render();
                // $.ajax({
                //     url: `{{ url('student/schedule/special-class') }}`,
                //     type: `GET`,
                // })
                // .done(function(res, xhr, meta) {
                //     if(res.status == 200){
                //         data = res.data;
                //         calendar_special = new FullCalendar.Calendar(element, {
                //             initialView: 'dayGridMonth',
                //             headerToolbar: {
                //                 left: 'prev,next today',
                //                 center: 'title',
                //                 right: 'dayGridMonth,timeGridWeek,timeGridDay,list'
                //             },
                //             locale: 'ind',
                //             timeZone: 'Asia/Jakarta',
                //             events: data,
                //             eventClick: function(info) {
                //                 // calendarDetail(info.event.id);
                //             }
                //         });
                //         calendar_special.render();
                //     }
                // })
                // .fail(function(res, error) {
                //     toastr.error(res.responseJSON.message, 'Failed')
                // })
                // .always(function() {

                // });
            },
            initCalendarReguler = () => {
                var element = document.getElementById('calendar-regular');
                $.ajax({
                    url: `{{ url('student/schedule/regular-class') }}`,
                    type: `GET`,
                })
                .done(function(res, xhr, meta) {
                    if(res.status == 200){
                        calendar_regular = new FullCalendar.Calendar(element, {
                            initialView: 'dayGridMonth',
                            headerToolbar: {
                                left: 'prev,next today',
                                center: 'title',
                                right: 'dayGridMonth,timeGridWeek,timeGridDay,list'
                            },
                            locale: 'ind',
                            timeZone: 'Asia/Jakarta',
                            events: res.data
                        });
                        calendar_regular.render();
                    }
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            },
            renderCalendarSpecial = () => {
                if(calendar_special){
                    calendar_special.removeAllEvents();
                }
                $.ajax({
                    url: `{{ url('student/schedule/special-class') }}`,
                    type: `GET`,
                })
                .done(function(res, xhr, meta) {
                    if(res.status == 200){
                        let data = res.data;
                        let schedules = [];

                        $.each(data, function(index, data){
                            calendar.addEvent({
                                "id": data.id,
                                "title": data.name,
                                "start": data.datetime,
                                "className": `bg-${data.color} border-${data.color} p-1 ${data.color == 'primary' ? 'text-white' : ''}`
                            });
                        })

                        return schedules;
                    }
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
