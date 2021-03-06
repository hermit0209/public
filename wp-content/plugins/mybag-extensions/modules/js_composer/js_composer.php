<?php

/**
 * Module Name 			: Visual Composer Addons
 * Module Description 	: Provides additional Visual Composer Elements for the MyBag theme
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! class_exists( 'MyBag_VCExtensions' ) ) {
	class MyBag_VCExtensions {

		/**
		 * List of paths.
		 *
		 * @var array
		 */
		private $paths = array();

		/**
		 * Constructor function.
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'setup_constants' ),	10 );
			add_action( 'init', array( $this, 'setPaths' ),			20 );
			add_action( 'init', array( $this, 'includes' ),			30 );
			add_action( 'init', array( $this, 'map_vc_elements' ),	40 );
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
			if ( ! defined( 'MYBAG_VC_PLUGIN_EXTENSIONS_DIR' ) ) {
				define( 'MYBAG_VC_PLUGIN_EXTENSIONS_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL
			if ( ! defined( 'MYBAG_VC_PLUGIN_EXTENSIONS_URL' ) ) {
				define( 'MYBAG_VC_PLUGIN_EXTENSIONS_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File
			if ( ! defined( 'MYBAG_VC_PLUGIN_EXTENSIONS_FILE' ) ) {
				define( 'MYBAG_VC_PLUGIN_EXTENSIONS_FILE', __FILE__ );
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
			# Shortcodes
			#-----------------------------------------------------------------
			require_once MYBAG_VC_PLUGIN_EXTENSIONS_DIR . '/includes/elements/mybag-vc-blog.php';
			require_once MYBAG_VC_PLUGIN_EXTENSIONS_DIR . '/includes/elements/mybag-vc-product-carousel.php';
			require_once MYBAG_VC_PLUGIN_EXTENSIONS_DIR . '/includes/elements/mybag-vc-product-categories.php';
			require_once MYBAG_VC_PLUGIN_EXTENSIONS_DIR . '/includes/elements/mybag-vc-product-tabs.php';
			require_once MYBAG_VC_PLUGIN_EXTENSIONS_DIR . '/includes/elements/mybag-vc-product.php';
			require_once MYBAG_VC_PLUGIN_EXTENSIONS_DIR . '/includes/elements/mybag-vc-products-with-image.php';
		}

		public function map_vc_elements() {

			// Check if Visual Composer is installed
			if ( ! defined( 'WPB_VC_VERSION' ) ) {
				// Display notice that Visual Compser is required
				return;
			}

			require_once  $this->path( 'CONFIG_DIR', 'map.php');
		}

		/**
		 * Setter for paths
		 *
		 * @since  4.2
		 * @access protected
		 */
		public function setPaths() {
			$this->paths = Array(
				'APP_ROOT' => MYBAG_VC_PLUGIN_EXTENSIONS_DIR,
				'WP_ROOT' => preg_replace( '/$\//', '', ABSPATH ),
				'APP_DIR' => plugin_basename( MYBAG_VC_PLUGIN_EXTENSIONS_DIR ),
				'CONFIG_DIR' => MYBAG_VC_PLUGIN_EXTENSIONS_DIR . '/config',
				'ASSETS_DIR' => MYBAG_VC_PLUGIN_EXTENSIONS_DIR . '/assets',
				'ASSETS_DIR_NAME' => 'assets',
			);
		}

		/**
		 * Gets absolute path for file/directory in filesystem.
		 *
		 * @since  4.2
		 * @access public
		 * @param $name        - name of path dir
		 * @param string $file - file name or directory inside path
		 * @return string
		 */
		public function path( $name, $file = '' ) {
			return $this->paths[$name] . ( strlen( $file ) > 0 ? '/' . preg_replace( '/^\//', '', $file ) : '' );
		}
	}
}

// Finally initialize code
new MyBag_VCExtensions();