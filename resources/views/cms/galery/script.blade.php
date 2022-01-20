<script>
    new Splide('#other-news', {
        lazyLoad: true,
        pagination: false,
        type: 'loop',
        perPage: 4,
        rewind: true,
        perMove: 1,
        arrows: false,
        autoplay: true,
    }).mount();
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