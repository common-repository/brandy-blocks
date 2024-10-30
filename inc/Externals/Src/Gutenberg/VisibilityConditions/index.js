const { InspectorControls } = wp.blockEditor;
const { PanelBody, CheckboxControl } = wp.components;
const { __ } = wp.i18n;
const { createHigherOrderComponent } = wp.compose;
const { addFilter } = wp.hooks;
import './style.css';

// SVG Icon as a React Element
const visibilityIcon = React.createElement(
  'svg',
  {
    width: '19',
    height: '14',
    viewBox: '0 0 19 14',
    fill: 'none',
    xmlns: 'http://www.w3.org/2000/svg',
  },
  React.createElement('path', {
    'fill-rule': 'evenodd',
    'clip-rule': 'evenodd',
    d: 'M18 7.00017C16.5151 10.0655 12.7959 13 9.4998 13C6.20371 13 2.48449 10.0655 1 6.99981',
    stroke: '#C6D4E1',
    'stroke-width': '1.2',
    'stroke-linecap': 'round',
    'stroke-linejoin': 'round',
  }),
  React.createElement('path', {
    'fill-rule': 'evenodd',
    'clip-rule': 'evenodd',
    d: 'M18 7.00017C16.5151 3.93484 12.7965 1 9.50039 1C6.2043 1 2.48449 3.93412 1 6.99981',
    stroke: '#C6D4E1',
    'stroke-width': '1.2',
    'stroke-linecap': 'round',
    'stroke-linejoin': 'round',
  }),
  React.createElement('path', {
    d: 'M12.05 7C12.05 8.42016 10.9083 9.57143 9.5 9.57143C8.09167 9.57143 6.95 8.42016 6.95 7C6.95 5.57984 8.09167 4.42857 9.5 4.42857C10.9083 4.42857 12.05 5.57984 12.05 7Z',
    stroke: '#C6D4E1',
    'stroke-width': '1.2',
    'stroke-linecap': 'round',
    'stroke-linejoin': 'round',
  })
);

// Extend block attributes to include visibility settings
const addVisibilityAttributes = (settings) => {
  if (settings.attributes == undefined) {
    return settings;
  }

  settings.attributes = Object.assign(settings.attributes, {
    hideLoggedIn: { type: 'boolean', default: false },
    hideLoggedOut: { type: 'boolean', default: false },
    hideRoles: { type: 'array', default: [] },
    hideBrowsers: { type: 'array', default: [] },
    hideDays: { type: 'array', default: [] },
  });

  return settings;
};
addFilter(
  'blocks.registerBlockType',
  'BrandyConditionBlocks/visibility/attributes',
  addVisibilityAttributes
);

const withConditionalVisibility = createHigherOrderComponent(
  (BlockComponent) => {
    return (props) => {
      const { attributes, setAttributes, isSelected } = props;
      const { hideLoggedIn, hideLoggedOut } = attributes;
      const hideRoles = attributes.hideRoles ?? [];
      const hideBrowsers = attributes.hideBrowsers ?? [];
      const hideDays = attributes.hideDays ?? [];

      const userRoles = brandyBlocksData.roles;
      const browsers = brandyBlocksData.browsers;
      const daysOfWeek = [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday',
      ];

      const toggleRole = (roleValue) => {
        const newRoles = hideRoles.includes(roleValue)
          ? hideRoles.filter((r) => r !== roleValue)
          : [...hideRoles, roleValue];
        setAttributes({ hideRoles: newRoles });
      };

      const currentUserRole = brandyBlocksData.currentUserRole;
      const currentBrowser = brandyBlocksData.currentBrowser;
      const currentDay = brandyBlocksData.currentDay;

      const shouldHideBlock =
        (hideLoggedIn && brandyBlocksData.isUserLoggedIn) ||
        (hideLoggedOut && !brandyBlocksData.isUserLoggedIn) ||
        hideRoles.includes(currentUserRole) ||
        hideBrowsers.includes(currentBrowser) ||
        hideDays.includes(currentDay);

      const hasVisibilityOptions =
        hideLoggedIn ||
        hideLoggedOut ||
        hideRoles.length > 0 ||
        hideBrowsers.length > 0 ||
        hideDays.length > 0;
      const blockClassName = hasVisibilityOptions
        ? shouldHideBlock
          ? 'brandy-conditional-visibility-blocks brandy-hidden-block'
          : 'brandy-conditional-visibility-blocks'
        : '';
      // Using createElement instead of JSX
      return (
        <Fragment>
          <BlockComponent {...props} className={blockClassName} />
          {isSelected && (
            <InspectorControls>
              <PanelBody
                title={
                  <>
                    {visibilityIcon}{' '}
                    {__('Visibility Conditions', 'brandy-blocks')}
                  </>
                }
                initialOpen={false}
                className="brandy-visibility-panel"
              >
                <div className="brandy-visibility-controls-wrapper">
                  <div className="brandy-visibility-controls-label">
                    {__('User State', 'brandy-blocks')}
                  </div>
                  <CheckboxControl
                    label={__('Hide when logged in')}
                    checked={hideLoggedIn}
                    onChange={(newVal) =>
                      setAttributes({ hideLoggedIn: newVal })
                    }
                    className="brandy-user-state-checkbox"
                  />
                  <CheckboxControl
                    label={__('Hide when logged out')}
                    checked={hideLoggedOut}
                    onChange={(newVal) =>
                      setAttributes({ hideLoggedOut: newVal })
                    }
                    className="brandy-user-state-checkbox"
                  />
                </div>
                <div className="brandy-visibility-controls-wrapper">
                  <div className="brandy-visibility-controls-label">
                    {__('Choose roles to hide from', 'brandy-blocks')}
                  </div>
                  {userRoles.map((role) => (
                    <CheckboxControl
                      key={role.value}
                      label={role.label}
                      checked={hideRoles.includes(role.value)}
                      onChange={() => toggleRole(role.value)}
                      className="brandy-role-checkbox"
                    />
                  ))}
                </div>
                <div className="brandy-visibility-controls-wrapper">
                  <div className="brandy-visibility-controls-label">
                    {__('Choose browsers to hide', 'brandy-blocks')}
                  </div>
                  {browsers.map((browser) => (
                    <CheckboxControl
                      key={browser}
                      label={browser}
                      checked={hideBrowsers.includes(browser)}
                      onChange={(newVal) => {
                        const newBrowsers = newVal
                          ? [...hideBrowsers, browser]
                          : hideBrowsers.filter((b) => b !== browser);
                        setAttributes({ hideBrowsers: newBrowsers });
                      }}
                      className="brandy-browser-checkbox"
                    />
                  ))}
                </div>
                <div className="brandy-visibility-controls-wrapper">
                  <div className="brandy-visibility-controls-label">
                    {__('Choose days to hide', 'brandy-blocks')}
                  </div>
                  {daysOfWeek.map((day) => (
                    <CheckboxControl
                      key={day}
                      label={day}
                      checked={hideDays.includes(day)}
                      onChange={(newVal) => {
                        const newDays = newVal
                          ? [...hideDays, day]
                          : hideDays.filter((d) => d !== day);
                        setAttributes({ hideDays: newDays });
                      }}
                      className="brandy-day-checkbox"
                    />
                  ))}
                </div>
              </PanelBody>
            </InspectorControls>
          )}
        </Fragment>
      );
    };
  },
  'withConditionalVisibility'
);

addFilter(
  'editor.BlockEdit',
  'BrandyConditionBlocks/VisibilityEditor',
  withConditionalVisibility
);
addFilter(
  'editor.BlockListBlock',
  'BrandyConditionBlocks/editor/blockClasses',
  withConditionalVisibility
);
