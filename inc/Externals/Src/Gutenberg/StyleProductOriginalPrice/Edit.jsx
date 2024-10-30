import React, { useEffect } from "react";
import CustomSettings from "./components/CustomSettings";

/** Register attribute */
function addWooProductPriceBlockAttribute(settings, name) {
  if (settings.attributes == undefined) {
    return settings;
  }

  if ("woocommerce/product-price" === name) {
    settings.attributes = Object.assign(settings.attributes, {
      originalPriceTypography: {
        type: "object",
        default: {
          fontSize: "",
          color: "",
          appearance: {
            key: "default",
            name: "Default",
            style: {
              fontWeight: "default",
              fontStyle: "default",
            },
          },
        },
      },
    });
  }

  return settings;
}

wp.hooks.addFilter(
  "blocks.registerBlockType",
  "brandy-blocks/woo-product-original-price-attributes",
  addWooProductPriceBlockAttribute
);

/** Display controls */
const StylePriceControls = wp.compose.createHigherOrderComponent(
  (BlockEdit) => {
    return (props) => {
      const { Fragment } = wp.element;
      const { attributes, setAttributes, name, clientId, isSelected } = props;

      return (
        <Fragment>
          <BlockEdit {...props} />
          {isSelected && "woocommerce/product-price" === name && (
            <CustomSettings
              attributes={attributes}
              setAttributes={setAttributes}
              clientId={clientId}
            />
          )}
        </Fragment>
      );
    };
  },
  "stylePriceControls"
);

wp.hooks.addFilter(
  "editor.BlockEdit",
  "brandy-blocks/woo-product-original-price-controls",
  StylePriceControls
);

const addStyle = wp.compose.createHigherOrderComponent((BlockListBlock) => {
  return (props) => {
    const { name, attributes } = props;

    if (name === "woocommerce/product-price") {
      const wrapperProps = props.wrapperProps ?? {};

      wrapperProps.style = {
        ...wrapperProps.style,
        "--original-price-color":
          attributes.originalPriceTypography?.color ?? "",
        "--original-price-size":
          attributes.originalPriceTypography?.fontSize ?? "",
        "--original-price-weight":
          attributes.originalPriceTypography?.appearance?.style?.fontWeight ??
          "",
        "--original-price-style":
          attributes.originalPriceTypography?.appearance?.style?.fontStyle ??
          "",
      };

      return <BlockListBlock {...props} wrapperProps={wrapperProps} />;
    }

    // For all other blocks, return unchanged
    return <BlockListBlock {...props} />;
  };
}, "addStyle");

wp.hooks.addFilter("editor.BlockListBlock", "my-plugin/add-style", addStyle);
