function initGoogleMapScript() {
  ((g) => {
    var h,
      a,
      k,
      p = "The Google Maps JavaScript API",
      c = "google",
      l = "importLibrary",
      q = "__ib__",
      m = document,
      b = window;
    b = b[c] || (b[c] = {});
    var d = b.maps || (b.maps = {}),
      r = new Set(),
      e = new URLSearchParams(),
      u = () =>
        h ||
        (h = new Promise(async (f, n) => {
          await (a = m.createElement("script"));
          e.set("libraries", [...r] + "");
          for (k in g)
            e.set(
              k.replace(/[A-Z]/g, (t) => "_" + t[0].toLowerCase()),
              g[k]
            );
          e.set("callback", c + ".maps." + q);
          a.src = `https://maps.${c}apis.com/maps/api/js?` + e;
          d[q] = f;
          a.onerror = () => (h = n(Error(p + " could not load.")));
          a.nonce = m.querySelector("script[nonce]")?.nonce || "";
          m.head.append(a);
        }));
    d[l]
      ? console.warn(p + " only loads once. Ignoring:", g)
      : (d[l] = (f, ...n) => r.add(f) && u().then(() => d[l](f, ...n)));
  })({ key: window.bbGoogleMap?.apiKey ?? "", v: "weekly" });
}

(function ($) {
  $(document).ready(function () {
    initGoogleMapScript();

    async function initMaps() {
      const { Map } = await google.maps.importLibrary("maps");
      const { AdvancedMarkerElement } =
        await google.maps.importLibrary("marker");
      const geocoder = new google.maps.Geocoder();

      async function initMap(block) {
        if (block.length < 1) {
          return;
        }

        const dom = $(block).find("#brandy-blocks-google-map");

        const zoomLevel = $(block).data("zoomLevel") ?? 10;
        const mapStyle = $(block).data("mapStyle") ?? "ROADMAP";
        const currentAddress = $(block).data("currentAddress") ?? "";
        // const language = $(block).data("language") ?? "en";
        geocoder.geocode(
          { address: currentAddress },
          function (results, status) {
            const position = { lat: -25.344, lng: 131.031 };
            if (status == google.maps.GeocoderStatus.OK) {
              position.lat = results[0].geometry.location.latitude;
              position.lng = results[0].geometry.location.longitude;
            }
            const map = new Map(dom, {
              zoom: zoomLevel,
              center: position,
              mapId: `bb-map-${new Date().getTime()}`,
              mapTypeId: mapStyle,
            });

            new AdvancedMarkerElement({
              map,
              position: position,
              title: "Google Map",
            });
          }
        );
      }

      document
        .querySelectorAll(".wp-block-brandy-google-map")
        .forEach(function (block) {
          initMap(block);
        });

      window.addEventListener("bb_google_map_attribute_changed", function (ev) {
        if (ev.detail?.block) {
          initMap(ev.detail?.block);
        }
      });
    }

    initMaps();
  });
})(window.jQuery);
