<script>
    $(".see-all").click(function () {
      $(".class-owned").removeClass("fade-out-up");
      $(".class-owned").addClass("fade-in-down");
      $(".class-owned").toggle();
    });

    $("#class-owned1").click(function () {
      $(".class-owned").removeClass("fade-in-down");
      $(".class-owned").addClass("fade-out-up");
      $(".class-owned").css("display", "none");
      $("#class-name-selected").html("Basic Piano");
      $("#class-image-selected").attr("src", "././assets/img/master-lesson__banner2.jpg");
    });

  </script>
  <script>
    $("#profile-mobile").click(function () {
      $(".menu-overlay").css("display", "block");
    });
    $(".menu-overlay__close").click(function () {
      $(".menu-overlay").css("display", "none");
    });
  </script>

  <script type="text/javascript">
    var Page = function () {
      var _componentPage = function () {

        $(document).ready(function () {
          splide();

          @if(!$image_galeries->isEmpty())
            initEventSplit()
          @endif


          AOS.init();
        });

        var initMinatSplide = ()=>{
          new Splide('#splide2', {
            type: 'loop',
            arrow: false,
            padding: {
              right: '10rem',
              left: '10rem',
            },
            breakpoints: {
              991: {
                perPage: 1,
                padding: {
                  right: '0rem',
                  left: '0rem',
                },
              },
              640: {
                perPage: 1,
                padding: {
                  right: '1rem',
                  left: '1rem',
                },
              },
            }
          }).mount();
        },
        initEventSplit = ()=>{
          new Splide('#splide1', {
            lazyLoad: true,
            autoplay: true,
            type: 'loop',
            breakpoints: {
              640: {
                perPage: 1,
              },
            }
          }).mount();
        },
        splide = () => {
          
          new Splide('#splide3', {
            breakpoints: {
              991: {
                perPage: 1,
                padding: {
                  right: '0rem',
                  left: '0rem',
                },
              },
              640: {
                perPage: 1,
                padding: {
                  right: '1.5rem',
                  left: '1.5rem',
                },
              },
            },
            pagination: true,
            type: 'loop',
            perPage: 2,
            perMove: 1,
          }).mount();
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