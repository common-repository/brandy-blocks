(function ($) {
  $(document).ready(function () {
    const defaultConfig = {
      direction: "horizontal",
      slidesPerView: 1,
      spaceBetween: 30,
      speed: 1000,
      breakpoints: {
        782: {
          slidesPerView: 2,
        },
        1000: {
          slidesPerView: 3,
        },
      },
    };
    const nextIcon = window.brandyBlocksTestimonials?.nextIcon;
    const backIcon = window.brandyBlocksTestimonials?.backIcon;
    function getConfigFromBlock(blockEl) {
      const isInEditor = $(blockEl).hasClass("block-editor-block-list__block");
      const isInfiniteLoop = !isInEditor && blockEl.dataset.loop === "true";
      const isAutoPlay = !isInEditor && blockEl.dataset.autoPlay === "true";
      const slidesPerView =
        blockEl.dataset.slidesPerView ?? defaultConfig.slidesPerView;
      return {
        ...defaultConfig,
        breakpoints: {
          ...defaultConfig.breakpoints,
          782: {
            slidesPerView: Math.min(
              slidesPerView,
              defaultConfig.breakpoints[782].slidesPerView
            ),
          },
          1000: {
            slidesPerView,
          },
        },
        spaceBetween:
          blockEl.dataset.itemsSpacing ?? defaultConfig.spaceBetween,
        ...(blockEl.dataset.scrollbarEnabled === "true"
          ? {
              scrollbar: {
                el: blockEl.querySelector(".swiper-scrollbar"),
                draggable: true,
                placement: "outside",
                snapOnRelease: true,
              },
            }
          : {
              navigation: {
                prevEl: blockEl.querySelector(".swiper-button-prev"),
                nextEl: blockEl.querySelector(".swiper-button-next"),
              },
              pagination: {
                el: blockEl.querySelector(".swiper-pagination"),
                type: blockEl.dataset.paginationType ?? "bullets",
                clickable: true,
              },
            }),
        loop: isInfiniteLoop,
        autoplay: isAutoPlay
          ? {
              delay: blockEl.dataset.autoPlayDelay ?? 3000,
              pauseOnMouseEnter: true,
            }
          : false,
      };
    }
    window.addEventListener("brandyRefreshTestimonials", function (ev) {
      const targetBlockId = ev.detail?.block;
      $(".wp-block-brandy-testimonials").each((ind, block) => {
        if (targetBlockId != null && block.dataset.block !== targetBlockId) {
          return;
        }
        $(block)
          .find(".swiper")
          .each((__, swiperEl) => {
            new Swiper(swiperEl, getConfigFromBlock(block));
            if (nextIcon) {
              $(block).find(".swiper-button-next").html(nextIcon);
            }
            if (backIcon) {
              $(block).find(".swiper-button-prev").html(backIcon);
            }
          });
      });
    });
    window.dispatchEvent(new CustomEvent("brandyRefreshTestimonials"));
  });
})(window.jQuery);
