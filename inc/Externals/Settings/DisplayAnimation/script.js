(function ($) {
  $(document).ready(() => {
    $("[data-animate-on-view='true']").each((_, el) => {
      observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("bb-is-on-view");
          } else {
            entry.target.classList.remove("bb-is-on-view");
          }
        });
      });
      observer.observe(el);
    });
  });
})(window.jQuery);
