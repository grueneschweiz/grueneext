<?php

/**
 * lock out script kiddies: die an direct call
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


if ( ! class_exists( 'Grueneext_Admin' ) ) {
	
	/**
	 * This class contains all the extra stuff of the backend
	 */
	class Grueneext_Admin {
		
		/**
		 * Add a media button to the post & page edit pages to insert shortcode easly
		 *
		 * @see                            http://de.wpseek.com/function/media_buttons/
		 */
		public function add_media_button() {
			global $typenow;
			
			// verify the post type
			if ( ! in_array( $typenow, array( 'post', 'page' ) ) ) {
				return; // BREAKPOINT
			}
			
			// make sure the thickbox script is loaded
			add_thickbox();
			
			// add media button
			echo '<a href="#TB_inline?&inlineId=grueneext-short-code-generator" class="thickbox button" ' .
			     'title="' . esc_attr__( "Insert Theme Shortcode", "grueneext" ) . '">' .
			     '<span class="wp-media-buttons-icon dashicons dashicons-plus"></span> ' .
			     __( "Add Special Function", "gruene" ) . '</a>';
		}
		
		/**
		 * print out the shortcode generators html
		 */
		function add_short_code_generator_html() {
			global $typenow;
			
			// verify the post type
			if ( ! in_array( $typenow, array( 'post', 'page' ) ) ) {
				return; // BREAKPOINT
			}
			
			// include thickbox content
			include GRUENEEXT_PLUGIN_PATH . '/admin/short-code-generator.php';
		}
	}
}
	