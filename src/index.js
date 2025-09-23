const { registerBlockType } = wp.blocks;
const { SelectControl } = wp.components;
const { useSelect } = wp.data;

registerBlockType('megamenu/navigation-static-content', {
    title: 'Static Content Menu',
    icon: 'screenoptions',
    category: 'widgets',
    attributes: {
        staticContentId: { type: 'number' }
    },
    edit: (props) => {
        const { attributes, setAttributes } = props;
        const posts = useSelect(select => 
            select('core').getEntityRecords('postType', 'mas_static_content', { per_page: -1 }), 
            []
        ) || [];

        return (
            <SelectControl
                label="Select Static Content"
                value={attributes.staticContentId}
                options={posts.map(post => ({ label: post.title.rendered, value: post.id }))}
                onChange={(value) => setAttributes({ staticContentId: parseInt(value) })}
            />
        );
    },
    save: () => null // Always rendered server-side
});
