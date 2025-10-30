const { registerBlockType } = wp.blocks;
const { SelectControl, Spinner } = wp.components;
const { useSelect } = wp.data;

registerBlockType('mas-static-content/navigation-static-content', {
    title: 'MegaMenu',
    icon: 'screenoptions',
    category: 'widgets',
    attributes: {
        staticContentId: { type: 'number' },
    },
    edit: (props) => {
        const { attributes, setAttributes } = props;

        const posts = useSelect((select) => {
            return select('core').getEntityRecords('postType', 'mas_static_content', { per_page: -1 });
        }, [wp.data.select('core').getEntityRecords]); 

        

        const options = posts.map((post) => ({
            label: post.title.rendered,
            value: post.id,
        }));

        // Optional: add a default option
        options.unshift({ label: 'Select Static Content', value: 0 });

        return (
            <SelectControl
                label="Select Static Content"
                value={attributes.staticContentId || 0}
                options={options}
                onChange={(value) => setAttributes({ staticContentId: parseInt(value) })}
            />
        );
    },
    save: () => null, // Server-rendered
});
