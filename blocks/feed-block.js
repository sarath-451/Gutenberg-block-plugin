(function(blocks, editor, components, i18n) {
  var el = wp.element.createElement;
  var registerBlockType = blocks.registerBlockType;

  registerBlockType('custom-gutenberg-blocks/feed-block', {
    title: i18n.__('Feed Block', 'custom-gutenberg-blocks'),
    description: i18n.__('Custom feed block to display posts.', 'custom-gutenberg-blocks'),
    category: 'widgets',
    icon: 'admin-post',
    edit: function() {
      return el('p', {}, i18n.__('Feed Block Edit Mode', 'custom-gutenberg-blocks'));
    },
    save: function() {
      return el('p', {}, i18n.__('Feed Block Frontend Mode', 'custom-gutenberg-blocks'));
    },
  });
})(
  window.wp.blocks,
  window.wp.editor,
  window.wp.components,
  window.wp.i18n
);
