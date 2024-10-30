import { InspectorControls, PanelColorSettings } from "@wordpress/block-editor";
import { __ } from "@wordpress/i18n";
import {
  FontSizePicker,
  __experimentalSpacer as Spacer,
  PanelBody,
  CustomSelectControl,
} from "@wordpress/components";
import React, { useMemo } from "react";

const getFontSizes = (themeFontSizes) => {
  if (Array.isArray(themeFontSizes) && themeFontSizes.length > 0) {
    themeFontSizes.forEach((fontSize) => {
      delete fontSize.fluid;
    });
  }

  return themeFontSizes;
};

const appearanceOptions = [
  {
    key: "default",
    name: "Default",
    style: {
      fontWeight: "default",
      fontStyle: "default",
    },
  },
  {
    key: "thin",
    name: "Thin",
    style: { fontStyle: "normal", fontWeight: "100" },
  },
  {
    key: "extra_light",
    name: "Extra Light",
    style: { fontStyle: "normal", fontWeight: "200" },
  },
  {
    key: "light",
    name: "Light",
    style: { fontStyle: "normal", fontWeight: "300" },
  },
  {
    key: "regular",
    name: "Regular",
    style: { fontStyle: "normal", fontWeight: "400" },
  },
  {
    key: "medium",
    name: "Medium",
    style: { fontStyle: "normal", fontWeight: "500" },
  },
  {
    key: "semi_bold",
    name: "Semi Bold",
    style: { fontStyle: "normal", fontWeight: "600" },
  },
  {
    key: "bold",
    name: "Bold",
    style: { fontStyle: "normal", fontWeight: "700" },
  },
  {
    key: "extra_bold",
    name: "Extra Bold",
    style: { fontStyle: "normal", fontWeight: "800" },
  },
  {
    key: "black",
    name: "Black",
    style: { fontStyle: "normal", fontWeight: "900" },
  },
  {
    key: "thin_italic",
    name: "Thin Italic",
    style: { fontStyle: "italic", fontWeight: "100" },
  },
  {
    key: "extra_light_italic",
    name: "Extra Light Italic",
    style: { fontStyle: "italic", fontWeight: "200" },
  },
  {
    key: "light_italic",
    name: "Light Italic",
    style: { fontStyle: "italic", fontWeight: "300" },
  },
  {
    key: "regular_italic",
    name: "Regular Italic",
    style: { fontStyle: "italic", fontWeight: "400" },
  },
  {
    key: "medium_italic",
    name: "Medium Italic",
    style: { fontStyle: "italic", fontWeight: "500" },
  },
  {
    key: "semi_bold_italic",
    name: "Semi Bold Italic",
    style: { fontStyle: "italic", fontWeight: "600" },
  },
  {
    key: "bold_italic",
    name: "Bold Italic",
    style: { fontStyle: "italic", fontWeight: "700" },
  },
  {
    key: "extra_bold_italic",
    name: "Extra Bold Italic",
    style: { fontStyle: "italic", fontWeight: "800" },
  },
  {
    key: "black_italic",
    name: "Black Italic",
    style: { fontStyle: "italic", fontWeight: "900" },
  },
];

const CustomSettings = ({ attributes, setAttributes, clientId }) => {
  const themeFontSizes = brandyGlobalSettings?.typography?.fontSizes?.theme;
  const typography = attributes.originalPriceTypography;

  let fontSizes = useMemo(() => {
    return getFontSizes(themeFontSizes);
  }, [themeFontSizes]);

  return (
    <InspectorControls>
      <PanelColorSettings
        title={__("Original Price Color", "brandy-blocks")}
        initialOpen={false}
        enableAlpha={true}
        colorSettings={[
          {
            value: typography.color,
            onChange: (newColor) =>
              setAttributes({
                originalPriceTypography: { ...typography, color: newColor },
              }),
            label: __("Text", "brandy-blocks"),
          },
        ]}
      />
      <PanelBody
        title={__("Original Price Settings", "brandy-blocks")}
        className="brandy-original-price-settings-panel"
      >
        <Spacer>
          <FontSizePicker
            fontSizes={fontSizes}
            units={["px", "em", "rem", "vw", "vh"]}
            onChange={(newFontSize) =>
              setAttributes({
                originalPriceTypography: {
                  ...typography,
                  fontSize: newFontSize,
                },
              })
            }
            value={typography.fontSize}
          />
        </Spacer>

        <Spacer>
          <CustomSelectControl
            label={__("APPEARANCE", "brandy-blocks")}
            options={appearanceOptions}
            onChange={({ selectedItem }) =>
              setAttributes({
                originalPriceTypography: {
                  ...typography,
                  appearance: selectedItem,
                },
              })
            }
            value={typography.appearance}
          />
        </Spacer>
      </PanelBody>
    </InspectorControls>
  );
};

export default CustomSettings;
