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

		if ( ! $atts['id'] ) {
			return '';
		}

		$original_post       = $GLOBALS['post'];
		$content             = '';
		$static_content_post = get_post( $atts['id'] ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$GLOBALS['post']     = $static_content_post;

		setup_postdata( $static_content_post );

		$static_content = get_the_content();

		if ( ! empty( $static_content ) ) {
			ob_start();

			do_action( 'mas_static_content_before_shortcode_content', $atts );

			if ( $atts['wrap'] ) {
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
			$static_content = str_replace( 'post_id="inherit"', 'post_id="' . $original_post->ID . '"', $static_content );
			$static_content = apply_filters( 'the_content', $static_content );
			$static_content = str_replace( ']]>', ']]&gt;', $static_content );
			echo $static_content;

			if ( $atts['wrap'] ) {
				echo '</div>';
			}

			do_action( 'mas_static_content_after_shortcode_content', $atts );

			$content = ob_get_clean();
		}

		$GLOBALS['post'] = $original_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		wp_reset_postdata();

		return $content;
	}
}
