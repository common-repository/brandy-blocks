(function ($) {
  $(document).ready(function () {
    // Random Banner
    const brandyBanners = $(".brandy-block-products-with-banners").find(
      ".brandy-products-with-banners-banner"
    );
    if (brandyBanners.length) {
      doRandomBanner(brandyBanners);
    }

    function createNewData(oldBannerData) {
      // Create an array of values from the original object
      let bannerArgs = Object.values(oldBannerData);
      // Shuffle the values array
      for (let i = bannerArgs.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [bannerArgs[i], bannerArgs[j]] = [bannerArgs[j], bannerArgs[i]];
      }
      // Create a new object with shuffled values but same keys
      const newBannerData = {};
      Object.keys(oldBannerData).forEach(function (key, index) {
        newBannerData[key] = bannerArgs[index];
      });
      return newBannerData;
    }

    function doRandomBanner(brandyBanners) {
      const oldBannerData = {};
      brandyBanners.each(function (idx, value) {
        const banner_wrapper_id = $(this).closest(".product").attr("id");
        oldBannerData[banner_wrapper_id] = value;
      });
      const newBannerData = createNewData(oldBannerData);
      $.each(newBannerData, function (key, value) {
        $("#" + key).html(newBannerData[key]);
      });
    }
  });
})(window.jQuery);
