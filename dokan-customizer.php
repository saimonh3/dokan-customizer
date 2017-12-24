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
	private $all_menus = array();
	private $new_menus = array();

	public function __construct() {
		$this->init_hooks();
		$this->define_constants();
		$this->all_menus = get_option( 'dokan_customized_menus' );
		$this->new_menus = get_theme_mod( 'menu_settings' );
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

		foreach ( $this->all_menus[0] as $key => $menu ) {
			$wp_customizer->add_setting( "menu_settings[$key]", array(
				'default' 		=> $menu['title'],
				'type'			=> 'theme_mod',
				'capability'	=> 'edit_theme_options',
				'transport'		=> 'refresh',
			) );
			$wp_customizer->add_control( new Menu_Dropdown_Custom_control( $wp_customizer, "menu_settings[$key]", array(
				'label' 		=> __( 'Menu Control', 'dokan-lite' ),
				'settings'		=> "menu_settings[$key]",
				'priority' 		=> $key,
				'section'		=> 'menu_option',
			) ) );
		}

		// if ( $wp_customizer->is_preview() && ! is_admin() ) {
		// 	add_action( 'wp_footer', array( $this, 'dokan_customizer_preview' ), 12 );
		// }
	}

	public function init_hooks() {
		// register global hooks
		// add_action( 'customize_preview_init', array( $this, 'dokan_customizer_scripts' ) );
		add_action( 'customize_register', array( $this, 'dokan_menu_cusotmize_register' ) );
		add_action( 'customize_save_after', array( $this, 'dokan_save_customizer_settings' ) );
		add_action( 'wp_footer', array( $this, 'dokan_customizer_preview' ), 12 );
		// register backend specefic hooks
		if ( is_admin() ) {
			//
		} else {
			// register frontend specefic hooks	
			add_filter( 'dokan_get_dashboard_nav', array( $this, 'dokan_get_dashboard_menus' ), 100 ); 
			add_action('init', array($this, 'test'));
		}
	}
	public function test() {
		// var_dump( $this->new_menus );
		// var_dump( $this->all_menus );
		// var_dump( get_option( 'dokan_customized_menus' ) );
	}

	public function dokan_save_customizer_settings() {
		// update_option( 'dokan_customized_menus',  );
	}

	public static function dokan_get_dashboard_menus( $urls ) {
		$menus_to_save = array();

		if ( ! empty( $this->new_menus ) ) {
			foreach ( $urls as $key => $value ) {
				foreach ( $this->new_menus as $new_key => $new_value ) {
					if ( strtolower( $value['title'] ) == $new_key ) {
						$urls[$key] = array(
							'title' => strtolower( $value['title'] ) == $new_key ? $new_value : $value['title'],
							'icon' 	=> $value['icon'],
							'url'  	=> $value['url'],
							'pos' 	=> $value['pos'],
						);						
					}
				}
			}
		}

		array_push( $menus_to_save, $urls );

		update_option( 'dokan_customized_menus', $menus_to_save );

		return $urls;
	}

	public function dokan_customizer_scripts() {
		wp_enqueue_script( 'dokan-menu-customizer', DOKAN_CUSTOMIZER_ASSETS . '/js/customizer.js' , array( 'jquery', 'customize-preview' ), false, true );
	}

	public function dokan_customizer_preview() {
	    ?>
	    <script type="text/javascript">
		jQuery(document).ready(function() {
			setTimeout(function() {
		   		console.log('loeded');
		   	  	$('.menu-item-bar').on('click', function(e) {
	    			var self = $(this);
	    			console.log(self);
	    		});
			}, 4000);
		});
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