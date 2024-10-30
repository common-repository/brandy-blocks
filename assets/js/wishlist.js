(function ($) {
  function loadingShortcode() {
    $(".brandy-wishlist-shortcode-loading").css("display", "flex");
  }
  function stopLoadingShortcode() {
    $(".brandy-wishlist-shortcode-loading").hide();
  }

  function reloadFragments(fragments, detail) {
    $(`.brandy-wishlist-shortcode`).replaceWith(fragments.shortcode ?? "");
    $(".brandy-wishlist-element .brandy-count-badge").text(
      fragments.count || ""
    );
    if (!detail.productId) {
      return;
    }

    if (detail.action === "add") {
      $(`[data-block-name="brandy/add-to-wishlist"][data-product-id="${detail.productId}"]`).addClass("added");
    } else {
      $(`[data-block-name="brandy/add-to-wishlist"][data-product-id="${detail.productId}"]`).removeClass("added");
    }
  }

  function sendAddRequest(productId) {
    const params = new URLSearchParams({
      product_id: productId,
    });
    const nonce = window.brandyWishlist?.ajax?.nonces?.add_wishlist_item ?? "";
    return new Promise((resolve, reject) => {
      $.ajax({
        url: window.brandyWishlist.ajax.path + `?${params.toString()}`,
        type: "GET",
        data: {
          action: "brandy_add_to_wishlist",
          nonce,
        },
        success: function (response) {
          if (response.success) {
            resolve(response.data);
          }
          reject(false);
        },
        error: function () {
          reject(false);
        },
      });
    });
  }

  function sendRemoveRequest(productId) {
    const params = new URLSearchParams({
      product_id: productId,
    });
    const nonce =
      window.brandyWishlist?.ajax?.nonces?.remove_wishlist_item ?? "";
    return new Promise((resolve, reject) => {
      $.ajax({
        url: window.brandyWishlist.ajax.path + `?${params.toString()}`,
        type: "GET",
        data: {
          action: "brandy_remove_wishlist_item",
          nonce,
        },
        success: function (response) {
          if (response.success) {
            resolve(response.data);
          }
          reject(false);
        },
        error: function () {
          reject(false);
        },
      });
    });
  }

  $(document).ready(function () {
    /**
     * Add to wishlist
     */
    $(document.body).on(
      "click",
      "[data-block-name='brandy/add-to-wishlist']:not(.added):not(.adding)",
      function () {
        const productId = $(this).attr("data-product-id");

        if (!productId) {
          return;
        }

        $(this).addClass("adding");

        loadingShortcode();
        sendAddRequest(productId)
          .then((data) => {
            if (!data.fragments) {
              return;
            }

            $(this).addClass("added");
            window.dispatchEvent(
              new CustomEvent("brandy-update-wishlist", {
                detail: {
                  fragments: data.fragments ?? {},
                  productId: data.product_id,
                  action: "add",
                },
              })
            );
          })
          .finally(() => {
            $(this).removeClass("adding");
            stopLoadingShortcode();
          });
      }
    );

    /**
     * Remove from wishlist
     */
    $(document.body).on(
      "click",
      ".brandy-wishlist-item-remove, [data-block-name='brandy/add-to-wishlist'].added:not(.removing)",
      function () {
        const productId = $(this).attr("data-product-id");
        $(this).closest(".brandy-wishlist-drawer__item").addClass("removing");
        $(this).addClass("removing");
        sendRemoveRequest(productId)
          .then((data) => {
            if (!data.fragments) {
              return;
            }
            window.dispatchEvent(
              new CustomEvent("brandy-update-wishlist", {
                detail: {
                  fragments: data.fragments ?? {},
                  productId: data.product_id,
                  action: "remove",
                },
              })
            );
            $(this).removeClass("added");
          })
          .finally(() => {
            $(this)
              .closest(".brandy-wishlist-drawer__item")
              .removeClass("removing");
            $(this).removeClass("removing");
          });
      }
    );
    window.addEventListener("brandy-update-wishlist", (e) => {
      reloadFragments(e.detail.fragments, e.detail);
    });
  });
})(window.jQuery);
