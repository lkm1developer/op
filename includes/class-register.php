<?php
use \Curl\Curl;

class OnePayRegister {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public  function Register() {
		
		global $wpdb;
				
		$hasKey = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}woocommerce_api_keys WHERE description='Kachyng'", OBJECT );

			 
			if(!$hasKey){
				$activator= new One_Pay_Activator;
				$activator->activate();
			}
		$hasKey = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}woocommerce_api_keys WHERE description='Kachyng'", OBJECT );
		 $admin_email = get_option('admin_email');
		$user = get_user_by( 'email', $admin_email );
		$user=$user->data;
		var_dump($user);
		$user_data=array(
							  "user"=>array(
								"email"=>$user->user_email,
								"firstname"=>$user->user_nicename ,
								"lastname" =>$user->user_nicename ,
								"authProvider" =>$user->user_nicename ,
								"authProviderId" =>$user->user_nicename ,
								"deviceVendor" =>$user->user_nicename ,
								"deviceId"=>$user->user_nicename ,
								"deviceName" =>$user->user_nicename ,
								"deviceOsVersion"=>$user->user_nicename ,
							  )
						  );
						  
						  
			$curl = new Curl();
			$curl->post('https://app.kachyng.com/api/v2/accounts/sessions/create', $user_data);
			if ($curl->error) {
				echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
			} else {
				echo 'Response:' . "\n";
				var_dump($curl->response);
			}				  
		
			
	}
	 

}


