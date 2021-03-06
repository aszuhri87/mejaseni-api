<script>
        $(".filter-btn").click(function () {
            $(".filter__wrapper").toggle();
        });
        $("#filter-btn-mobile").click(function () {
          $(".filter-overlay").css("display", "block");
        });
        $(".menu-overlay__close").click(function () {
          $(".filter-overlay").css("display", "none");
        });
        $(".close-btn").click(function () {
          $(".filter-overlay").css("display", "none");
        });
      </script>

    <script>
        new SlimSelect({
            select: '#select-subcategories'
        })
        new SlimSelect({
            select: '#select-event-categories'
        })
        $('.input-daterange input').each(function () {
            $(this).datepicker('clearDates',{ dateFormat: 'Y-m-d' });
        });
    </script>

    <script type="text/javascript">
        var Page = function () {
            var _componentPage = function () {
                $(document).ready(function () {
                    AOS.init();
                    eventSearchListener()
                });

                var eventSearchListener = ()=>{
                    $('#search').keyup(searchDelay(function(event) {
                        if(event.keyCode == 13){
                            let url = `/event-list?search=${$(this).val()}`
                            window.location = url;
                        }
                    }, 1000));
                }
            };
            return {
                init: function () {
                    _componentPage();
                }
            }
        }();
        document.addEventListener('DOMContentLoaded', function () {
            Page.init();
        });

    </script>