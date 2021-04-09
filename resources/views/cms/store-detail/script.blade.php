<script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js"></script>
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
<script>
    


</script>
<script type="text/javascript">
    $(document).ready(function(){
        get_init_video()
    })

    var get_init_video = ()=>{
        var video = document.getElementById('video-player');
        $("#video-course").attr('src',$(".video-quality__item.360").data('url'))

        if(video)
            video.load()
    }
</script>
<script type="text/javascript">
    var Page = function () {
        var _componentPage = function () {

            $(document).ready(function () {
                AOS.init();
                videoContentChange()
                eventSearchListener()
                initVideoContentEvent()
            });

            var videoContentChange = ()=>{
                $(".video-title").click(function(event){
                    event.preventDefault()
                    $("#title-video-played").text($(this).data('title'))
                    
                    if($(this).data('youtube')){
                        $("#video-content").html(`<div class="content-embed__wrapper">
                                                    <iframe id="video-course" class="w-100 h-100" src="" frameborder="0"
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                        allowfullscreen></iframe>
                                                </div>`)
                        var video = document.getElementById('video-player');
                        $(".video-quality-selected").html($(this).text());

                        $("#video-course").attr('src',$(this).data('url'))

                    }else{
                        let url = $(this).data('url')
                        if(url){
                            $.ajax({
                                url: `/video-course/videos/${url}`,
                                type: 'GET',
                            })
                            .done(function(res, xhr, meta) {
                                $("#video-content").html(res.data.html)
                                get_init_video()
                                initVideoContentEvent()
                            })
                            .fail(function(res, error) {
                                toastr.error(res.responseJSON.message, 'Failed')
                            })
                            .always(function() {
                               
                            });
                        }
                    }
                })
            }

            var initVideoContentEvent = ()=>{
                $(".video-quality-selected").click(function () {
                    $(".video-quality-item__wrapper").toggle("fast", function () {
                    });
                });

                $(".video-quality__item").click(function () {
                    var video = document.getElementById('video-player');
                    $(".video-quality-selected").html($(this).text());

                    $("#video-course").attr('src',$(this).data('url'))
                    video.load()
                    $(".video-quality-item__wrapper").toggle("fast", function () {
                    });
                });
            }

            var eventSearchListener = ()=>{
                $('#search').keyup(searchDelay(function(event) {
                    $.ajax({
                        url: `{{ url('store/search') }}`,
                        data:{
                            'search':$(this).val()
                        },
                        type: 'POST',
                    })
                    .done(function(res, xhr, meta) {
                        let element = "";
                        $.each(res.data, function(index, item){
                            element += `<option value="${item.name}" data-id="${item.id}">`
                        })
                        $("#datalistOptions").html(element)
                    })
                    .fail(function(res, error) {
                        toastr.error(res.responseJSON.message, 'Failed')
                    })
                    .always(function() {
                       
                    });
                }, 1000));

                $("#search").on('input',function(event){
                    let video_course_id = $("#datalistOptions option[value='" + $(this).val() + "']").attr('data-id')
                    if(video_course_id)
                        window.location.href = `/video-course/${video_course_id}/detail`;
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