<script>
    const primary = '#7F16A7';
    const warning = '#FFA800';

    var _demo3 = function () {
        $.ajax({
            url: '{{url("admin/cart-dashboard")}}',
            type: 'GET',
            dataType: 'json',
        })
        .done(function(res, xhr, meta) {
            const apexChart = "#chart_3";
            var options = {
                series: [{
                    name: 'Kelas',
                    data: res.data.classroom
                }, {
                    name: 'Video',
                    data: res.data.video
                }],
                chart: {
                    type: 'bar',
                    height: 350
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
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
                    title: {
                        text: 'Penjualan'
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " Terjual"
                        }
                    }
                },
                colors: [primary, warning]
            };

            var chart = new ApexCharts(document.querySelector(apexChart), options);
            chart.render();
        })

	}

    _demo3();
</script>
