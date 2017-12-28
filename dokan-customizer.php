<?php
/*
Plugin Name: Doakn Customizer
Plugin URI: https://wordpress.org/plugins/dokan-lite/
Description: A simple plugin to customize dokan dashboard menus
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
	private static $instance;
	private $all_menus;
	private $new_menus;
	private $default_menus;
	private $final_menus;

	public function __construct() {
		$this->init_hooks();
		$this->define_constants();
		$this->set_all_menus();
		$this->set_all_new_menus();
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

	// set all menus to $all_menus property
	public function set_all_menus() {
		$this->all_menus = get_option( 'dokan_customizer_menus' );
	}

	public function dokan_menu_cusotmize_register( $wp_customizer ) {
		require_once DOKAN_CUSTOMIZER_DIR . 'classes/class-menu-drop.php';

		$wp_customizer->add_section( 'menu_option', array( 
			'title'			=> __( 'Dokan Menu Customizer', 'dokan-lite' ),
			'priority'		=> 35,
			'description'	=> __( 'Dokan Menu Customizer', 'dokan-lite' ),
			'capability'  => 'edit_theme_options',
		) );

		foreach ( $this->all_menus as $key => $menu ) {
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
		add_action( 'init', array( $this, 'dokan_save_default_theme_mods' ), 10 );
		// register backend specefic hooks
		if ( is_admin() ) {
			add_action( 'switch_theme', array( $this, 'dokan_customizer_menus_reset' ) );
		} else {
			// register frontend specefic hooks	
			add_filter( 'dokan_get_dashboard_nav', array( $this, 'dokan_get_dashboard_menus' ), 200 );  
			add_action('init', array($this, 'test'));
		}
	}
	public function test() {
		// var_dump( array_combine(['dashboard', 'products'], $this->new_menus) );
		// var_dump( $this->default_menus );
		// var_dump( $this->final_menus );
		// var_dump($this->new_menus );
		
		// var_dump( $this->all_menus );
		// var_dump( get_option( 'dokan_customizer_menus' ) );
	}

	// get all the default menus to set in the customizer
	// public function dokan_save_customized_menus( $urls ) {
	// 	update_option( 'dokan_customizer_menus', $urls );

	// 	return $urls;
	// }

    public function dokan_save_default_theme_mods() {
    	//return early if default menu is already there
    	$is_set = get_option( 'dokan_customizer_menus_isset' );
    	if ( $is_set == 'yes' ) return;
    	
    	$menus = array();
    	$menus_icons = array();
    	$menus_pos = array();
    	
    	foreach ( $this->all_menus as $key => $value ) {
    		$menus[$key] = $value['title'];
    		$menus_icons[$key] = $value['icon'];
    		$menus_pos[$key] = $value['pos'];
    	}

    	$menus_icons = array_map( array( $this, 'filter_menus_icons' ), $menus_icons );

    	set_theme_mod( 'menu_settings', $menus );
    	set_theme_mod( 'menu_settings_icon', $menus_icons );
    	set_theme_mod( 'menu_settings_pos', $menus_pos );
    	update_option( 'dokan_customizer_menus_isset', 'yes' );
    }

    public function filter_menus_icons( $key ) {
    	$filtered_icon = array();
    	$pattern	= '/(fa-[a-z]+-?[a-z]+)/';
    	preg_match_all( $pattern, $key, $filtered_icon );

    	return $filtered_icon[0][0];
    }

    // set only default menus name
	public function set_default_menus() {
		$menus = $this->all_menus;
		$this->default_menus = array();

		foreach ( $menus as $key => $value ) {
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

		$this->new_menus = $sorted_menus;
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
	public function dokan_get_dashboard_menus( $urls ) {
		// save original dashbord menus to get all the original menus
		// we are checking so that update_options only runs once
		if ( is_array( $this->final_menus )  && empty( $this->final_menus ) ) {
			update_option( 'dokan_customizer_menus', $urls );
		}

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

		return $urls;
	}

	public function dokan_customizer_scripts() {
		wp_enqueue_script( 'dokan-menu-customizer', DOKAN_CUSTOMIZER_ASSETS . '/js/customizer.js' , array( 'jquery', 'customize-preview' ), false, true );
	}

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new Self();
		}

		return self::$instance;
	}
}

add_action( 'dokan_loaded', array( 'Dokan_Menu_Customizer', 'get_instance' ) );