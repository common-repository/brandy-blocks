/** Register attribute */
function addDisplayAnimationAttribute(settings) {
  if (typeof settings.attributes === "undefined") {
    return settings;
  }
  settings.attributes = Object.assign(settings.attributes, {
    displayAnimation: {
      type: "object",
      default: {
        type: "none",
        duration: 3,
        animateOnView: false,
      },
    },
  });

  return settings;
}

wp.hooks.addFilter(
  "blocks.registerBlockType",
  "brandy-blocks/display-animation-attribute",
  addDisplayAnimationAttribute
);

/** Display controls */

const DisplayAnimationControls = wp.compose.createHigherOrderComponent(
  (BlockEdit) => {
    return (props) => {
      const { Fragment } = wp.element;
      const { SelectControl, RangeControl, ToggleControl } = wp.components;
      const { InspectorAdvancedControls } = wp.blockEditor;
      const { __ } = wp.i18n;
      const { attributes, setAttributes, isSelected } = props;
      return (
        <Fragment>
          <BlockEdit {...props} />
          {isSelected && (
            <InspectorAdvancedControls>
              <SelectControl
                label={__("Display animation effect")}
                value={attributes.displayAnimation?.type ?? "none"}
                onChange={(selection) => {
                  setAttributes({
                    displayAnimation: {
                      ...attributes.displayAnimation,
                      type: selection,
                    },
                  });
                }}
                __nextHasNoMarginBottom
              >
                <option value="none">None</option>
                <optgroup label="Fade in">
                  <option value="fade_in_down">Fade in down</option>
                  <option value="fade_in_up">Fade in up</option>
                  <option value="fade_in_left">Fade in left</option>
                  <option value="fade_in_right">Fade in right</option>
                </optgroup>
                <optgroup label="Flip">
                  <option value="flip_y">Flip horizontal</option>
                  <option value="flip_x">Flip vertical</option>
                </optgroup>
                <optgroup label="Zoom">
                  <option value="zoom_in">Zoom in</option>
                  <option value="zoom_out">Zoom out</option>
                </optgroup>
                {/* <optgroup label="Slide">
                  <option value="slide_in">Slide in</option>
                  <option value="slide_out">Slide out</option>
                </optgroup> */}
              </SelectControl>
              {attributes.displayAnimation?.type &&
                attributes.displayAnimation.type != "none" && (
                  <>
                    <RangeControl
                      __nextHasNoMarginBottom
                      label="Animation duration"
                      value={attributes.displayAnimation?.duration ?? 3}
                      onChange={(value) =>
                        setAttributes({
                          displayAnimation: {
                            ...attributes.displayAnimation,
                            duration: value,
                          },
                        })
                      }
                      min={0}
                      max={10}
                      step={0.1}
                    />
                    <ToggleControl
                      label={wp.i18n.__(
                        "Animate when viewing",
                        "brandy-blocks"
                      )}
                      checked={!!attributes.displayAnimation?.animateOnView}
                      onChange={(value) => {
                        setAttributes({
                          displayAnimation: {
                            ...attributes.displayAnimation,
                            animateOnView: value,
                          },
                        });
                      }}
                    />
                  </>
                )}
            </InspectorAdvancedControls>
          )}
        </Fragment>
      );
    };
  },
  "displayAnimationControls"
);

wp.hooks.addFilter(
  "editor.BlockEdit",
  "brandy-blocks/display-animation-controls",
  DisplayAnimationControls
);

/**
 * Save function
 */
function addAnimationAttribute(props, blockType, attributes) {
  const { displayAnimation } = attributes;

  if (displayAnimation == null || displayAnimation.type == null) {
    return props;
  }

  if (displayAnimation?.type == "none") {
    return props;
  }

  Object.assign(props, {
    ["data-animate-effect"]: displayAnimation.type,
    style: {
      ...(props.style ?? {}),
      animationDuration: `${displayAnimation.duration ?? 3}s`,
    },
    ["data-animate-on-view"]: displayAnimation.animateOnView ? "true" : "false",
  });
  return props;
}

wp.hooks.addFilter(
  "blocks.getSaveContent.extraProps",
  "brandy-blocks/display-animation-props",
  addAnimationAttribute
);
