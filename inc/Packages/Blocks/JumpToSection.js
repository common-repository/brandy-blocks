(function ($) {
  $(document).on(
    "click",
    ".wp-block-brandy-jump-to-section-trigger",
    function () {
      const coreBlock = $(this).closest(".wp-block-brandy-jump-to-section");

      const jumpTo = $(coreBlock).data("jumpTo");
      const jumpSection = $(coreBlock).data("jumpSection");
      const scrollBehaviour = $(coreBlock).data("scrollBehaviour");
      const scrollDuration = $(coreBlock).data("scrollDuration");

      const scrollData = {};
      let duration = 0;

      if (jumpTo === "top") {
        scrollData.scrollTop = 0;
      }

      if (jumpTo === "bottom") {
        scrollData.scrollTop = $(document).height();
      }

      if (jumpTo === "section") {
        scrollData.scrollTop = $(jumpSection).offset().top;
      }

      if (scrollBehaviour === "smooth") {
        duration = "smooth";
      }

      if (scrollBehaviour === "custom") {
        duration = scrollDuration;
      }

      $("html, body").animate(scrollData, duration);
    }
  );
})(window.jQuery);
