<script type="text/javascript">
    var Page = function () {
      var _componentPage = function () {
        $(document).ready(function () {
          splide();
          AOS.init();
        });

        splide = () => {
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
          new Splide('#our-team', {
            breakpoints: {
              640: {
                padding: {
                  right: '3rem',
                  left: '3rem',
                },
              },
            },
            autoplay: true,
            interval: 2000,
            pagination: true,
            type: 'loop',
            perPage: 1,
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