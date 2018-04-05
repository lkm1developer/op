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
	public  function Register($data=null) {
		
		global $wpdb;
				
		$hasKey = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}woocommerce_api_keys WHERE description='Kachyng'", OBJECT );

			 
			if(!$hasKey){
				$activator= new One_Pay_Activator;
				$activator->activate();
			}
		$hasKey = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}woocommerce_api_keys WHERE description='Kachyng'", OBJECT );
		 $admin_email = get_option('admin_email');
		$user = get_user_by( 'email', $admin_email );
		
		if($user){
			$user=$user->data;
		/* $user_data=array(
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
						  
		 */				  
		$user_data=array(
							  "user"=>array(
								"merchant_email"=>$data?$data['email']:$user->user_email,
								"merchant_first_name"=>$data?$data['name']:$user->user_nicename ,
								"merchant_last_name" =>$data?$data['name']:$user->user_nicename ,
								"merchant_login" =>$data?$data['email']:$user->user_email ,
								"user_login" =>$data?$data['email']:$user->user_email ,
								"login" =>$data?$data['email']:$user->user_email ,
								"business" =>array(
												"business_name" =>get_bloginfo('name'),
												"business_url" =>get_bloginfo('url')
													),
								"provider" =>array(
												"provider_name" =>get_bloginfo('name'),
												"provider_api_access_token" =>get_bloginfo('url')
													)
								
							  )
						  );
						  
		
			  
		$res=	$this->curl->HttpPost('buy/register/merchant',$user_data);
	
		if($res->success==true)
		{
			update_option('kach_merchat_apiKey',$res->access_keys->apiKey);
			update_option('kach_merchat_secretKey',$res->access_keys->secretKey);
			update_option('kach_merchat_authenticationKey',$res->access_keys->authenticationKey);
			update_option('kach_merchat_password',$res->password);
			update_option('kach_merchat_email',$data?$data['email']:$user->user_email);
			update_option('kach_merchat_name',$data?$data['name']:$user->user_nicename);
			return $res;
		}
		else{
			
			return $res;
		}
			
		
			
	}
	else{
		$res=new STDClass();
		$res->success==false;
		$res->error->user[0]='that user not exist';
		return $res;
	}
	 
	}
	
	
	public function UserRegisterOnOnePay($data){
		
		$data = $data->get_data();
		
		$user_data=array(
		  "user"=>array(
			"email"=> $data['billing']['email'],
			"firstname"=>  $data['billing']['first_name'],
			"lastname"=> $data['billing']['last_name'],
			"authProvider"=>  "string",
			"authProviderId"=>  "string",
			"deviceVendor"=>  "string",
			"deviceId"=>  "string",
			"deviceName"=>  "string",
			"deviceOsVersion"=>  "string"
		  ));
		 
		$res=	$this->curl->HttpPost('accounts/sessions/create',$user_data);
		return $res;
		
	}

	public function UserAddProduct($orderdata,$userdata){
		
		$data = $orderdata->get_data();
		
		$user_data=array(
		  "user"=>array(
			"email"=> $data['billing']['email'],
			"firstname"=>  $data['billing']['first_name'],
			"lastname"=> $data['billing']['last_name'],
			"authProvider"=>  "string",
			"authProviderId"=>  "string"
				),
		  "cartItems"=>array(
						
						  "number"=>324,
						  "name"=>  'hat',
						  "price"=> $data['total'],
						  "upc"=>324,
						  "active"=>true,
						  "description"=> 'this is hat hat',
						  "images"=> array(
							""
						  ),
						  "package_dimensions"=> array(
													"height"=> 0,
													"length"=>0,
													"width"=> 0,
													"length_unit"=> "string",
													"weight"=> 0,
													"weight_unit"=> "string"
												  ),
						  "shippable"=> true,
						  "google_category"=> "string",
						  "taxable"=> true,
						  "on_sale"=> true,
						  "sale_price"=> 10.99,
						
						  
						  "cartAddress"=> array(
											
											  "addressLine1"=>$data['billing']['address_1'],
											  "addressLine2"=> $data['billing']['address_2'],
											  "cityName"=> $data['billing']['city'],
											  "country"=> $data['billing']['country'],
											  "region"=> $data['billing']['state'],
											  "postalCode"=> $data['billing']['postcode']
											
										  )
						)
		  );
		echo '<pre>';  
		$res=	$this->curl->HttpPost('buy/product/addCart ',$user_data);
		
		var_dump($user_data);var_dump($res);die();
		return $res;
		
	}

	
}


