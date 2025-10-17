<?php
/**
 * Plugin Name:       MAS Static Content
 * Plugin URI:        https://github.com/madrasthemes/mas-static-content
 * Description:       This plugin helps to create a custom post type static content and use it with shortcode.
 * Version:           1.1.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            MadrasThemes
 * Author URI:        https://madrasthemes.com/
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       mas-static-content
 * Domain Path:       /languages
 *
 * @package Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define MAS_STATIC_CONTENT_PLUGIN_FILE.
if ( ! defined( 'MAS_STATIC_CONTENT_PLUGIN_FILE' ) ) {
	define( 'MAS_STATIC_CONTENT_PLUGIN_FILE', __FILE__ );
}

// Include the main Mas_Static_Content class.
if ( ! class_exists( 'Mas_Static_Content' ) ) {
	include_once dirname( MAS_STATIC_CONTENT_PLUGIN_FILE ) . '/includes/class-mas-static-content.php';
}

/**
 * Register the dynamic block type and render callback.
 */
add_action( 'init', function() {
    register_block_type( 'mas-static-content/navigation-static-content', array(
        'render_callback' => 'mas_static_content_render_megamenu_block',
        'attributes'      => array(
            'staticContentId' => array( 'type' => 'integer' ),
        ),
        'supports'        => array(
            'inserter' => true,
        ),
        'parent' => array('core/navigation', 'core/navigation-submenu'),
    ) );
});


/**
 * Render callback for the MegaMenu block.
 *
 * @param array $attributes Block attributes from JS.
 * @return string Rendered HTML content.
 */
function mas_static_content_render_megamenu_block( $attributes ) {


    if ( empty( $attributes['staticContentId'] ) ) {
        return '';
    }

    $post = get_post( $attributes['staticContentId'] );

    if ( ! $post ) {
        return '';
    }

    // Return the content with filters (so shortcodes, blocks, etc. render properly)
    return apply_filters( 'the_content', $post->post_content );
}


add_action( 'enqueue_block_editor_assets', function() {
    wp_enqueue_script(
        'mas_static_content-megamenu-block',
       	plugin_dir_url( MAS_STATIC_CONTENT_PLUGIN_FILE ) . 'build/index.js',
        [ 'wp-blocks', 'wp-element', 'wp-components', 'wp-data', 'wp-editor' ],
        filemtime( dirname( MAS_STATIC_CONTENT_PLUGIN_FILE ) . '/build/index.js' ),
        true
    );
});


/**
 * Unique access instance for Mas_Static_Content class
 */
function mas_static_content() {
	return Mas_Static_Content::instance();
}

// Global for backwards compatibility.
$GLOBALS['mas_static_content'] = mas_static_content();
