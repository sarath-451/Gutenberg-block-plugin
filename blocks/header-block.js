(function(blocks, editor, components, i18n) {
  var el = wp.element.createElement;
  var registerBlockType = blocks.registerBlockType;
  var TextControl = components.TextControl;

  registerBlockType('custom-gutenberg-blocks/header-block', {
    title: i18n.__('Header Block', 'custom-gutenberg-blocks'),
    description: i18n.__('Custom header block with ACF fields.', 'custom-gutenberg-blocks'),
    category: 'common',
    icon: 'heading',
    attributes: {
      headingText: {
        type: 'string',
        source: 'text',
        selector: 'h1',
      },
      subheadingText: {
        type: 'string',
        source: 'text',
        selector: 'p',
      },
    },
    edit: function(props) {
      var attributes = props.attributes;

      function updateHeadingText(newText) {
        props.setAttributes({ headingText: newText });
      }

      function updateSubheadingText(newText) {
        props.setAttributes({ subheadingText: newText });
      }

      return [
        el('div', { className: props.className },
          el('h1', {}, attributes.headingText),
          el(TextControl, {
            label: i18n.__('Heading Text', 'custom-gutenberg-blocks'),
            value: attributes.headingText,
            onChange: updateHeadingText,
          }),
          el('p', {}, attributes.subheadingText),
          el(TextControl, {
            label: i18n.__('Subheading Text', 'custom-gutenberg-blocks'),
            value: attributes.subheadingText,
            onChange: updateSubheadingText,
          })
        )
      ];
    },
    save: function(props) {
      var attributes = props.attributes;
      
      return el('div', { className: props.className },
        el('h1', {}, attributes.headingText),
        el('p', {}, attributes.subheadingText)
      );

    },
  });
})(
  window.wp.blocks,
  window.wp.editor,
  window.wp.components,
  window.wp.i18n
);
