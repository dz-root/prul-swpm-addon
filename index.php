<?php  

/**
 * Plugin Name:       Prul addon for Simple Membership
 * Plugin URI:        https:/yagami.xyz/
 * Description:       Simple Membership Password reset unique link is the addon solution to reset the SM user pasword by sending link with unique token to update the password in case of the default solution (generate random password and send it by email) used by Simple Membership
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Yagami.xyz
 * Author URI:        https://yagami.xyz/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wpsm-prul-addon
 */

error_reporting("E_all");
define('PRUL_VER', '1.0.0');
define('PRUL_PATH', dirname(__FILE__) . '/');
define('INTER_PATH' , '/wp-content/plugins/prul-swpm-addon');

include_once('core/classes/install.php');
include_once('core/admin/wpInitPrul.php');
include_once('core/classes/shortcode.php');

wp_register_style('prul-admin-style', INTER_PATH .'/dist/css/prul-admin.css');
wp_enqueue_style('prul-admin-style');

function prul_script_enqueuer() {

    wp_register_script( "prul_ajax", INTER_PATH .'/dist/js/prul.js', array('jquery'));

    wp_localize_script( 'prul_ajax', 'Ajax_call', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));

    wp_enqueue_script( 'prul_ajax' );

}

function bkash_settings_link( $links ) {
    $settings_link = '<a href="options-general.php?page=prul_admin_settings">' . __( 'Settings' ) . '</a>';
	
	
    array_push( $links, $settings_link );
  	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'bkash_settings_link' );

add_action( 'init', 'prul_script_enqueuer' );
add_action( 'init', 'continuTosettings', 10, 1 );
add_action( 'init', 'auto_config', 10, 1 );
add_action('init', 'reset_config');
add_action( 'init', 'updateSettings' );


register_deactivation_hook( __FILE__, 'prul_uninstall' );


$lostPassword_shortcode = new Shortcode();