
<script type="text/javascript">
    var Page = function () {
        var _componentPage = function () {
            $(document).ready(function () {
                AOS.init();
                initSelect();
                getClassRoomCatergory();
                eventSearchListener();


                $('.input-daterange input').each(function () {
                    $(this).datepicker('clearDates');
                });

                $("#news_filter_btn").click(function () {
                    $(".filter__wrapper").toggle();
                });
                $("#news-filter-btn-mobile").click(function () {
                    $(".filter-overlay").css("display", "block");
                });
                $(".menu-overlay__close").click(function () {
                    $(".filter-overlay").css("display", "none");
                });
                $(".close-btn").click(function () {
                    $(".filter-overlay").css("display", "none");
                });
            });

            var getClassRoomCatergory = ()=>{
                $.ajax({
                    url: '{{url('public/get-classroom-category')}}',
                    type: 'GET',
                    dataType: 'json',
                })
                .done(function(res, xhr, meta) {
                    let element = `<option value="0">Semua berita</option>`;
                    $.each(res.data, function(index, data) {
                        element += `<option value="${data.id}">${data.name}</option>`;
                    });

                    $('#select-news-categories').html(element);
                    initSelect()
                })
            }

            var eventSearchListener = ()=>{
                $('#search').keyup(searchDelay(function(event) {
                    if(event.keyCode == 13){
                        let url = `/news-list?search=${$(this).val()}`
                        window.location = url;
                    }
                }, 1000));
            }

            var initSelect = ()=>{
                new SlimSelect({
                    select: '#select-news-categories'
                })
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