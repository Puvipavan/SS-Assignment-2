(function ($) {

  BACheetahCarouselNew = function (node, settings) {

    let settingsApp = JSON.parse(settings);

    if (settingsApp.lengthPhotos > 0) {

      var swiperThumbnail;

      if (settingsApp.styleGallery === 'thumbnail') {

        swiperThumbnail = new Swiper(
          '[id="' + node + '-thumbnails"]',
          {
            loop: true,
            spaceBetween: 10,
            slidesPerView: settingsApp.lengthPhotos < 4 ? settingsApp.lengthPhotos : 4,
            freeMode: true,
            watchSlidesProgress: true,
          }
        );
      }


      new Swiper('[id="' + node + '"]', {
        effect: settingsApp.styleGallery === 'carousel' ? "coverflow" : settingsApp.styleGallery,
        grabCursor: true,
        autoplay: settingsApp.auto_play === 'true' ? {delay: settingsApp.speed * 1000} : false,
        freeMode: settingsApp.mode === 'free',
        loop: settingsApp.loop === 'true',
        cubeEffect: {
          shadow: false,
          slideShadows: false,
        },

        thumbs: settingsApp.styleGallery === 'thumbnail' ? {
          swiper: swiperThumbnail,
        } : null,
      });
    }
  };

  function autoplayMethod(speed) {
    return (slider) => {
      let timeout;
      let mouseOver = false;

      function clearNextTimeout() {
        clearTimeout(timeout);
      }

      function nextTimeout() {
        clearTimeout(timeout);
        if (mouseOver) return;
        timeout = setTimeout(() => {
          slider.next();
        }, speed * 1000);
      }

      slider.on("created", () => {
        slider.container.addEventListener("mouseover", () => {
          mouseOver = true;
          clearNextTimeout();
        });
        slider.container.addEventListener("mouseout", () => {
          mouseOver = false;
          nextTimeout();
        });
        nextTimeout();
      });
      slider.on("dragStarted", clearNextTimeout);
      slider.on("animationEnded", nextTimeout);
      slider.on("updated", nextTimeout);
    };
  }


  function ThumbnailPlugin(main) {
    return (slider) => {
      function removeActive() {
        slider.slides.forEach((slide) => {
          slide.classList.remove("active");
        });
      }

      function addActive(idx) {
        slider.slides[idx].classList.add("active");
      }

      function addClickEvents() {
        slider.slides.forEach((slide, idx) => {
          slide.addEventListener("click", () => {
            main.moveToIdx(idx);
          });
        });
      }

      slider.on("created", () => {
        addActive(slider.track.details.rel);
        addClickEvents();
        main.on("animationStarted", (main) => {
          removeActive();
          const next = main.animator.targetIdx || 0;
          addActive(main.track.absToRel(next));
          slider.moveToIdx(next);
        });
      });
    };
  }

  BACheetahCarousel = function (node, settings) {

    let settingsApp = JSON.parse(settings);

    if (settingsApp.lengthPhotos > 0) {
      let slider = new KeenSlider(
        '[id="' + node + '"]',
        {
          slides: {
            origin: settingsApp.origin , //default nao existir
            perView: settingsApp.styleGallery === 'default' ? settingsApp.per_view : 1, //default nao existir
            spacing: settingsApp.spacing, //default nao existir
          },
          disabled: false,
          loop: settingsApp.loop === 'true', //default nao existir
          vertical: false, //default nao existir
          mode: settingsApp.mode, //default nao existir isso mas pode ter "free" ou "free-snap"
          renderMode: settingsApp.styleGallery === 'carousel' ? "custom" : "precision",//carousel
          selector: settingsApp.styleGallery === 'carousel' ? ".carousel__cell" : ".keen-slider__slide"//carousel
        },
        [
          settingsApp.auto_play === 'true' ? autoplayMethod(settingsApp.speed) : null,
          //carousel
          settingsApp.styleGallery === 'carousel' ? (slider) => {

            // var carousel = slider.querySelector('.keen-slider__parent');
            var cells = slider.container.querySelectorAll('.carousel__cell');
            var cellCount = slider.slides.length; // cellCount set from cells-range input value
            var cellWidth = slider.container.offsetWidth;
            var cellHeight = slider.container.offsetHeight;
            var isHorizontal = true;
            var rotateFn = isHorizontal ? 'rotateY' : 'rotateX';
            var radius, theta;

            function rotate() {
              var angle = 360 * slider.track.details.progress * -1;
              slider.container.style.transform = 'translateZ(' + -radius + 'px) ' +
                rotateFn + '(' + angle + 'deg)';
            }

            function initCarousel() {
              theta = 360 / cellCount;
              var cellSize = isHorizontal ? cellWidth : cellHeight;
              radius = Math.round( ( cellSize / 2) / Math.tan( Math.PI / cellCount ) );
              for ( var i=0; i < cells.length; i++ ) {
                var cell  = cells[i];
                cell.style.opacity = 1;
                var cellAngle = theta * i;
                console.log(cellAngle, radius);
                cell.style.transform = rotateFn + '(' + cellAngle + 'deg) translateZ(' + radius + 'px)';
              }
            }

            slider.on("created", () => {
              initCarousel();
              rotate();
            });
            slider.on("detailsChanged", rotate);
          } : null
          //fim carousel
        ]
      );

      if (settingsApp.styleGallery === 'thumbnail') {

        var thumbnails = new KeenSlider(
          '[id="' + node + '-thumbnails"]',
          {
            initial: 0,
            slides: {
              perView: 4, //default nao existir
              spacing: 10, //default nao existir
            },
            loop: false, //default nao existir
            vertical: false, //default nao existir
            mode: "free-snap", //default nao existir isso mas pode ter "free" ou "free-snap"
          },
          [
            autoplayMethod(),
            ThumbnailPlugin(slider)
          ]
        );
      }
    }
  };

})(jQuery);
