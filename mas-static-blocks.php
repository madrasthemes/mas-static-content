<?php
/**
 * Plugin Name: MAS Static Blocks
 * Plugin URI: https://madrasthemes.com/mas-static-blocks
 * Description: This plugin helps to create a CPT static blocks and use it using shortcode.
 * Version: 0.0.1
 * Author: MadrasThemes
 * Author URI: https://madrasthemes.com/
 * Requires at least: 4.8
 * Tested up to: 5.0
 *
 * Text Domain: mas-static-blocks
 * Domain Path: /languages/
 *
 * @package Mas_Static_Blocks
 * @category Core
 * @author Madras Themes
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Define MAS_STATIC_BLOCKS_PLUGIN_FILE.
if ( ! defined( 'MAS_STATIC_BLOCKS_PLUGIN_FILE' ) ) {
    define( 'MAS_STATIC_BLOCKS_PLUGIN_FILE', __FILE__ );
}

// Include the main Mas_Static_Blocks class.
if ( ! class_exists( 'Mas_Static_Blocks' ) ) {
    include_once dirname( MAS_STATIC_BLOCKS_PLUGIN_FILE ) . '/includes/class-mas-static-blocks.php';
}

/**
 * Unique access instance for Mas_Static_Blocks class
 */
function Mas_Static_Blocks() {
    return Mas_Static_Blocks::instance();
}

// Global for backwards compatibility.
$GLOBALS['mas_static_blocks'] = Mas_Static_Blocks();