<script type="text/javascript">
    var Page = function() {
        var _componentPage = function(){
            var calendar;

            $(document).ready(function() {
                initCalendar();
            });

            const initCalendar = () => {
                var calendarEl = document.getElementById('calendar');

                calendar = new FullCalendar.Calendar(calendarEl, {
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,list'
                    },
                    locale: 'ind',
                    timeZone: 'Asia/Jakarta',
                    initialView: 'dayGridMonth',
                    eventColor: '#fff',
                    editable: true,
                    selectable: true,
                    dateClick: function(info) {
                        console.log(info);
                        alert('clicked ' + info.dateStr);
                    },
                    select: function(info) {
                        console.log(info);
                        alert('selected ' + info.startStr + ' to ' + info.endStr);
                    }
                });

                calendar.render();
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
