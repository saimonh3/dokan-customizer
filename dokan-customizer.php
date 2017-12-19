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
	}

	public function init_hooks() {
		// register backend specefic hooks
		if ( is_admin() ) {
			
		}
		// register frontend specefic hooks
		wp_die('frontend');
	}

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
}

add_action( 'dokan_loaded', array( 'Dokan_Menu_Customizer', 'get_instance' ) );