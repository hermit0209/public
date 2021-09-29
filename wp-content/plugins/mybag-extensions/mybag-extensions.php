<?php
/**
 * Plugin Name:    	MyBag Extensions
 * Plugin URI:     	https://demo2.chethemes.com/mybag/
 * Description:    	This selection of extensions compliment our lean and mean theme for WooCommerce, MyBag. Please note: they don’t work with any WordPress theme, just MyBag.
 * Author:         	Transvelo
 * Author URL:     	http://transvelo.com/
 * Version:        	1.2.8
 * Text Domain: 	mybag-extensions
 * Domain Path: 	/languages
 * WC tested up to: 3.6.2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! class_exists( 'MyBag_Extensions' ) ) {
	/**
	 * Main MyBag_Extensions Class
	 *
	 * @class MyBag_Extensions
	 * @version	1.0.0
	 * @since 1.0.0
	 * @package	Kudos
	 * @author Ibrahim
	 */
	final class MyBag_Extensions {
		/**
		 * MyBag_Extensions The single instance of MyBag_Extensions.
		 * @var 	object
		 * @access  private
		 * @since 	1.0.0
		 */
		private static $_instance = null;

		/**
		 * The token.
		 * @var     string
		 * @access  public
		 * @since   1.0.0
		 */
		public $token;

		/**
		 * The version number.
		 * @var     string
		 * @access  public
		 * @since   1.0.0
		 */
		public $version;

		/**
		 * Constructor function.
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function __construct () {
			$this->token 			= 'mybag-extensions';
			$this->version 			= '0.0.1';
			// $this->hook 			= (string)apply_filters( 'homepage_control_hook', 'homepage' );
			
			// add_action( 'plugins_loaded', array( $this, 'maybe_migrate_data' ) );
			
			// register_activation_hook( __FILE__, array( $this, 'install' ) );
			
			add_action( 'plugins_loaded', array( $this, 'setup_constants' ) );
			add_action( 'plugins_loaded', array( $this, 'includes' ) );
			add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
			
			/* Setup Customizer. */
			//require_once( 'classes/class-homepage-control-customizer.php' );
			
			/* Reorder Components. */
			// if ( ! is_admin() ) {
			// 	add_action( 'get_header', array( $this, 'maybe_apply_restructuring_filter' ) );
			// }
		}

		/**
		 * Main MyBag_Extensions Instance
		 *
		 * Ensures only one instance of MyBag_Extensions is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 * @static
		 * @see MyBag_Extensions()
		 * @return Main Kudos instance
		 */
		public static function instance () {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * Setup plugin constants
		 *
		 * @access public
		 * @since  1.0.0
		 * @return void
		 */
		public function setup_constants() {

			// Plugin Folder Path
			if ( ! defined( 'MYBAG_EXTENSIONS_DIR' ) ) {
				define( 'MYBAG_EXTENSIONS_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL
			if ( ! defined( 'MYBAG_EXTENSIONS_URL' ) ) {
				define( 'MYBAG_EXTENSIONS_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File
			if ( ! defined( 'MYBAG_EXTENSIONS_FILE' ) ) {
				define( 'MYBAG_EXTENSIONS_FILE', __FILE__ );
			}
		}

		/**
		 * Include required files
		 *
		 * @access public
		 * @since  1.0.0
		 * @return void
		 */
		public function includes() {

			#-----------------------------------------------------------------
			# Static Block Post Type
			#-----------------------------------------------------------------
			require_once MYBAG_EXTENSIONS_DIR . '/modules/post-types/static-block.php';

			#-----------------------------------------------------------------
			# Visual Composer Extensions
			#-----------------------------------------------------------------
			require_once MYBAG_EXTENSIONS_DIR . '/modules/js_composer/js_composer.php';

			#-----------------------------------------------------------------
			# Theme Shoprtcodes
			#-----------------------------------------------------------------
			require_once MYBAG_EXTENSIONS_DIR . '/modules/theme-shortcodes/theme-shortcodes.php';
		}

		/**
		 * Load the localisation file.
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'mybag-extensions', false, dirname( plugin_basename( MYBAG_EXTENSIONS_FILE ) ) . '/languages/' );
		}

		/**
		 * Cloning is forbidden.
		 *
		 * @since 1.0.0
		 */
		public function __clone () {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'mybag-extensions' ), '1.0.0' );
		}

		/**
		 * Unserializing instances of this class is forbidden.
		 *
		 * @since 1.0.0
		 */
		public function __wakeup () {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'mybag-extensions' ), '1.0.0' );
		}
	}
}

/**
 * Returns the main instance of MyBag_Extensions to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object MyBag_Extensions
 */
function MyBag_Extensions() {
	return MyBag_Extensions::instance();
}

/**
 * Initialise the plugin
 */
MyBag_Extensions();