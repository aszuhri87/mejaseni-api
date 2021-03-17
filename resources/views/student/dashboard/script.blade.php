<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            const primary = '#7F16A7';
            const success = '#1BC5BD';
            const info = '#8950FC';
            const warning = '#FFA800';
            const danger = '#F64E60';

            $(document).ready(function() {
                progressClass();
                totalClass();
                totalVideo();
                totalBooking();
                historyBooking();
                restSession();
                initChart();
                upcoming();
                myCourse();
                initAction();
                studentBookingWeek();
            });

            const initChart = () => {
                $.ajax({
                    url: `{{ url('student/dashboard/summary-course') }}`,
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(res, xhr, meta) {
                    if(res.status == 200){
                        var options = {
                            series: [{
                                name: 'Kelas Dihadiri',
                                data: res.data.present
                            }, {
                                name: 'Kelas Dibooking',
                                data: res.data.booking
                            }],
                            chart: {
                                type: 'bar',
                                height: 400
                            },
                            plotOptions: {
                                bar: {
                                    horizontal: false,
                                    columnWidth: '30%',
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
                                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Des'],
                            },
                            yaxis: {
                                max: 30,
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
                    }
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() { });
            },
            restSession = () => {
                $.ajax({
                    url: `{{ url('student/dashboard/rest-session') }}`,
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(res, xhr, meta) {
                    $('#rest-session').html(res.data)
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() { });
            },
            initAction = () => {
                $(document).on('click','#filter-course',function(event){
                    event.preventDefault();
                    let filter_course = $(this).val();
                    myCourse(filter_course);
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
            progressClass = () => {
                $.ajax({
                    url: `{{url('student/dashboard/progress-class')}}`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    if(res.status == 200 ){
                        var options = {
                            chart: {
                                height: 228,
                                type: "radialBar"
                            },

                            series: [res.data.total_present],

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

                        $('#percent-progress-class').html(res.data.total_present);
                        $('#class-active').html(res.data.total_class);
                    }
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
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
            },
            upcoming = () => {
                $.ajax({
                    url: `{{url('student/dashboard/upcoming')}}`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    let element = ``;
                    $.each(res.data, function(index,data){
                        if(index == 0){
                            $('#img-upcoming').attr('src',data.image);
                            $('#title-upcoming').html(data.name);
                            $('#date-upcoming').html(moment(data.datetime).format('DD MMMM YYYY'));
                            $('#time-upcoming').html(moment(data.datetime).format('HH:mm'));

                            if(res.data.is_master_lesson){
                                if(moment(data.datetime).isSameOrBefore(moment().format('YYYY-MM-DD HH:mm:ss'))){
                                    $('#enter-master-lesson').html(`<a href="${data.link}" class="btn btn-primary mt-5 mb-5">Masuk Kelas</a>`)
                                }
                            }

                            $('#is_exist_upcoming').show();
                        }
                        else{
                            element +=`
                                <div class="separator separator-dashed mt-8 mb-5"></div>
                                <div class="row">
                                    <div class="col-5">
                                        <img src="${data.image}" class="rounded" height="110px" width="100%">
                                    </div>
                                    <div class="col-7">
                                        <h5>${data.name}</h5>
                                        <div class="rounded" style="padding: 10px !important; background-color: rgba(0, 0, 0, 0.097);">
                                            <span>${moment(data.datetime).format('DD MMMM YYYY')}</span><br>
                                            <span>${moment(data.datetime).format('HH:mm')}</span>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }
                    });
                    $('#list-upcoming').html(element)
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            },
            myCourse = (filter_course) => {
                $.ajax({
                    url: `{{url('student/dashboard/my-course')}}`,
                    type: 'GET',
                    data :{
                        filter_course:filter_course
                    }
                })
                .done(function(res, xhr, meta) {
                    let element = ``;
                    let total_course = 0;
                    let total_completed = 0;
                    $.each(res.data, function(index,data){
                        total_course++;
                        if(data.type == 1){
                            element += `
                            <div class="row mt-5">
                                <div class="col-lg-3">
                                    <img src="${data.image}" class="rounded" height="150px" width="100%">
                                </div>
                                <div class="col-lg-9 align-middle">
                                    <div class="row">
                                        <div class="col-9" style="padding-top: 40px !important">
                                            <p><h4>${data.classroom_name}</h4></p>
                                            `;
                                            if(data.total < data.session_total){
                                                let percent = Math.floor((data.total/data.session_total)*100);
                                                element += `
                                                <div class="row">
                                                    <div class="col-8">
                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar" style="width: ${percent}%" aria-valuenow="${percent}" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <span class="text-muted">${data.total}/${data.session_total} Pertemuan</span>
                                                    </div>
                                                </div>`;
                                            }
                                            else{
                                                total_completed++;
                                                element += `
                                                <span class="text-muted">Completed</span>
                                                `
                                            }
                                            element +=`
                                        </div>
                                        <div class="col-3 text-right" style="padding-top: 50px !important">
                                            `;
                                            if(data.total < data.session_total){
                                                element += `<a href="{{url('student/schedule')}}" class="btn btn-outline-primary">Lihat Jadwal</a>`;
                                            }
                                            element +=`
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-5">
                                <div class="col-lg-9 offset-3">
                                    <hr>
                                </div>
                            </div>
                            `;
                        }
                        else if(data.type == 2){
                            element += `
                            <div class="row mt-5">
                                <div class="col-lg-3">
                                    <img src="${data.image}" class="rounded" height="150px" width="100%">
                                </div>
                                <div class="col-lg-9 align-middle">
                                    <div class="row">
                                        <div class="col-9" style="padding-top: 40px !important">
                                            <p><h4>${data.name}</h4><span class="label label-inline label-primary mr-2">Video Course</span></p>

                                        </div>
                                        <div class="col-3 text-right" style="padding-top: 50px !important">
                                           <a href="{{url('student/theory/video-class/video-detail')}}/${data.session_video_id}" class="btn btn-outline-primary">Lihat Video</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-5">
                                <div class="col-lg-9 offset-3">
                                    <hr>
                                </div>
                            </div>
                            `;
                        }
                    });
                    $('#my-course').html(element);
                    $('#text-course').html(
                        `Terdapat <strong>${total_course} kursus</strong> yang kamu miliki dan <strong>${total_completed} kursus</strong> telah kamu selesaikan`
                    )
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {

                });
            },
            studentBookingWeek = () => {
                $.ajax({
                    url: `{{url('student/dashboard/student-booking-month')}}`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    if(res.status == 200 ){
                        $('#student-booking-month').html(res.data);
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
