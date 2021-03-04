<script>
    $(".addtocart").click(function () {
        
        $.ajax({
            url: $(this).attr('href'),
            type: 'POST',
            dataType: 'json',
        })
        .done(function(res, xhr, meta) {
            $(".cart-added").toggle();
            $('.addtocart').toggle();
            $(".cart-added").css("display", "flex");
        })
        .fail(function(res, error) {
            toastr.error(res.responseJSON.message, 'Failed')
        })
        .always(function() { });
        event.preventDefault();
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