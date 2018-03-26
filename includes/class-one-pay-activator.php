<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    One_Pay
 * @subpackage One_Pay/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    One_Pay
 * @subpackage One_Pay/includes
 * @author     Your Name <email@example.com>
 */
class One_Pay_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public  function activate() {
		
		/* check woocommerce activated */
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
			{
				//'WooCommerce is active.';
				global $wpdb;
				
			 $hasKey = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}woocommerce_api_keys WHERE description='Kachyng'", OBJECT );

			 
			if(!$hasKey){

					try {
						$user_id = get_current_user_id();
						$description = "Kachyng";
						$permissions = "read_write";
						$consumer_key    = 'ck_' . wc_rand_hash();
						$consumer_secret = 'cs_' . wc_rand_hash();

						$data = array(
							'user_id'         => $user_id,
							'description'     => $description,
							'permissions'     => $permissions,
							'consumer_key'    => wc_api_hash( $consumer_key ),
							'consumer_secret' => $consumer_secret,
							'truncated_key'   => substr( $consumer_key, -7 ),
						);

						$wpdb->insert(
							$wpdb->prefix . 'woocommerce_api_keys',
							$data,
							array(
								'%d',
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
							)
						);

						$data['key_id']          = $wpdb->insert_id;
						update_option('kachyen_key',$consumer_key);
						update_option('kachyen_sec',$consumer_secret);
						error_log( print_r($data, true));

						$data['consumer_key'] = $consumer_key;
						$data['consumer_secret'] = $consumer_secret;

						return $data;
					} catch ( Exception $e) {
						//TODO log this error?
						return false;
					}

			}
			}
			
			
			
			else {
			wp_die( 'WooCommerce is not Active.');
			}
		
		
	}
	 

    }


