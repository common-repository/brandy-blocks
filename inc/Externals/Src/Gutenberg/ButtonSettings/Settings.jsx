const {
  __experimentalToolsPanelItem,
  __experimentalBorderBoxControl,
  __experimentalToolsPanel,
} = wp.components;
const { PanelColorSettings } = wp.blockEditor;
const { __ } = wp.i18n;

export default function Settings(props) {
  const { attributes, setAttributes } = props;

  const { hoverBackgroundColor, hoverTextColor, hoverBorder } = attributes;

  return (
    <>
      <PanelColorSettings
        title={__("Hover colors", "brandy-blocks")}
        initialOpen={false}
        enableAlpha={true}
        colorSettings={[
          {
            value: hoverBackgroundColor,
            onChange: (newColor) =>
              setAttributes({
                hoverBackgroundColor: newColor,
              }),
            label: __("Background hover", "brandy-blocks"),
          },
          {
            value: hoverTextColor,
            onChange: (newColor) =>
              setAttributes({
                hoverTextColor: newColor,
              }),
            label: __("Text hover", "brandy-blocks"),
          },
        ]}
      />
      <__experimentalToolsPanel label={__("External Border & Shadow")}>
        <__experimentalToolsPanelItem
          hasValue={() => true}
          label={__("Border hover")}
          onDeselect={() => {}}
        >
          <__experimentalBorderBoxControl
            colors={wp.data.select("core/editor").getEditorSettings().colors}
            label={__("Border hover")}
            value={hoverBorder}
            onChange={(v) => {
              setAttributes({ hoverBorder: v });
            }}
          />
        </__experimentalToolsPanelItem>
      </__experimentalToolsPanel>
    </>
  );
}
