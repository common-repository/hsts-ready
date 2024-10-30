<?php



	/*

	Plugin Name: HSTS Ready

	Plugin URI: 

	Version: 1.04

	Description: Enable HSTS (HTTP Strict Transport Security) on your website

	Author: Manu225

	Author URI: 

	Network: false

	Text Domain: hsts-ready

	Domain Path: 

	*/



	register_activation_hook( __FILE__, 'hsts_ready_install' );

	register_uninstall_hook(__FILE__, 'hsts_ready_desinstall');



	function hsts_ready_install() {

		//option for settings

		add_option( 'hsts_ready_expire', 31536000 );

		add_option( 'hsts_ready_subdomains', true );

	}



	function hsts_ready_desinstall() {

		delete_option( 'hsts_ready_expire' );

		delete_option( 'hsts_ready_subdomains' );

	}



	function add_hsts_ready_header() {

		//check HTTPS enabled ?

		if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')

		{

			$expire = get_option('hsts_ready_expire');

			$subdomains = get_option('hsts_ready_subdomains');

	    	@header("strict-transport-security: max-age=".(int)$expire."; preload; ".($subdomains == true ? 'includeSubDomains' : ''));

		}

	}

	add_action( 'init', 'add_hsts_ready_header' );



	add_action( 'admin_menu', 'register_hsts_ready_menu' );

	function register_hsts_ready_menu() {



		add_submenu_page( 'options-general.php', 'HSTS Ready', 'HSTS Ready', 'edit_pages', 'hsts_ready_settings', 'hsts_ready_settings');



	}



	function hsts_ready_settings() {



		if(sizeof($_POST) > 0 && is_numeric($_POST['hsts_ready_expire']) && check_admin_referer( 'hsts_ready_settings' ))

		{

			$expire = sanitize_text_field($_POST['hsts_ready_expire']);

			$subdomains = ($_POST['hsts_ready_subdomains'] == 1 ? true : false);

			update_option('hsts_ready_expire', $expire);

			update_option('hsts_ready_subdomains', $subdomains);

		}

		else

		{

			$expire = get_option('hsts_ready_expire');

			$subdomains = get_option('hsts_ready_subdomains');

		}



		echo '<form action="" method="post">';

		wp_nonce_field( 'hsts_ready_settings' );

		echo '<h2>HSTS Ready settings</h2>

		<label for="hsts_ready_expire">HSTS expire time:</label> <input type="text" name="hsts_ready_expire" id="hsts_ready_expire" value="'.(int)$expire.'" />s<br />

		<label for="hsts_ready_subdomains">HSTS include subdomains?</label> <input type="checkbox" name="hsts_ready_subdomains" id="hsts_ready_subdomains" value="1" '.($subdomains == true ? 'checked="checked"' : '').' /><br />

		<input type="submit" value="Save settings" />

		</form>

		<h3>Like InfoD74 to discover my new plugins: <a href="https://www.facebook.com/infod74/" target="_blank"><img src="'.esc_url(plugins_url( 'images/fb.png', __FILE__)).'" alt="" /></a></h3>';



	}



?>