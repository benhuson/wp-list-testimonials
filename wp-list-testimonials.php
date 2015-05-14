<?php

/*
Plugin Name: WP List Testimonials
Plugin URI: http://www.benhuson.co.uk/wordpress-plugins/wp-list-testimonials/
Description: Manage and display testimonials on your site.
Version: 2.0.dev
Author: Ben Huson
Author URI: https://github.com/benhuson/wp-list-testimonials
License: GPL
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

http://www.gnu.org/licenses/gpl.html
*/

add_action( 'plugins_loaded', array( 'WPL_Testimonals', 'load' ), 5 );

class WPL_Testimonals {

	// Paths
	public static $SUBDIR = null;
	public static $URL = null;
	public static $DIR = null;

	// Vars
	private static $POST_TYPE = 'wpl_testimonial';
	private static $GROUP_TAX = 'wpl_testimonial_group';

	/**
	 * Load
	 */
	function load() {

		// Define Paths
		WPL_Testimonals::$SUBDIR = '/' . str_replace( basename( __FILE__ ), '', plugin_basename( __FILE__ ) );
		WPL_Testimonals::$URL = plugins_url( WPL_Testimonals::$SUBDIR );
		WPL_Testimonals::$DIR = plugin_dir_path( __FILE__ );

		// Deprecated Blogroll Functionality
		require_once( WPL_Testimonals::$DIR . 'includes/deprecated.php' );

		// Global Actions
		add_action( 'init', array( 'WPL_Testimonals', 'setup' ) );
		add_filter( 'gettext', array( 'WPL_Testimonals', 'gettext' ), 5, 3 );

		// Admin / AJAX / Front end only
		if ( is_admin() ) {
			if ( defined('DOING_AJAX') && DOING_AJAX ) {
				// AJAX only
			} else {
				require_once( WPL_Testimonals::$DIR . 'admin/admin.php' );
				WPL_Testimonals_Admin::load();
			}
		} else {
			// Front end only
		}
	}

	/**
	 * Post Type
	 *
	 * @return  string  Post type.
	 */
	public static function post_type() {

		return self::$POST_TYPE;

	}

	/**
	 * Group Taxonomy
	 *
	 * @return  string  Group taxonomy.
	 */
	public static function group_tax() {

		return self::$GROUP_TAX;

	}

	/**
	 * Text String Filter
	 */
	function gettext( $translated_text, $text, $domain ) {
		if ( is_admin() ) {
			$screen = '';
			if ( function_exists( 'get_current_screen' ) )
				$screen = get_current_screen();
			if ( ! is_object( $screen ) )
				return $translated_text;

			if ( $screen->id == self::post_type() ) {
				switch ( $translated_text ) {
					case 'Enter title here' :
						$translated_text = __( 'Enter name here', 'wpl_testimonials' );
						break;
				}
			}
		}
		return $translated_text;
	}

	/**
	 * Setup
	 */
	function setup() {
		WPL_Testimonals::register_testimonal_post_type();
		WPL_Testimonals::register_testimonal_group_taxonomy();
	}

	/**
	 * Register Testimonal Post Type
	 */
	function register_testimonal_post_type() {
		$labels = array(
			'name'               => __( 'Testimonals', 'wpl_testimonials' ),
			'singular_name'      => __( 'Testimonal', 'wpl_testimonials' ),
			'menu_name'          => __( 'Testimonals', 'wpl_testimonials' ),
			'add_new'            => __( 'Add New', 'wpl_testimonials' ),
			'add_new_item'       => __( 'Add New Testimonal', 'wpl_testimonials' ),
			'edit_item'          => __( 'Edit Testimonal', 'wpl_testimonials' ),
			'new_item'           => __( 'New Testimonal', 'wpl_testimonials' ),
			'all_items'          => __( 'All Testimonals', 'wpl_testimonials' ),
			'view_item'          => __( 'View Testimonal', 'wpl_testimonials' ),
			'search_items'       => __( 'Search Testimonals', 'wpl_testimonials' ),
			'not_found'          => __( 'No testimonals found', 'wpl_testimonials' ),
			'not_found_in_trash' => __( 'No testimonals found in Trash', 'wpl_testimonials' ),
			'parent_item_colon'  => ''
		);
		$args = array(
			'labels'              => $labels,
			'description'         => __( 'Testimonials.', 'wpl_testimonials' ),
			'public'              => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_nav_menus'   => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => null,
			'menu_icon'           => null, // @todo
			'hierarchical'        => false,
			'supports'            => array( 'title', 'editor', 'thumbnail', 'revisions' ),
			'has_archive'         => _x( 'testimonials', 'slug', 'wpl_testimonials' ),
			'rewrite'             => array(
				'slug'       => _x( 'testimonials', 'slug', 'wpl_testimonials' ),
				'with_front' => false,
				'feeds'      => true
			),
			'query_var'           => true
		); 
		register_post_type( self::post_type(), apply_filters( 'wpl_testimonals_post_type_args', $args ) );
	}

	/**
	 * Register Testimonal Group Taxonomy
	 */
	function register_testimonal_group_taxonomy() {
		$labels = array(
			'name'              => _x( 'Groups', 'taxonomy general name' ),
			'singular_name'     => _x( 'Group', 'taxonomy singular name' ),
			'menu_name'         => __( 'Groups' ),
			'all_items'         => __( 'All Groups' ),
			'edit_item'         => __( 'Edit Testimonial Group' ),
			'view_item'         => __( 'View Testimonial Group' ),
			'update_item'       => __( 'Update Testimonial Group' ),
			'new_item_name'     => __( 'New Testimonial Group Name' ),
			'search_items'      => __( 'Search Testimonial Groups' ),
			'add_new_item'      => __( 'Add New Testimonial Group' ),
			'not_found'         => __( 'No testimonial groups found.' ),
		);
		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'public'            => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'            => array(
				'slug'       => _x( 'testimonials/group', 'slug', 'wpl_testimonials' ),
				'with_front' => false
			),
		);
		register_taxonomy( self::group_tax(), array( self::post_type() ), apply_filters( 'wpl_testimonals_taxonomy_group_args', $args ) );
	}

}
