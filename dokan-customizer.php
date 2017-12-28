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

		// if ( $wp_customizer->is_preview() && ! is_admin() ) {
		// 	add_action( 'wp_footer', array( $this, 'dokan_customizer_preview' ), 12 );
		// }
	}

	public function init_hooks() {
		// register global hooks
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'dokan_customizer_scripts' ) );
		add_action( 'customize_register', array( $this, 'dokan_menu_cusotmize_register' ) );
		add_action( 'customize_save_after', array( $this, 'dokan_save_customizer_settings' ) );
		// add_action( 'init', array( $this, 'dokan_save_default_theme_mods' ) );
		// add_filter( 'dokan_get_dashboard_nav', array( $this, 'dokan_set_dashboard_menus' ) );
		// add_action( 'wp_footer', array( $this, 'dokan_customizer_preview' ), 12 );
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
		// var_dump( array_combine(['dashboard', 'products'], $this->new_menus) );
		// var_dump( $this->default_menus );
		var_dump( $this->final_menus );
		// var_dump($this->new_menus );
		
		// var_dump( $this->all_menus );
		// var_dump( get_option( 'dokan_customized_menus' ) );
	}

	// public function dokan_sort_menu( $menus ) {
	// 	return strtoupper($menus);
	// }

    public function dokan_save_default_theme_mods() {
    	// set_theme_mod("menu_settings", array('dashboard' => 'dashboard new eita'));
    	// set_theme_mod("menu_settings_icon", array('dashboard' => 'dashboard new icon'));
    	// set_theme_mod("menu_settings_pos", array('dashboard' => '20'));
    	// foreach ( $this->all_menus[0] as $key => $value ) {
    	// 	var_dump($key);
	    // 	set_theme_mod( "menu_settings", array( $key => $value['title'] ) );
	    // 	// set_theme_mod( "menu_settings_icon", array( $key => $value['icon'] ) );
	    // 	// set_theme_mod( "menu_settings_pos", array( $key => $value['pos'] ) );
    	// }
    	set_theme_mod("menu_settings", array( 'dashboard', 'dashboard new eita', 'products', 'products new eita' ) );
    	// set_theme_mod("menu_settings_icon", array('dashboard' => 'dash icon'));
    	// set_theme_mod("menu_settings_pos", array('dashboard' => '12'));

    	// set_theme_mod("menu_settings", array('products' => 'product new eita'));
    	// set_theme_mod("menu_settings_icon", array('products' => 'product icon'));
    	// set_theme_mod("menu_settings_pos", array('products' => '13'));

    	// set_theme_mod("menu_settings", array('orders' => 'orders new eita'));
    	// set_theme_mod("menu_settings_icon", array('orders' => 'orders icon'));
    	// set_theme_mod("menu_settings_pos", array('orders' => '14'));

    	// set_theme_mod("menu_settings", array('withdraw' => 'withdraw new eita'));
    	// set_theme_mod("menu_settings_icon", array('withdraw' => 'withdraw icon'));
    	// set_theme_mod("menu_settings_pos", array('withdraw' => '15'));
    	
    	// set_theme_mod("menu_settings", array('settings' => 'settings new eita'));
    	// set_theme_mod("menu_settings_icon", array('settings' => 'settings icon'));
    	// set_theme_mod("menu_settings_pos", array('settings' => '15'));    	
    	
    	// set_theme_mod("menu_settings", array('coupons' => 'coupons new eita'));
    	// set_theme_mod("menu_settings_icon", array('coupons' => 'coupons icon'));
    	// set_theme_mod("menu_settings_pos", array('coupons' => '16'));    	

    	// set_theme_mod("menu_settings", array('reviews' => 'reviews new eita'));
    	// set_theme_mod("menu_settings_icon", array('reviews' => 'reviews icon'));
    	// set_theme_mod("menu_settings_pos", array('reviews' => '17'));

    	// set_theme_mod("menu_settings", array('reports' => 'reports new eita'));
    	// set_theme_mod("menu_settings_icon", array('reports' => 'reports icon'));
    	// set_theme_mod("menu_settings_pos", array('reports' => '18'));

    	// set_theme_mod("menu_settings", array('booking' => 'booking new eita'));
    	// set_theme_mod("menu_settings_icon", array('booking' => 'booking icon'));
    	// set_theme_mod("menu_settings_pos", array('booking' => '18'));
    }

	public function set_default_menus() {
		$menus = $this->all_menus;

		foreach ( $menus[0] as $key => $value ) {
			array_push($this->default_menus, $key );			
		}
	}

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

	// public function dokan_save_customizer_settings() {
		
	// }

	// public function dokan_set_dashboard_menus( $menus ) {
	// 	$all_menus = array();

	// 	foreach ( $menus as $key => $value ) {
	// 		array_push( $this->default_menus, $key );
	// 	}

	// 	return $menus;
	// }


	public static function dokan_get_dashboard_menus( $urls ) {
		$menus_to_save = array();

		if ( is_array( $this->final_menus ) && ! empty( $this->final_menus ) ) {
			foreach ( $urls as $key => $value ) {
				foreach ( $this->final_menus as $new_key => $new_value ) {
					if ( strtolower( $value['title'] ) == $new_key ) {
						$urls[$key] = array(
							'title' => $new_value[0],
							'icon' 	=> $new_value[1],
							'url' 	=> $value['url'],
							'pos' 	=> $new_value[2],
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
		// jQuery(document).ready(function() {
		// 	setTimeout(function() {
		//    		console.log('loeded');
		//    	  	$('.menu-item-bar').on('click', function(e) {
	 //    			var self = $(this);
	 //    			console.log(self);
	 //    		});
		// 	}, 4000);
		// });
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