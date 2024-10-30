import Settings from "./Settings";

function isButtonBlock(name) {
  return name === "core/button" || name === "woocommerce/product-button";
}

/** Register attribute */
function addButtonAttributes(settings, name) {
  if (typeof settings.attributes === "undefined") {
    return settings;
  }
  if (!isButtonBlock(name)) {
    return settings;
  }
  settings.attributes = Object.assign(settings.attributes, {
    hoverBackgroundColor: {
      type: "string",
      default: "",
    },
    hoverTextColor: {
      type: "string",
      default: "",
    },
    hoverBorder: {
      type: "object",
      default: {},
    },
  });

  return settings;
}

wp.hooks.addFilter(
  "blocks.registerBlockType",
  "brandy-blocks/button-attribute",
  addButtonAttributes
);

/** Display controls */

const ButtonSettingsControls = wp.compose.createHigherOrderComponent(
  (BlockEdit) => {
    return (props) => {
      const { Fragment } = wp.element;
      const { InspectorControls } = wp.blockEditor;
      const { isSelected, name } = props;
      const canAddSettings = isButtonBlock(name);
      return (
        <Fragment>
          <BlockEdit {...props} />
          {isSelected && canAddSettings && (
            <InspectorControls>
              <Settings {...props} />
            </InspectorControls>
          )}
        </Fragment>
      );
    };
  },
  "ButtonSettingsControls"
);

wp.hooks.addFilter(
  "editor.BlockEdit",
  "brandy-blocks/button-settings-controls",
  ButtonSettingsControls
);

const addExternalButtonStyle = wp.compose.createHigherOrderComponent(
  (BlockListBlock) => {
    return (props) => {
      const { attributes, name } = props;
      const extraWrapperProps = props.wrapperProps ?? {};
      if (isButtonBlock(name)) {
        extraWrapperProps.style = {
          ...extraWrapperProps.style,
          ...getStyleFromAttributes(attributes),
        };
      }
      return (
        <BlockListBlock {...props} wrapperProps={{ ...extraWrapperProps }} />
      );
    };
  },
  "addExternalButtonStyle"
);

wp.hooks.addFilter(
  "editor.BlockListBlock",
  "brandy-blocks/external-button-settings",
  addExternalButtonStyle
);

/**
 * Save function
 */
function addButtonSettingsProps(props, blockType, attributes) {
  if (!isButtonBlock(blockType.name)) {
    return props;
  }

  props.style = {
    ...props.style,
    ...getStyleFromAttributes(attributes),
  };

  return props;
}

wp.hooks.addFilter(
  "blocks.getSaveContent.extraProps",
  "brandy-blocks/button-settings-props",
  addButtonSettingsProps
);

function getStyleFromAttributes(attributes) {
  const style = {};

  const prefix = (attributes.className ?? "").includes("is-style-outline")
    ? "outline"
    : "primary";

  if (attributes.hoverTextColor) {
    style[`--button-hover-color`] = attributes.hoverTextColor;
  }
  if (attributes.hoverBackgroundColor) {
    style[`--button-hover-background-color`] = attributes.hoverBackgroundColor;
  }
  if (Object.keys(attributes.hoverBorder ?? {}).length > 0) {
    if (Object.keys(attributes.hoverBorder ?? {}).includes("top")) {
      ["top", "bottom", "left", "right"].forEach((aspect) => {
        style[`--button-hover-border-${aspect}-c`] =
          attributes.hoverBorder[aspect].color;
        style[`--button-hover-border-${aspect}-w`] =
          attributes.hoverBorder[aspect].width;
        style[`--button-hover-border-${aspect}-s`] =
          attributes.hoverBorder[aspect].style;
      });
    } else {
      style[`--button-hover-border-c`] = attributes.hoverBorder.color;
      style[`--button-hover-border-w`] = attributes.hoverBorder.width;
      style[`--button-hover-border-s`] = attributes.hoverBorder.style;
    }
  }
  return style;
}
