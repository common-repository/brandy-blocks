/** Register attribute */
function addResponsiveConditionsAttribute(settings, name) {
  if (typeof settings.attributes === "undefined") {
    return settings;
  }
  settings.attributes = Object.assign(settings.attributes, {
    hideOnDesktop: {
      type: "boolean",
      default: false,
      attribute: "data-hide-on-desktop",
    },
    hideOnTablet: {
      type: "boolean",
      default: false,
      attribute: "data-hide-on-tablet",
    },
    hideOnMobile: {
      type: "boolean",
      default: false,
      attribute: "data-hide-on-mobile",
    },
  });

  return settings;
}

wp.hooks.addFilter(
  "blocks.registerBlockType",
  "brandy-blocks/responsive-conditions-attribute",
  addResponsiveConditionsAttribute
);

/** Display controls */

const ResponsiveConditionsControls = wp.compose.createHigherOrderComponent(
  (BlockEdit) => {
    return (props) => {
      const { Fragment } = wp.element;
      const { ToggleControl, PanelBody } = wp.components;
      const { InspectorControls } = wp.blockEditor;
      const { __ } = wp.i18n;
      const { attributes, setAttributes, isSelected } = props;
      const { hideOnDesktop, hideOnTablet, hideOnMobile } = attributes;
      return (
        <Fragment>
          <BlockEdit {...props} />
          {isSelected && (
            <InspectorControls>
              <PanelBody title={__("Responsive Conditions", "brandy-blocks")}>
                <ToggleControl
                  label={wp.i18n.__("Hide on desktop", "brandy-blocks")}
                  checked={!!hideOnDesktop}
                  onChange={(value) => {
                    setAttributes({
                      hideOnDesktop: value,
                    });
                  }}
                />
                <ToggleControl
                  label={wp.i18n.__("Hide on tablet", "brandy-blocks")}
                  checked={!!hideOnTablet}
                  onChange={(value) => {
                    setAttributes({
                      hideOnTablet: value,
                    });
                  }}
                />
                <ToggleControl
                  label={wp.i18n.__("Hide on mobile", "brandy-blocks")}
                  checked={!!hideOnMobile}
                  onChange={(value) => {
                    setAttributes({
                      hideOnMobile: value,
                    });
                  }}
                />
              </PanelBody>
            </InspectorControls>
          )}
        </Fragment>
      );
    };
  },
  "responsiveConditionsControls"
);

wp.hooks.addFilter(
  "editor.BlockEdit",
  "brandy-blocks/responsive-conditions-controls",
  ResponsiveConditionsControls
);

/**
 * Save function
 */
function addResponsiveClass(props, blockType, attributes) {
  const { hideOnDesktop, hideOnTablet, hideOnMobile } = attributes;

  if (hideOnDesktop) {
    Object.assign(props, { "data-hide-on-desktop": "true" });
  }
  if (hideOnTablet) {
    Object.assign(props, { "data-hide-on-tablet": "true" });
  }
  if (hideOnMobile) {
    Object.assign(props, { "data-hide-on-mobile": "true" });
  }

  return props;
}

wp.hooks.addFilter(
  "blocks.getSaveContent.extraProps",
  "brandy-blocks/responsive-conditions-props",
  addResponsiveClass
);

const addStyle = wp.compose.createHigherOrderComponent((BlockListBlock) => {
  return (props) => {
    const { attributes } = props;

    const { hideOnDesktop, hideOnTablet, hideOnMobile } = attributes;

    const hideOns = [];

    if (hideOnDesktop) {
      hideOns.push("Desktop");
    }
    if (hideOnTablet) {
      hideOns.push("Tablet");
    }
    if (hideOnMobile) {
      hideOns.push("Mobile");
    }

    const wrapperProps = {
      ...(props.wrapperProps ?? {}),
      ...(hideOns.length < 1
        ? {}
        : {
            ["data-hide-ons"]: hideOns.join(", "),
          }),
    };
    return <BlockListBlock {...props} wrapperProps={wrapperProps} />;
  };
}, "addStyle");

wp.hooks.addFilter(
  "editor.BlockListBlock",
  "brandy-blocks/responsive-conditions-style",
  addStyle
);
