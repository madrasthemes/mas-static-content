<?php
/**
 * Plugin Name:       MAS Static Content
 * Plugin URI:        https://github.com/madrasthemes/mas-static-content
 * Description:       This plugin helps to create a custom post type static content and use it with shortcode.
 * Version:           1.1.3
 * Requires at least: 7.1
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
add_action(
	'init',
	function () {
		register_block_type(
			dirname( MAS_STATIC_CONTENT_PLUGIN_FILE ) . '/build',
			array(
				'render_callback' => 'mas_static_content_render_megamenu_block',
			)
		);
	}
);


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

	if ( ! $post || 'mas_static_content' !== $post->post_type || post_password_required( $post ) ) {
		return '';
	}

	// Only published content, or private content for users allowed to read it.
	$status = get_post_status( $post );
	if ( 'publish' !== $status && ! ( 'private' === $status && current_user_can( 'read_private_posts' ) ) ) {
		return '';
	}

	/**
	 * Filters the post content.
	 *
	 * @since 1.1.1
	 *
	 * @param string $content Content of the static content post.
	 */
	return apply_filters( 'the_content', $post->post_content );
}


/**
 * Unique access instance for Mas_Static_Content class
 */
function mas_static_content() {
	return Mas_Static_Content::instance();
}

// Global for backwards compatibility.
$GLOBALS['mas_static_content'] = mas_static_content();
