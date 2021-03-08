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

        var init_page = 0;

        $(document).scroll(function() {
            var page_height = $( window ).height()
            var current_height = document.documentElement.scrollTop;
            var page = parseInt(current_height / page_height)
            var query = window.location.search ? window.location.search +`&page=${page}`:`?page=${page}`
            console.log(init_page)
            if(page && (page != init_page)){
                $("#loading-scroll").html('<div class="lds-dual-ring mt-4"></div>')
                init_page = page;
                $.ajax({
                    url: `/event-list/paging${query}`,
                    type: 'GET',
                })
                .done(function(res, xhr, meta) {
                    let element = ''
                    $.each(res.data, function(index, event){
                        element += `<div class="row mb-4 pr-0 pr-lg-5 pb-3">
                                    <div class="col-xl-4 mb-3 mb-md-0">
                                        <a href="event-detail.html">
                                            <figure><img src="${ event.image_url ? event.image_url:'' }" /></figure>
                                        </a>
                                    </div>
                                    <div class="col-xl-8 px-4">
                                        <div class="badge-left">
                                            <a href="/event/${event.id}/detail">
                                                <h3 class="ml-2 mt-2 mt-md-4 mt-lg-0">${ event.title ? event.title:'' }</h3>
                                            </a>
                                        </div>
                                        <p class="mt-3 ml-3 desc__store-content">${ event.description ? event.description:'' }</p>
                                        <div class="detail__store-content ml-3 mt-3">
                                            <div class="coach-name__store-content row-center mr-4">
                                                <img src="/cms/assets/img/svg/Crown.svg" class="mr-2" alt="">${ event.category ? event.category:'-'}
                                            </div>
                                            <div class="class__store-content row-center mt-md-0 mt-3">
                                                <img src="/cms/assets/img/svg/calendar.svg" class="mr-2" alt="">${ event.date}
                                            </div>
                                        </div>
                                    </div>
                                </div>`
                    })

                    $("#event-list").append(element)

                    
                })
                .fail(function(res, error) {
                    toastr.error(res.responseJSON.message, 'Failed')
                })
                .always(function() {
                   $("#loading-scroll").html('')
                });
            }
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