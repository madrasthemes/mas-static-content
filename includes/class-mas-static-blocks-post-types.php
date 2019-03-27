<?php
/**
 * Post Types
 *
 * Registers post types and taxonomies.
 *
 * @package Mas_Static_Blocks/Classes
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Post types Class.
 */
class Mas_Static_Blocks_Post_Types {

    /**
     * Hook in methods.
     */
    public static function init() {
        add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
        add_filter( 'rest_api_allowed_post_types', array( __CLASS__, 'rest_api_allowed_post_types' ) );
        add_action( 'mas_static_blocks_after_register_post_type', array( __CLASS__, 'maybe_flush_rewrite_rules' ) );
        add_action( 'mas_static_blocks_flush_rewrite_rules', array( __CLASS__, 'flush_rewrite_rules' ) );
        add_filter( 'gutenberg_can_edit_post_type', array( __CLASS__, 'gutenberg_can_edit_post_type' ), 10, 2 );
    }

    /**
     * Register core post types.
     */
    public static function register_post_types() {

        if ( ! is_blog_installed() ) {
            return;
        }

        do_action( 'mas_static_blocks_register_post_type' );

        // If theme support changes, we may need to flush permalinks since some are changed based on this flag.
        if ( update_option( 'current_theme_supports_mas_static_blocks', current_theme_supports( 'mas-static-blocks' ) ? 'yes' : 'no' ) ) {
            update_option( 'mas_static_blocks_queue_flush_rewrite_rules', 'yes' );
        }

        register_post_type(
            'mas_static_block',
            apply_filters(
                'mas_static_blocks_register_post_type_mas_static_block',
                array(
                    'labels'              => array(
                        'name'                  => esc_html__( 'Static Blocks', 'mas-static-blocks' ),
                        'singular_name'         => esc_html__( 'Static Block', 'mas-static-blocks' ),
                        'all_items'             => esc_html__( 'All Static Blocks', 'mas-static-blocks' ),
                        'menu_name'             => esc_html_x( 'Static Blocks', 'Admin menu name', 'mas-static-blocks' ),
                        'add_new'               => esc_html__( 'Add New', 'mas-static-blocks' ),
                        'add_new_item'          => esc_html__( 'Add new static block', 'mas-static-blocks' ),
                        'edit'                  => esc_html__( 'Edit', 'mas-static-blocks' ),
                        'edit_item'             => esc_html__( 'Edit static block', 'mas-static-blocks' ),
                        'new_item'              => esc_html__( 'New static block', 'mas-static-blocks' ),
                        'view_item'             => esc_html__( 'View static block', 'mas-static-blocks' ),
                        'view_items'            => esc_html__( 'View static blocks', 'mas-static-blocks' ),
                        'search_items'          => esc_html__( 'Search static blocks', 'mas-static-blocks' ),
                        'not_found'             => esc_html__( 'No static blocks found', 'mas-static-blocks' ),
                        'not_found_in_trash'    => esc_html__( 'No static blocks found in trash', 'mas-static-blocks' ),
                        'parent'                => esc_html__( 'Parent static block', 'mas-static-blocks' ),
                        'featured_image'        => esc_html__( 'Static Block image', 'mas-static-blocks' ),
                        'set_featured_image'    => esc_html__( 'Set static block image', 'mas-static-blocks' ),
                        'remove_featured_image' => esc_html__( 'Remove static block image', 'mas-static-blocks' ),
                        'use_featured_image'    => esc_html__( 'Use as static block image', 'mas-static-blocks' ),
                        'insert_into_item'      => esc_html__( 'Insert into static block', 'mas-static-blocks' ),
                        'uploaded_to_this_item' => esc_html__( 'Uploaded to this static block', 'mas-static-blocks' ),
                        'filter_items_list'     => esc_html__( 'Filter static blocks', 'mas-static-blocks' ),
                        'items_list_navigation' => esc_html__( 'Static Blocks navigation', 'mas-static-blocks' ),
                        'items_list'            => esc_html__( 'Static Blocks list', 'mas-static-blocks' ),
                    ),
                    'description'         => esc_html__( 'This is where you can add new static blocks to your site.', 'mas-static-blocks' ),
                    'public'              => true,
                    'show_ui'             => true,
                    'map_meta_cap'        => true,
                    'publicly_queryable'  => true,
                    'exclude_from_search' => true,
                    'hierarchical'        => false, // Hierarchical causes memory issues - WP loads all records!
                    'rewrite'             => false,
                    'query_var'           => true,
                    'supports'            => array( 'title', 'editor', 'revisions' ),
                    'has_archive'         => false,
                    'show_in_nav_menus'   => true,
                    'show_in_menu'        => true,
                    'show_in_rest'        => true,
                    'menu_icon'           => 'dashicons-admin-post'
                )
            )
        );

        do_action( 'mas_static_blocks_after_register_post_type' );
    }

    /**
     * Flush rules if the event is queued.
     *
     * @since 3.3.0
     */
    public static function maybe_flush_rewrite_rules() {
        if ( 'yes' === get_option( 'mas_static_blocks_queue_flush_rewrite_rules' ) ) {
            update_option( 'mas_static_blocks_queue_flush_rewrite_rules', 'no' );
            self::flush_rewrite_rules();
        }
    }

    /**
     * Flush rewrite rules.
     */
    public static function flush_rewrite_rules() {
        flush_rewrite_rules();
    }

    /**
     * Disable Gutenberg for videos.
     *
     * @param bool   $can_edit Whether the post type can be edited or not.
     * @param string $post_type The post type being checked.
     * @return bool
     */
    public static function gutenberg_can_edit_post_type( $can_edit, $post_type ) {
        return in_array( $post_type, array( 'mas_static_block' ) ) ? false : $can_edit;
    }

    /**
     * Added video for Jetpack related posts.
     *
     * @param  array $post_types Post types.
     * @return array
     */
    public static function rest_api_allowed_post_types( $post_types ) {
        $post_types[] = 'mas_static_block';

        return $post_types;
    }
}

Mas_Static_Blocks_Post_Types::init();
