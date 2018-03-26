<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/**
 * Our Main WooWidgets SaaS Plugin
 *
 * @link              http://example.com
 * @since             1.0.0
 * @package           One_Pay
 *
 * @wordpress-plugin
 * Plugin Name:       OnePay
 * Plugin URI:        http://OnePay.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Lakhvinder
 * Author URI:        http://example.com/
 * License:           Strictly Private and Confidential
 * Text Domain:       one-pay
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
DEFINE('DS', DIRECTORY_SEPARATOR); 

define( 'OP_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'OP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'OP_API_URL', 'https://app.kachyng.com/api/v2/' );


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-one-pay-activator.php
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-one-pay-activator.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-register.php';

require_once plugin_dir_path( __FILE__ ) . 'curl/vendor/autoload.php';

function activate_plugin_name() {
	
	One_Pay_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-one-pay-deactivator.php
 */
function deactivate_plugin_name() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-one-pay-deactivator.php';
	One_Pay_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plugin_name' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_name' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-one-pay.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */


/* $d=glob(plugin_dir_path( __FILE__ )."/public/*.php");

foreach ($d as $filename)
{
	require_once( $filename);
   echo include $filename;
}
 */
add_shortcode('kachyng','kachyng');
 function kachyng(){
	$OnePayRegister=new OnePayRegister;
	$OnePayRegister->Register();
} 