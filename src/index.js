import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { SelectControl, Spinner } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { __, sprintf } from '@wordpress/i18n';

import metadata from './block.json';

registerBlockType( metadata.name, {
    edit: ( { attributes, setAttributes } ) => {
        const blockProps = useBlockProps();

        // `getEntityRecords` returns null until the request resolves (or if it fails).
        const { posts, hasResolved } = useSelect( ( select ) => {
            const args = [ 'postType', 'mas_static_content', { per_page: -1 } ];
            return {
                posts: select( coreStore ).getEntityRecords( ...args ),
                hasResolved: select( coreStore ).hasFinishedResolution( 'getEntityRecords', args ),
            };
        }, [] );

        if ( ! hasResolved ) {
            return (
                <div { ...blockProps }>
                    <Spinner />
                </div>
            );
        }

        const options = [
            { label: __( 'Select Static Content', 'mas-static-content' ), value: 0 },
            ...( posts || [] ).map( ( post ) => ( {
                label:
                    post.title?.rendered ||
                    sprintf(
                        /* translators: %d: static content post ID */
                        __( '#%d (no title)', 'mas-static-content' ),
                        post.id
                    ),
                value: post.id,
            } ) ),
        ];

        return (
            <div { ...blockProps }>
                <SelectControl
                    label={ __( 'Select Static Content', 'mas-static-content' ) }
                    value={ attributes.staticContentId || 0 }
                    options={ options }
                    onChange={ ( value ) =>
                        setAttributes( { staticContentId: parseInt( value, 10 ) || 0 } )
                    }
                    __nextHasNoMarginBottom
                />
            </div>
        );
    },
    save: () => null, // Server-rendered
} );
