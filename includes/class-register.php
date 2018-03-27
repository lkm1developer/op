<?php
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Client.php';
use Includes\Client;

class OnePayRegister {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	 
	 public function __construct()
	 {
			$this->curl = new Includes\Client();
			
			
	 }
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
						  
					  
		$res=	$this->curl->HttpPost('accounts/sessions/create',$user_data);		  
		var_dump($res);	
			
		
			
	}
	 

}


