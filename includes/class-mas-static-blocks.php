<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'Mas_Static_Blocks' ) ) {
    /**
     * Main plugin class
     *
     * @class Mas_Static_Blocks
     * @version 1.0.0
     */
    final class Mas_Static_Blocks {
        /**
         * Version
         *
         * @var string
         */
        public $version = '0.0.1';

        /**
         * The single instance of the class.
         *
         * @var Mas_Static_Blocks
         */
        protected static $_instance = null;

        /**
         * Main Mas_Static_Blocks Instance.
         *
         * Ensures only one instance of Mas_Static_Blocks is loaded or can be loaded.
         *
         * @static
         * @return Mas_Static_Blocks - Main instance.
         */
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Cloning is forbidden.
         */
        public function __clone() {
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'mas-static-blocks' ), '1.0.0' );
        }

        /**
         * Unserializing instances of this class is forbidden.
         */
        public function __wakeup() {
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'mas-static-blocks' ), '1.0.0' );
        }

        /**
         * Mas_Static_Blocks Constructor.
         */
        public function __construct() {
            $this->define_constants();
            $this->includes();
            $this->init_hooks();

            do_action( 'mas_static_blocks_loaded' );
        }

        /**
         * Define constants
         */
        private function define_constants() {
            $this->define( 'MAS_STATIC_BLOCKS_ABSPATH', dirname( MAS_STATIC_BLOCKS_PLUGIN_FILE ) . '/' );
            $this->define( 'MAS_STATIC_BLOCKS_PLUGIN_BASENAME', plugin_basename( MAS_STATIC_BLOCKS_PLUGIN_FILE ) );
            $this->define( 'MAS_STATIC_BLOCKS_VERSION', $this->version );
            $this->define( 'MAS_STATIC_BLOCKS_DELIMITER', '|' );
        }

        /**
         * Init Mas_Static_Blocks when Wordpress Initializes
         */
        public function includes() {
            /**
             * Core classes.
             */
            include_once MAS_STATIC_BLOCKS_ABSPATH . 'includes/class-mas-static-blocks-post-types.php';
            include_once MAS_STATIC_BLOCKS_ABSPATH . 'includes/class-mas-static-blocks-shortcodes.php';
        }

        /**
         * Init Mas_Static_Blocks when Wordpress Initializes
         */
        public function init_hooks() {
            add_action( 'init', array( $this, 'init' ), 0 );
            add_action( 'init', array( 'Mas_Static_Blocks_Shortcodes', 'init' ) );
        }

        /**
         * Init Mas_Static_Blocks when WordPress Initialises.
         */
        public function init() {
            // Before init action.
            do_action( 'before_mas_static_blocks_init' );

            // Set up localisation.
            $this->load_plugin_textdomain();

            // Init action.
            do_action( 'mas_static_blocks_init' );
        }

        /**
         * Load Localisation files.
         *
         * Note: the first-loaded translation file overrides any following ones if the same translation is present.
         *
         * Locales found in:
         *      - WP_LANG_DIR/mas-static-blocks/mas-static-blocks-LOCALE.mo
         *      - WP_LANG_DIR/plugins/mas-static-blocks-LOCALE.mo
         */
        public function load_plugin_textdomain() {
            $locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
            $locale = apply_filters( 'plugin_locale', $locale, 'mas-static-blocks' );

            unload_textdomain( 'mas-static-blocks' );
            load_textdomain( 'mas-static-blocks', WP_LANG_DIR . '/mas-static-blocks/mas-static-blocks-' . $locale . '.mo' );
            load_plugin_textdomain( 'mas-static-blocks', false, plugin_basename( dirname( MAS_STATIC_BLOCKS_PLUGIN_FILE ) ) . '/i18n/languages' );
        }

        /**
         * Get the plugin url.
         * @return string
         */
        public function plugin_url() {
            return untrailingslashit( plugins_url( '/', MAS_STATIC_BLOCKS_PLUGIN_FILE ) );
        }

        /**
         * Get the plugin path.
         * @return string
         */
        public function plugin_path() {
            return untrailingslashit( plugin_dir_path( MAS_STATIC_BLOCKS_PLUGIN_FILE ) );
        }

        /**
         * Define constant if not already set.
         *
         * @param  string $name
         * @param  string|bool $value
         */
        private function define( $name, $value ) {
            if ( ! defined( $name ) ) {
                define( $name, $value );
            }
        }
    }
}