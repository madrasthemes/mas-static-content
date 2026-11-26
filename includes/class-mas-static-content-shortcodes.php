<?php
/**
 * Shortcodes
 *
 * @package Mas_Static_Content/Classes
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Mas_Static_Content Shortcodes class.
 */
class Mas_Static_Content_Shortcodes {

	/**
	 * Init shortcodes.
	 */
	public static function init() {
		$shortcodes = array(
			'mas_static_content' => __CLASS__ . '::static_content',
		);

		foreach ( $shortcodes as $shortcode => $function ) {
			add_shortcode( apply_filters( "{$shortcode}_shortcode_tag", $shortcode ), $function );
		}
	}

	/**
	 * List multiple static_content shortcode.
	 *
	 * @param array $atts Attributes.
	 * @return string
	 */
	public static function static_content( $atts ) {
		$atts = shortcode_atts(
			array(
				'id'    => 0,
				'class' => '',
				'wrap'  => 1,
			),
			$atts,
			'mas_static_content'
		);

		$post_id = absint( $atts['id'] );

		if ( ! $post_id ) {
			return '';
		}

		static $rendering_ids = array();

		if ( isset( $rendering_ids[ $post_id ] ) ) {
			return '';
		}

		$original_post       = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;
		$original_post_id    = $original_post instanceof WP_Post ? absint( $original_post->ID ) : '';
		$content             = '';
		$static_content_post = get_post( $post_id );

		if ( ! $static_content_post || 'mas_static_content' !== $static_content_post->post_type || post_password_required( $static_content_post ) ) {
			return '';
		}

		$status = get_post_status( $static_content_post );

		if ( 'publish' !== $status && ! ( 'private' === $status && current_user_can( 'read_private_posts' ) ) ) {
			return '';
		}

		$rendering_ids[ $post_id ] = true;

		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$GLOBALS['post'] = $static_content_post;

		setup_postdata( $static_content_post );

		$static_content = get_the_content();

		if ( ! empty( $static_content ) ) {
			$wrap = wp_validate_boolean( $atts['wrap'] );

			ob_start();

			do_action( 'mas_static_content_before_shortcode_content', $atts );

			if ( $wrap ) {
				$class = ! empty( $atts['class'] ) ? ' ' . $atts['class'] : '';
				echo '<div class="mas-static-content' . esc_attr( $class ) . '">';
			}

			/**
			 * Filters the post content.
			 *
			 * @since 1.0.7
			 *
			 * @param string $content Content of the current post.
			 */
			$static_content = str_replace( 'post_id="inherit"', 'post_id="' . $original_post_id . '"', $static_content );
			$static_content = apply_filters( 'the_content', $static_content );
			$static_content = str_replace( ']]>', ']]&gt;', $static_content );
			echo $static_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			if ( $wrap ) {
				echo '</div>';
			}

			do_action( 'mas_static_content_after_shortcode_content', $atts );

			$content = ob_get_clean();
		}

		// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$GLOBALS['post'] = $original_post;

		wp_reset_postdata();

		unset( $rendering_ids[ $post_id ] );

		return $content;
	}
}
