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
 * Plugin Name:       Kachyng-WC
 * Plugin URI:        https://app.kachyng.com/docs
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Kachyng
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
require_once plugin_dir_path( __FILE__ ) . 'includes/html.php';

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
	//$OnePayRegister=new OnePayRegister;
	//$OnePayRegister->Register();
	require_once plugin_dir_path( __FILE__ ) . 'includes/html.php';
	MerchantRegister_html();
}

/* add menu page */


function wpdocs_register_one_pay_menu_page() {
    add_menu_page(
        __( 'Kachyng WC', 'Kachyng WC' ),
        'Kachyng WC',
        'manage_options',
        'one_pay_menu',
        'one_pay_menu',
        plugins_url( 'one-pay/images/icon.png' ),
        6
    );
}
add_action( 'admin_menu', 'wpdocs_register_one_pay_menu_page' ); 
add_action('admin_enqueue_scripts', 'load_onepay_scripts');
add_action('wp_enqueue_scripts', 'load_onepay_scripts');


function one_pay_menu(){
	require_once plugin_dir_path( __FILE__ ) . 'includes/html.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-function.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-one_pay_menu.php';
	
}

function load_onepay_scripts(){
	
	/* $id= get_current_user_id();
	$user =get_option('kach_user1');
	
	var_dump($user);var_dump($id);
	die(); */ 
	 wp_enqueue_script('one-pay-jquery.min.js', plugins_url( 'op-master/js/jquery.min.js' ));
	 wp_enqueue_script('one-pay', plugins_url( 'op-master/js/onepay.js' ));
	 $user_logged=get_current_user_id()?true:false;
	 
	 wp_localize_script( 'one-pay', 'ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'user_logged' => $user_logged ) );
}
 /* delete_option( 'kach_merchat_secretKey' );
delete_option( 'kach_merchat_authenticationKey' );
delete_option( 'kach_merchat_password' );
delete_option( 'kach_merchat_apiKey' );
delete_option( 'kach_merchat_email' );
delete_option( 'kach_merchat_name' );  */


add_action( 'wp_ajax_nopriv_onepay_merchant_reg', 'OnePayRegisterAjax' );
add_action( 'wp_ajax_onepay_merchant_reg', 'OnePayRegisterAjax' );
add_action( 'wp_ajax_nopriv_onepay_user_register', 'OnePayUserRegisterAjax' );
add_action( 'wp_ajax_onepay_user_register', 'OnePayUserRegisterAjax' );

function OnePayRegisterAjax(){
	$data['email']=$_POST['email'];
	$data['name']=$_POST['name'];
	
	$OnePayRegister=new OnePayRegister;
	$OnePRegister=$OnePayRegister->Register($data);
	echo json_encode($OnePRegister);
	wp_die();
	
}
function OnePayUserRegisterAjax(){
	$data['email']=$_POST['email'];
	$data['name']=$_POST['name'];
	
	$OnePayRegister=new OnePayRegister;
	$OnePRegister=$OnePayRegister->UserRegister($data);
	echo json_encode($OnePRegister);
	wp_die();
	
}


add_action( 'wp_ajax_nopriv_onepay_productsync', 'OnePayProductSyncAjax' );
add_action( 'wp_ajax_onepay_productsync', 'OnePayProductSyncAjax' );

function OnePayProductSyncAjax(){
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-function.php';
	$OnePayFunctions=new OnePayFunctions;
	$sync=$OnePayFunctions->StartSyncProductToOnePay();
	
	echo json_encode($sync);
	wp_die();
	
}

add_action('template_redirect','redirect_visitor');
function redirect_visitor(){
    $ck=is_checkout();
	if($ck){
		$Register=new OnePayRegister; 
		$res=$Register->UserAddToCart();
	}

}








//add_action('woocommerce_thankyou', 'user_register_op', 10, 1);
function user_register_op( $order_id ) {

    if ( ! $order_id )
        return;

    // Getting an instance of the order object
    $order = wc_get_order( $order_id );
	
	$Register=new OnePayRegister; 
	$res=$Register->UserRegisterOnOnePay($order);
	
    if($res->success==true){
		$res=$Register->UserAddProduct($order,$res);
		
	}
}
function InserFbButton() {
	//update_option('siteurl','http://naveen.store/');
	
	$id=get_current_user_id();
	if(!$id){
		InserFbButtonHtml();
		
	}
   
}
add_action( 'wp_footer', 'InserFbButton' );


add_filter('pre_option_default_role', function($default_role){
    // You can also add conditional tags here and return whatever
    return 'customer'; // This is changed
    return $default_role; // This allows default
});



