<?php
/*
Plugin Name: Doakn Customizer
Plugin URI: https://wordpress.org/plugins/dokan-lite/
Description: A simple plugin to customize dokan plugin
Version: 0.1
Author: weDevs
Author URI: https://wedevs.com/
Developer: Mohammed Saimon
Text Domain: dokan-lite
Domain Path: /languages/
License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) exit;

class Dokan_Menu_Customizer {
	private static $instance = null;

	public function __construct() {
		$this->init_hooks();
		$this->define_constants();
	}

	public function define_constants() {
		if ( ! defined( 'DOKAN_CUSTOMIZER_DIR' ) ) {
			define( 'DOKAN_CUSTOMIZER_DIR', plugin_dir_path( __FILE__ ) );
		}
		if ( ! defined( 'DOKAN_CUSTOMIZER_ASSETS' ) ) {
			define( 'DOKAN_CUSTOMIZER_ASSETS', plugins_url( 'assets', __FILE__ ) );
		}
	}

	public function dokan_menu_cusotmize_register( $wp_customizer ) {
		require_once DOKAN_CUSTOMIZER_DIR . 'classes/class-menu-drop.php';

		$wp_customizer->add_section( 'menu_option', array( 
			'title'			=> __( 'Dokan Menu Customizer', 'dokan-lite' ),
			'priority'		=> 35,
			'description'	=> __( 'Dokan Menu Customizer', 'dokan-lite' ),
			'capability'  => 'edit_theme_options',
		) );

		$wp_customizer->add_setting( 'menu_settings', array(
			'default' 		=> 'test',
			'type'			=> 'theme_mod',
			'capability'	=> 'edit_theme_options',
			'transport'		=> 'refresh',
		) );

		// $wp_customizer->add_control( 'menu_control', array(
		// 	'label' 		=> __( 'Menu Control', 'dokan-lite' ),
		// 	'settings'		=> 'menu_settings',
		// 	'priority' 		=> 10,
		// 	'section'		=> 'menu_option',
		// 	'type' 			=> 'text',
		// ) );
		$wp_customizer->add_control( new Menu_Dropdown_Custom_control( $wp_customizer, 'menu_control', array(
			'label' 		=> __( 'Menu Control', 'dokan-lite' ),
			'settings'		=> 'menu_settings',
			'priority' 		=> 10,
			'section'		=> 'menu_option',
		) ) );

		// if ( $wp_customizer->is_preview() && ! is_admin() ) {
		// 	add_action( 'wp_footer', array( $this, 'dokan_customizer_preview' ), 12 );
		// }
	}

	public function init_hooks() {
		// register global hooks
		// add_action( 'customize_preview_init', array( $this, 'dokan_customizer_scripts' ) );
		add_action( 'customize_register', array( $this, 'dokan_menu_cusotmize_register' ) );
		// register backend specefic hooks
		if ( is_admin() ) {
			//
		} else {
			// register frontend specefic hooks	
			add_filter( 'dokan_get_dashboard_nav', array( $this, 'dokan_get_dashboard_menus' ), 100 ); 
		}
	}

	public static function dokan_get_dashboard_menus( $urls ) {
		$menus_to_save = array();

		$urls['dashboard'] = array(
			'title' 	=> 'New Dashboard',
			'icon' 		=> '',
			'url'		=> '',
			'pos'		=> '10'
		);

		foreach ( $urls as $url ) {
			array_push( $menus_to_save, $url );
		}

		update_option( 'dokan_customized_menus', $menus_to_save );

		return $urls;
	}

	public function dokan_customizer_scripts() {
		wp_enqueue_script( 'dokan-menu-customizer', DOKAN_CUSTOMIZER_ASSETS . '/js/customizer.js' , array( 'jquery', 'customize-preview' ), false, true );
	}

	public function dokan_customizer_preview() {
	    ?>
	    <script type="text/javascript">
	    (function($){
	    	$('.dokan-menu-customizer').on('click', function() {
	    		console.log('I\' being changed');
	    	});
	    	console.log('I am loaded');
	    	$('.button-link.item-edit').on('click', function() {
	    		$('dokan-menu-customizer').addClass('menu-item-edit-active');
	    		console.log('I\' being changed');
	    	});
	    })(jQuery)
	    </script>
	    <?php 
	}

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

add_action( 'dokan_loaded', array( 'Dokan_Menu_Customizer', 'get_instance' ) );