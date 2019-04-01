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
            'mas_static_content'    => __CLASS__ . '::static_content',
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
        $atts       = (array) $atts;
        $content    = '';

        if( isset( $atts['id'] ) ) {
            $post       = get_post( $atts['id'] );
            $content    = apply_filters( 'the_content', $post->post_content );
        }

        if( ! empty( $content ) ) {
            $class      = isset( $atts['class'] ) ? ' ' . $atts['class'] : '';
            $content    = '<div class="mas-static-content' . esc_attr( $class ) . '">' . $content . '</div>';
        }

        return $content;
    }
}
