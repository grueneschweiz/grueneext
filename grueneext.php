<?php

/**
 * Plugin Name: Grueneext
 * Plugin URI: http://www.cyrillbolliger.ch
 * Version: 1.1.2
 * Description: Extended functions for the gruene theme
 * Author: Cyrill Bolliger
 * Author URI: http://www.cyrillbolliger.ch
 * Text Domain: grueneext
 * Domain Path: languages
 * GitHub Plugin URI: cyrillbolliger/grueneext
 * License: GPL 2.
 */

/**
 * Copyright 2015 Cyrill Bolliger (email: bolliger@gmx.ch)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * lock out script kiddies: die an direct call
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * abspath to plugins directory
 */
define( 'GRUENEEXT_PLUGIN_PATH', dirname( __FILE__ ) );

/**
 * version number (don't forget to change it also in the header)
 */
define( 'GRUENEEXT_VERSION', '1.1.2' );

/**
 * plugin prefix
 */
define( 'GRUENEEXT_PLUGIN_PREFIX', 'grueneext' );

/**
 * load settings class
 */
require_once( GRUENEEXT_PLUGIN_PATH . '/includes/class-grueneext-settings.php' );


if ( ! class_exists( 'Grueneext_Main' ) ) {
	
	class Grueneext_Main extends Grueneext_Settings {
		
		/*
		 * Construct the plugin object
		 */
		public function __construct() {
			parent::__construct();
			
			register_activation_hook( __FILE__, array( &$this, 'activate' ) );
			register_deactivation_hook( __FILE__, array( &$this, 'deactivate' ) );
			
			add_action( 'init', array( &$this, 'init' ) );
			add_action( 'init', array( &$this, 'fe_init' ) );
			add_action( 'admin_init', array( &$this, 'admin_init' ) );
			add_action( 'admin_menu', array( &$this, 'add_menu' ) );
			add_action( 'plugins_loaded', array( &$this, 'i18n' ) );
			add_action( 'plugins_loaded', array( &$this, 'upgrade' ) );
			add_action( 'wp_enqueue_scripts', array( &$this, 'load_resources' ) );
			add_action( 'admin_enqueue_scripts', array( &$this, 'load_resources' ) );
			add_action( 'media_buttons', array( &$this, 'add_media_button' ), 15 );
			add_action( 'admin_footer', array( &$this, 'add_short_code_generator_html' ), 15 );
			
		}
		
		/**
		 * Activate the plugin
		 */
		public function activate() {
			$this->set_version_number();
			// $this->add_roles_on_plugin_activation();
			// $this->add_capabilities_on_plugin_activation();
			// $this->create_tables_on_plugin_activation();
			// $this->create_default_options_on_first_plugin_activation();
		}
		
		/**
		 * Deactivate the plugin
		 */
		public function deactivate() {
			// $this->remove_capabilities_on_plugin_deactivation();
			// $this->remove_roles_on_plugin_deactivation();
		}
		
		/**
		 * Hook into WP's init action hook.
		 */
		public function init() {
			
		}
		
		/**
		 * Hook into WP's init action hook for frontend pages
		 */
		public function fe_init() {
			if ( ! is_admin() ) {
				$this->short_code_handler();
			}
		}
		
		/**
		 * Hook into WP's admin_init action hook
		 */
		public function admin_init() {
			$this->init_options();
		}
		
		/**
		 * Add media button to the editor
		 */
		public function add_media_button() {
			global $grueneext_admin;
			$grueneext_admin->add_media_button();
		}
		
		public function add_short_code_generator_html() {
			global $grueneext_admin;
			$grueneext_admin->add_short_code_generator_html();
		}
		
		/**
		 * write version number to db
		 */
		public function set_version_number() {
			update_option( GRUENEEXT_PLUGIN_PREFIX . '_version_number', GRUENEEXT_VERSION );
		}
		
		/**
		 * upgrade routine
		 */
		public function upgrade() {
			$current_version = get_option( GRUENEEXT_PLUGIN_PREFIX . '_version_number' );
			
			// if everything is up to date stop here
			if ( GRUENEEXT_VERSION == $current_version ) {
				return; // BREAKPOINT
			}
			
			
			// run the upgrade routine for versions smaller 1.2.0
			if ( - 1 == version_compare( $current_version, '1.2.0' ) ) {
				
				$defaults = [
					'custom_script' => "window.rnwWidget = window.rnwWidget || {};window.rnwWidget.configureWidget = function(options) {options.defaults['ui_onetime_amount_default'] = '5000';};",
					'custom_css'    => '#lema-container.lema-container .lema-step-header, #lema-container.lema-container .lema-step, #lema-container.lema-container .lema-step-content {background:transparent;} #lema-container.lema-container .lema-button, #lema-container.lema-container .lema-step-number span {background: #e10078;} #lema-container.lema-container .lema-step-header {border-bottom: #e10078 1px solid;} #lema-container.lema-container .lema-step-number span, #lema-container.lema-container .lema-step-header-text, #lema-container.lema-container .lema-button-donate {font-family: \'Tahoma\', \'Verdana\', \'Segoe\', \'sans-serif\'; font-weight: bold;} #lema-container.lema-container .lema-step-header-text {color:#e10078;} #lema-container.lema-container .lema-amount-box.lema-active {border: #e10078 2px solid} .lema-overlay-bg {display: none !important;}',
				];
				$this->upgrade_all_sites_options_if_empty( GRUENEEXT_PLUGIN_PREFIX . '_donation_options', $defaults );
			}
			
			// set the current version number
			$this->set_version_number();
			
		}
		
		/**
		 * update the options of all sites if they are empty. use on upgrade only
		 *
		 * @param string $option_id the option to add
		 * @param mixed $value the value
		 */
		public function upgrade_all_sites_options_if_empty( $option_id, $value ) {
			if ( is_multisite() ) {
				global $wpdb;
				$blogs_list = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
				if ( ! empty( $blogs_list ) ) {
					foreach ( $blogs_list as $blog ) {
						switch_to_blog( $blog['blog_id'] );
						if ( empty( get_option( $option_id ) ) ) {
							update_option( $option_id, $value );
						}
						restore_current_blog();
					}
				}
			} else {
				if ( empty( get_option( $option_id ) ) ) {
					update_option( $option_id, $value );
				}
			}
		}
		
		/**
		 * Initialize some custom settings
		 */
		public function init_options() {
			register_setting( GRUENEEXT_PLUGIN_PREFIX . '_donation_settings', GRUENEEXT_PLUGIN_PREFIX . '_donation_options' );
			
			add_settings_section(
				GRUENEEXT_PLUGIN_PREFIX . '_donation_section',
				__( 'Customize donation form', 'grueneext' ),
				[ &$this, 'donation_options_section_header' ],
				GRUENEEXT_PLUGIN_PREFIX . '_donation_settings'
			);
			
			add_settings_field(
				GRUENEEXT_PLUGIN_PREFIX . '_custom_script',
				__( 'Add custom script', 'grueneext' ),
				[ &$this, 'render_custom_code_option' ],
				GRUENEEXT_PLUGIN_PREFIX . '_donation_settings',
				GRUENEEXT_PLUGIN_PREFIX . '_donation_section',
				[
					'option_id' => 'custom_script',
					'helptext'  => "<p>" . __( 'Enter your js below, without the <script></script> tags.', 'grueneext' ) . "</p>",
				]
			);
			
			add_settings_field(
				GRUENEEXT_PLUGIN_PREFIX . '_custom_css',
				__( 'Add custom css', 'grueneext' ),
				[ &$this, 'render_custom_code_option' ],
				GRUENEEXT_PLUGIN_PREFIX . '_donation_settings',
				GRUENEEXT_PLUGIN_PREFIX . '_donation_section',
				[
					'option_id' => 'custom_css',
					'helptext'  => "<p>" . __( 'Enter your custom css below, without the <style></style> tags.', 'grueneext' ) . "</p>",
				]
			);
		}
		
		public function donation_options_section_header() {
			echo __( 'Use the options below to customize your donation form.', 'grueneext' );
		}
		
		public function render_custom_code_option( $args ) {
			$options_id = GRUENEEXT_PLUGIN_PREFIX . '_donation_options';
			$options    = get_option( $options_id );
			
			if ( isset( $options[ $args['option_id'] ] ) ) {
				$input = $options[ $args['option_id'] ];
			} else {
				$input = '';
			}
			
			echo $args['helptext'];
			echo "<textarea style='resize: both;' name='{$options_id}[{$args['option_id']}]'>$input</textarea>";
		}
		
		/**
		 * Add a menu
		 */
		public function add_menu() {
			add_options_page(
				__( 'Online donations', 'grueneext' ),
				__( 'Online donations', 'grueneext' ),
				'manage_options',
				GRUENEEXT_PLUGIN_PREFIX . '_donation_settings',
				[ &$this, 'display_plugin_optionspage' ]
			);
		}
		
		/**
		 * Menu Callback
		 */
		public function display_plugin_optionspage() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( __( 'You do not have sufficient permissions to access this page.', 'grueneext' ) );
			}
			
			// Render the settings template
			include GRUENEEXT_PLUGIN_PATH . '/admin/options.php';
		}
		
		/**
		 * I18n.
		 *
		 * Note: Put the translation in the languages folder in the plugins directory,
		 * name the translation files like "nameofplugin-lanugage_COUUNTRY.po". Ex: "grueneext-fr_FR.po"
		 */
		public function i18n() {
			$path = dirname( plugin_basename( __FILE__ ) ) . '/languages';
			load_plugin_textdomain( 'grueneext', false, $path );
		}
		
		/**
		 * Add roles on plugin activation
		 */
		public function add_roles_on_plugin_activation() {
			if ( is_multisite() ) {
				global $wpdb;
				$blogs_list = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
				if ( ! empty( $blogs_list ) ) {
					foreach ( $blogs_list as $blog ) {
						switch_to_blog( $blog['blog_id'] );
						$this->add_roles_for_sigle_blog();
						restore_current_blog();
					}
				}
			} else {
				$this->add_roles_for_sigle_blog();
			}
		}
		
		/**
		 * actually adds the roles
		 */
		private function add_roles_for_sigle_blog() {
			foreach ( $this->roles as $role ) {
				add_role( $role[0], $role[1], $role[2] );
			}
		}
		
		/**
		 * Remove roles on plugin deactivation
		 */
		public function remove_roles_on_plugin_deactivation() {
			if ( is_multisite() ) {
				global $wpdb;
				$blogs_list = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
				if ( ! empty( $blogs_list ) ) {
					foreach ( $blogs_list as $blog ) {
						switch_to_blog( $blog['blog_id'] );
						$this->remove_roles_for_sigle_blog();
						restore_current_blog();
					}
				}
			} else {
				$this->remove_roles_for_sigle_blog();
			}
		}
		
		/**
		 * actually removes the roles
		 */
		private function remove_roles_for_sigle_blog() {
			foreach ( $this->roles as $role ) {
				remove_role( $role[0] );
			}
		}
		
		/**
		 * Add capabilities on plugin activation
		 */
		public function add_capabilities_on_plugin_activation() {
			if ( is_multisite() ) {
				global $wpdb;
				$blogs_list = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
				if ( ! empty( $blogs_list ) ) {
					foreach ( $blogs_list as $blog ) {
						switch_to_blog( $blog['blog_id'] );
						$this->add_capabilities_for_single_blog();
						restore_current_blog();
					}
				}
			} else {
				$this->add_capabilities_for_single_blog();
			}
		}
		
		
		/**
		 * Actually add capabilities
		 */
		private function add_capabilities_for_single_blog() {
			$capabilities = array(
				GRUENEEXT_PLUGIN_PREFIX . '_frontend',
				GRUENEEXT_PLUGIN_PREFIX . '_admin',
			);
			$this->add_plugin_capabilities_for( GRUENEEXT_PLUGIN_PREFIX . '_user', $capabilities[0] );
			$this->add_plugin_capabilities_for( 'administrator', $capabilities );
		}
		
		
		/**
		 * Remove capabilities on plugin deactivation
		 */
		public function remove_capabilities_on_plugin_deactivation() {
			if ( is_multisite() ) {
				global $wpdb;
				$blogs_list = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
				if ( ! empty( $blogs_list ) ) {
					foreach ( $blogs_list as $blog ) {
						switch_to_blog( $blog['blog_id'] );
						$this->remove_capabilities_for_single_blog();
						restore_current_blog();
					}
				}
			} else {
				$this->remove_capabilities_for_single_blog();
			}
		}
		
		
		/**
		 * Actually remove capabilities
		 */
		private function remove_capabilities_for_single_blog() {
			$capabilities = array(
				GRUENEEXT_PLUGIN_PREFIX . 'frontend',
				GRUENEEXT_PLUGIN_PREFIX . 'admin',
			);
			$this->remove_plugin_capabilities_for( GRUENEEXT_PLUGIN_PREFIX . 'user', $capabilities[0] );
			$this->remove_plugin_capabilities_for( 'administrator', $capabilities );
			
		}
		
		/**
		 * Add capabilities
		 *
		 * @var string $role_name subject
		 * @var string|array $capabilities caps to add
		 */
		public function add_plugin_capabilities_for( $role_name, $capabilities ) {
			$role = get_role( $role_name );
			foreach ( (array) $capabilities as $capability ) {
				$role->add_cap( $capability );
			}
		}
		
		/**
		 * Remove capabilities
		 *
		 * @var string $role_name subject
		 * @var string|array $capabilities caps to remove
		 */
		public function remove_plugin_capabilities_for( $role_name, $capabilities ) {
			$role = get_role( $role_name );
			foreach ( (array) $capabilities as $capability ) {
				$role->remove_cap( $capability );
			}
		}
		
		/**
		 * Add tables on plugin activation if they dont exist yet
		 */
		public function create_tables_on_plugin_activation() {
			// dont forget to check if tables dont exist yet
			// dont forget to use $this->network_tables and $this->single_blog_tables (with $wpdb->prefix) as table names
		}
		
		/**
		 * Create options on plugin activation it they dont exist yet. Nothing will be overwritten.
		 */
		public function create_default_options_on_first_plugin_activation() {
			// single blog options
			if ( is_multisite() ) {
				global $wpdb;
				$blogs_list = $wpdb->get_results( "SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A );
				if ( ! empty( $blogs_list ) ) {
					foreach ( $blogs_list as $blog ) {
						switch_to_blog( $blog['blog_id'] );
						$this->add_options_for_sigle_blog();
						restore_current_blog();
					}
				}
			} else {
				$this->add_options_for_sigle_blog();
			}
			
			// options for all blogs (network options)
			$this->add_site_options();
		}
		
		/**
		 * Actually adds the options. If the option already exists it will simply be skiped.
		 * So nothing will be overwritten. This function will only add single blog options.
		 */
		private function add_options_for_sigle_blog() {
			foreach ( $this->single_blog_options as $option_name => $option_data ) {
				add_option( $option_name, $option_data );
			}
		}
		
		/**
		 * Actually adds the options. If the option already exists it will simply be skiped.
		 * So nothing will be overwritten. This function will only add network options.
		 */
		private function add_site_options() {
			foreach ( $this->network_options as $option_name => $option_data ) {
				add_site_option( $option_name, $option_data );
			}
		}
		
		/**
		 * hook in the shortcodes
		 */
		public function short_code_handler() {
			global $grueneext_frontend;
			add_shortcode( 'hide_n_show', array( $grueneext_frontend, 'hide_n_show' ) );
			add_shortcode( 'button', array( $grueneext_frontend, 'button' ) );
			add_shortcode( 'progressbar', array( $grueneext_frontend, 'progressbar' ) );
			add_shortcode( 'donation_form', array( $grueneext_frontend, 'donation_form' ) );
		}
		
		/**
		 * load ressources (js, css)
		 */
		public function load_resources() {
			
			foreach ( $this->styles as $style ) {
				if ( is_admin() && $style['scope'] == ( 'admin' || 'shared' ) ) {
					if ( ! wp_style_is( $style['handle'], 'enqueued' ) ) {
						if ( ! wp_style_is( $style['handle'], 'registered' ) ) {
							$this->register_style( $style );
						}
						wp_enqueue_style( $style['handle'] );
					}
				}
				if ( ! is_admin() && $style['scope'] == ( 'frontend' || 'shared' ) ) {
					if ( ! wp_style_is( $style['handle'], 'enqueued' ) ) {
						if ( ! wp_style_is( $style['handle'], 'registered' ) ) {
							$this->register_style( $style );
						}
						wp_enqueue_style( $style['handle'] );
					}
				}
			}
			
			foreach ( $this->scripts as $script ) {
				if ( is_admin() && $script['scope'] == ( 'admin' || 'shared' ) ) {
					if ( ! wp_script_is( $script['handle'], 'enqueued' ) ) {
						if ( ! wp_script_is( $script['handle'], 'registered' ) ) {
							$this->register_script( $script );
						}
						wp_enqueue_script( $script['handle'] );
					}
				}
				if ( ! is_admin() && $script['scope'] == ( 'frontend' || 'shared' ) ) {
					if ( ! wp_script_is( $script['handle'], 'enqueued' ) ) {
						if ( ! wp_script_is( $script['handle'], 'registered' ) ) {
							$this->register_script( $script );
						}
						wp_enqueue_script( $script['handle'] );
					}
				}
			}
		}
		
		/**
		 * register script
		 *
		 * @var array $script for params see __construct in Grueneext_Settings
		 */
		public function register_script( $script ) {
			wp_register_script(
				$script['handle'],
				plugins_url( $script['src'], __FILE__ ),
				$script['deps'],
				GRUENEEXT_VERSION,
				$script['in_footer']
			);
		}
		
		/**
		 * register style
		 *
		 * @var array $style for params see __construct in Grueneext_Settings
		 */
		public function register_style( $style ) {
			wp_register_style(
				$style['handle'],
				plugins_url( $style['src'], __FILE__ ),
				$style['deps'],
				GRUENEEXT_VERSION,
				$style['media']
			);
		}
		
	} // END class Grueneext_Main
} // END if ( ! class_exists( 'Grueneext_Main' ) )

if ( class_exists( 'Grueneext_Main' ) ) {
	
	if ( ! is_admin() ) {
		require_once( GRUENEEXT_PLUGIN_PATH . '/includes/class-grueneext-frontend.php' );
		$grueneext_frontend = new Grueneext_Frontend();
	} else {
		require_once( GRUENEEXT_PLUGIN_PATH . '/includes/class-grueneext-admin.php' );
		$grueneext_admin = New Grueneext_Admin();
	}
	
	$grueneext_main = new Grueneext_Main();
	
}