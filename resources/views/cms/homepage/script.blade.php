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