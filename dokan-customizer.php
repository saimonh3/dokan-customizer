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
	private $default_menus = array();
	private $final_menus;

	public function __construct() {
		$this->init_hooks();
		$this->define_constants();
		$this->all_menus = get_option( 'dokan_customized_menus' );
		$this->new_menus = $this->set_all_new_menus();
		$this->set_default_menus();
		$this->creating_final_menu_array();
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
			$wp_customizer->add_setting( "menu_settings_icon[$key]", array(
				'default' 		=> $menu['title'],
				'type'			=> 'theme_mod',
				'capability'	=> 'edit_theme_options',
				'transport'		=> 'refresh',
			) );
			$wp_customizer->add_setting( "menu_settings_pos[$key]", array(
				'default' 		=> $menu['title'],
				'type'			=> 'theme_mod',
				'capability'	=> 'edit_theme_options',
				'transport'		=> 'refresh',
			) );
			$wp_customizer->add_control( new Menu_Dropdown_Custom_control( $wp_customizer, "menu_settings[$key]", array(
				'label' 		=> __( 'Menu Control', 'dokan-lite' ),
				// 'settings'		=> "menu_settings[$key]",
				'settings'		=> array(
					"menu_settings[$key]",
					"menu_settings_icon[$key]",
					"menu_settings_pos[$key]",
				),
				'priority' 		=> $key,
				'section'		=> 'menu_option',
			) ) );
		}
	}

	public function init_hooks() {
		// register global hooks
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'dokan_customizer_scripts' ) );
		add_action( 'customize_register', array( $this, 'dokan_menu_cusotmize_register' ) );
		// add_action( 'customize_save_after', array( $this, 'dokan_save_customizer_settings' ) );
		add_action( 'init', array( $this, 'dokan_save_default_theme_mods' ) );
		// add_filter( 'dokan_get_dashboard_nav', array( $this, 'dokan_set_dashboard_menus' ) );
		// add_action( 'wp_footer', array( $this, 'dokan_customizer_preview' ), 12 );
		// register backend specefic hooks
		if ( is_admin() ) {
			add_action( 'switch_theme', array( $this, 'dokan_customizer_menus_reset' ) );
		} else {
			// register frontend specefic hooks	
			add_filter( 'dokan_get_dashboard_nav', array( $this, 'dokan_get_dashboard_menus' ), 100 );  
			add_action('init', array($this, 'test'));
		}
	}
	public function test() {
		// var_dump( array_combine(['dashboard', 'products'], $this->new_menus) );
		// var_dump( $this->default_menus );
		// var_dump( $this->final_menus );
		// var_dump($this->new_menus );
		
		// var_dump( $this->all_menus );
		// var_dump( get_option( 'dokan_customized_menus' ) );
	}

	// public function dokan_sort_menu( $menus ) {
	// 	return strtoupper($menus);
	// }

    public function dokan_save_default_theme_mods() {
    	// return early if default menu is already there
    	if ( get_option( 'dokan_customizer_menus_isset' ) == 'yes' ) {
    		return;
    	}

    	// set default dokan menus in the customizer so that while setting random menu
    	// first doesn't change it's index
    	$menus = array(
			'dashboard'	=> 'Dashboard',
			'products'	=> 'Products',
			'orders'	=> 'Orders',
			'withdraw' 	=> 'Withdraw',
			'settings' 	=> 'Settings',
			'coupons' 	=> 'Coupons',
			'reviews' 	=> 'Reviews',
			'reports' 	=> 'Reports',
			'booking' 	=> 'Booking' 
    	);
    	$menus_icons = array(
			'dashboard' => 'fa-tachometer',
			'products' 	=> 'fa-briefcase',
			'orders' 	=> 'fa-shopping-cart',
			'withdraw' 	=> 'fa-upload',
			'settings' 	=> 'fa-cog',
			'coupons' 	=> 'fa-gift',
			'reviews' 	=> 'fa-comments-o',
			'reports' 	=> 'fa-line-chart',
			'booking' 	=> 'fa-calendar' 
    	);
    	$menus_pos = array(
			'dashboard' => '1',
			'products' 	=> '2',
			'orders' 	=> '3',
			'withdraw' 	=> '7',
			'settings' 	=> '20',
			'coupons' 	=> '4',
			'reviews' 	=> '6',
			'reports' 	=> '5',
			'booking' 	=> '8' 
    	);
    	set_theme_mod( 'menu_settings', $menus );
    	set_theme_mod( 'menu_settings_icon', $menus_icons );
    	set_theme_mod( 'menu_settings_pos', $menus_pos );
    	update_option( 'dokan_customizer_menus_isset', 'yes' );
    }

    // set only default menus name
	public function set_default_menus() {
		$menus = $this->all_menus;

		foreach ( $menus[0] as $key => $value ) {
			array_push($this->default_menus, $key );			
		}
	}

	// set all the new menus and sort it
	public function set_all_new_menus() {
		$menus = get_theme_mod( 'menu_settings' );
		$menus_icon = get_theme_mod( 'menu_settings_icon' );
		$menus_pos = get_theme_mod( 'menu_settings_pos' );

		if ( empty( $menus ) || empty( $menus_icon ) || empty( $menus_pos ) ) {
			return;
		}

		$sorted_menus = array_map( null, $menus, $menus_icon, $menus_pos );

		return $sorted_menus;
	}

	// setting up the final menus to match with the array structure of dokan_get_dashboard_nav filter
	public function creating_final_menu_array() {
		if ( empty( $this->new_menus ) ) {
			return;
		}

		$final_menus = array();

		foreach ( $this->default_menus as $key => $value ) {
			$final_menus[$value] = $this->new_menus[$key];
		}

		$this->final_menus = $final_menus;
	}

	// reset the dokan_customizer_menus_isset flag to no
	public function dokan_customizer_menus_reset() {
		update_option( 'dokan_customizer_menus_isset', 'no' );
	}

	// rename, reposition or change icon and show in the dashboard
	public static function dokan_get_dashboard_menus( $urls ) {
		$menus_to_save = array();

		if ( is_array( $this->final_menus ) && ! empty( $this->final_menus ) ) {
			foreach ( $urls as $key => $value ) {
				foreach ( $this->final_menus as $new_key => $new_value ) {
					if ( strtolower( $value['title'] ) == $new_key ) {
						$urls[$key] = array(
							'title' => ! empty( $new_value[0] ) ? $new_value[0] : $value['title'],
							'icon' 	=> ! empty( $new_value[1] ) ? '<i class="fa ' . $new_value[1] . '"></i>' : $value['icon'],
							'url' 	=> $value['url'],
							'pos' 	=> ! empty( $new_value[2] ) ? $new_value[2] : $value['pos'],
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

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

add_action( 'dokan_loaded', array( 'Dokan_Menu_Customizer', 'get_instance' ) );