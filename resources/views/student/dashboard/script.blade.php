<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            const primary = '#7F16A7';
            const success = '#1BC5BD';
            const info = '#8950FC';
            const warning = '#FFA800';
            const danger = '#F64E60';

            $(document).ready(function() {
                initProgressClass();
                totalClass();
                totalVideo();
                totalBooking();
                historyBooking();
                initChart();
            });

            const initChart = () => {
                var options = {
                    series: [{
                        name: 'Net Profit',
                        data: [44, 55, 57, 56, 61]
                    }, {
                        name: 'Revenue',
                        data: [76, 85, 101, 98, 87]
                    }],
                    chart: {
                        type: 'bar',
                        height: 350
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    },
                    yaxis: {
                        title: {
                            text: 'Total'
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    colors: [primary, warning]
                };

                var chart = new ApexCharts(document.querySelector("#chart"), options);
                chart.render();
            },
            initAction = () => {
                $(document).on('click', '#add-btn', function(event){
                    event.preventDefault();

                    $('#form-invoice').trigger("reset");
                    $('#form-invoice').attr('action','{{url('admin/master/courses/classroom-category')}}');
                    $('#form-invoice').attr('method','POST');

                    showModal('modal-invoice');
                });

                $(document).on('click', '.btn-edit', function(event){
                    event.preventDefault();

                    var data = init_table.row($(this).parents('tr')).data();

                    $('#form-invoice').trigger("reset");
                    $('#form-invoice').attr('action', $(this).attr('href'));
                    $('#form-invoice').attr('method','PUT');

                    $('#form-invoice').find('input[name="name"]').val(data.name);

                    showModal('modal-invoice');
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
            initProgressClass = () => {
                var options = {
                    chart: {
                        height: 280,
                        type: "radialBar"
                    },

                    series: [67],

                    plotOptions: {
                        radialBar: {
                        hollow: {
                            margin: 15,
                            size: "70%"
                        },

                        dataLabels: {
                            showOn: "always",
                            name: {
                            offsetY: -10,
                            show: false,
                            color: "#888",
                            fontSize: "13px"
                            },
                            value: {
                            color: "#111",
                            fontSize: "30px",
                            show: true
                            }
                        }
                        }
                    },

                    stroke: {
                        lineCap: "round",
                    }
                };

                var chart = new ApexCharts(document.querySelector("#progress-class"), options);

                chart.render();

            },
            totalClass = () => {
                $.ajax({
                    url: `{{url('student/dashboard/total-class')}}`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    $('#total-class').html(res.data)
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            },
            totalVideo = () => {
                $.ajax({
                    url: `{{url('student/dashboard/total-video')}}`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    $('#total-video').html(res.data)
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            },
            totalBooking = () => {
                $.ajax({
                    url: `{{url('student/dashboard/total-booking')}}`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    $('#total-booking').html(res.data)
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            },
            historyBooking = () => {
                $.ajax({
                    url: `{{url('student/dashboard/history-booking')}}`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    $('#history-booking').html(res.data)
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
