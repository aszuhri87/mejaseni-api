<script>
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
            select: '#select_event_categories'
        })
        $('.input-daterange input').each(function () {
            $(this).datepicker('clearDates');
        });
    </script>
    <script>
        $("#event_filter_btn").click(function () {
            $(".filter__wrapper").toggle();
        });
    </script>

    <script type="text/javascript">
        var Page = function () {
            var _componentPage = function () {
                $(document).ready(function () {
                    AOS.init();
                });
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