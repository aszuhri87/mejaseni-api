<script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js"></script>
<script>
    tippy('.video-title', {
        content: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Aut eaque, fugiat quam inventore officiis rerum error sint provident nam accusamus, iure impedit minus esse placeat quia. Saepe animi veniam, asperiores adipisci, laborum reiciendis soluta rem aliquid natus temporibus dicta possimus. Ullam quod cum, asperiores odit, dolor suscipit impedit voluptate distinctio ipsum quam quidem? Doloremque nulla fugiat fuga totam rerum placeat, distinctio deleniti architecto, necessitatibus natus enim ab esse non dolore, quidem inventore voluptatem aut assumenda molestias! Est qui exercitationem nostrum omnis commodi! Voluptas quidem unde reprehenderit aut, distinctio libero at, saepe, quibusdam dignissimos sit facere ratione magnam inventore minus exercitationem?',
        animation: 'scale',
        placement: 'bottom',
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