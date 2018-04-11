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
			$id= get_current_user_id();
			
			update_option('kach_user1'.$id,json_encode($res));
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
	
	public function UserRegister($data){
		
		
		
		$user_data=array(
		  "user"=>array(
			"email"=> $data['email'],
			"firstname"=>  $data['name'],
			"lastname"=> $data['name'],
			"authProvider"=>  "string",
			"authProviderId"=>  "string",
			"deviceVendor"=>  "string",
			"deviceId"=>  "string",
			"deviceName"=>  "string",
			"deviceOsVersion"=>  "string"
		  ));
		 
		$res=	$this->curl->HttpPost('accounts/sessions/create',$user_data);
		
		if($res->success==true){
		$user_id = username_exists( $data['email'] );
			if ( !$user_id and email_exists($data['email']) == false ) {
				$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
				$user_id = wp_create_user( $data['name'], $random_password, $data['email'] );
				$email = $data['email'] ;
				$user = get_user_by('email', $email );

				// Redirect URL //
				if ( !is_wp_error( $user ) )
				{
					wp_clear_auth_cookie();
					wp_set_current_user ( $user_id );
					wp_set_auth_cookie  ( $user_id );
					update_option('kach_user'.$user_id,json_encode($res));
					$url=get_bloginfo('url').'/my-account/edit-address/billing/';
					//return $res;
					$a['success']=true;
					$a['redirect']=$url;
					$a['res']=$res;
					return $a;
				}
			} else {
				$a['success']=false;
				$a['error'][]='User already exists.  Password inherited.';
				return $a;
			}	
		}
		else {
				$a['success']=false;
				$a['error'][]='Something went wrong!!';
				return $a;
			}	
		
	}

	public function UserAddToCart(){
		$user=get_user_meta( get_current_user_id() );
		if($user){
		global $woocommerce;
		$items = $woocommerce->cart->get_cart();
		if($items){
			
		

        foreach($items as $item => $values) {
			
            $_product =  wc_get_product( $values['data']->get_id()); 
			$id=$values['data']->get_id();
          /*  var_dump($_product->short_description);die(); */
			$category_ids=$_product->category_ids;
			$cat=null;
			if(is_array($category_ids)){
				
				foreach($category_ids as $c){
					
					$cat .=get_the_category_by_ID(  $c ) .' , ';
					
				}
			}
			$cart_items[]=array(
						"sessionToken"=>'qtb9ac2mewaCXvdojGLayjYVnkACtuMg',
						  "number"=>$id,
						  "name"=> $_product->get_title(),
						  "price"=> $_product->get_price(),
						  "standardCost"=> $_product->get_price(),
						  "upc"=>$id,
						  "active"=>true,
						  "description"=> $_product->short_description,
						 /*  "images"=> array(
						  "is_deafult"=>"true",
							"url"=>
							get_the_post_thumbnail_url($id)
						  ), */
						  "imageURL"=> get_the_post_thumbnail_url($id),
						  
						  "package_dimensions"=> array(
													"height"=> get_post_meta($id,'height',true),
													"length"=>get_post_meta($id,'length',true),
													"width"=> get_post_meta($id,'_width',true),
													"length_unit"=> "string",
													"weight"=> get_post_meta($id,'weight',true),
													"weight_unit"=> "string"
												 ),
						  "shippable"=> true,
						  "google_category"=>$cat,
						  "taxable"=> $_product->tax_status?'Y':'N',
						  "on_sale"=> get_post_meta($id,'_sale_price',true)?'Y':'N',
						  "sale_price"=> $_product->get_price(),
						
						  
						  "cartAddress"=> array(
											
											  "addressLine1"=>$user['billing_address_1'][0],
											  "addressLine2"=> $user['billing_address_2'][0],
											  "cityName"=> $user['billing_city'][0],
											  "country"=> $user['billing_country'][0],
											  "region"=> $user['billing_state'][0],
											  "postalCode"=> $user['billing_postcode'][0]
											
										  )
						);
        } 
		
		
		$user_data=array(
		"sessionToken"=>'qtb9ac2mewaCXvdojGLayjYVnkACtuMg',
		  "user"=>array(
			"email"=> $user['billing_email'][0],
			"firstname"=>  $user['billing_first_name'][0],
			"lastname"=> $user['billing_last_name'][0],
			"authProvider"=>  "string",
			"authProviderId"=>  "string",
			"sessionToken"=>'qtb9ac2mewaCXvdojGLayjYVnkACtuMg'
				),
		  "cartItems"=>$cart_items
		  );
		  /* echo '<pre>';
		  var_dump($user_data);die(); */
		  $user_data=json_encode($user_data,true); 
		
		
				$curl = curl_init();

					curl_setopt_array($curl, array(
					  CURLOPT_URL => "https://dev.kachyng.com/api/v2/buy/product/add",
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_ENCODING => "",
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 30,
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => "POST",
					  CURLOPT_POSTFIELDS => $user_data,
					  CURLOPT_HTTPHEADER => array(
						"Authorization: Basic YXBpS2V5Om5NanFSeTcxR1ZmSGFiam00cmQ0Sk13WkQ0MlRkVGlp",
						"Cache-Control: no-cache",
						"Content-Type: application/json",
						"Postman-Token: 23d7d223-aa6e-4449-9508-b11acfda6d32",
						"apiKey: nMjqRy71GVfHabjm4rd4JMwZD42TdTii"
					  ),
					));

					$response = curl_exec($curl);
					$err = curl_error($curl);

					curl_close($curl);

					/* if ($err) {
					  echo "cURL Error #:" . $err;
					} else {
					  echo $response;
					}
							var_dump($response);
							die();
							return $res;
							 */
						}
	}
	
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
		  "cartItems"=>array([
						
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
						])
		  );
		 $user_data=json_encode($user_data,true); 
		echo '<pre>';  
		$res=	$this->curl->HttpPost('buy/product/addCart',$user_data);
		
		var_dump($user_data);var_dump($res);die();
		return $res;
		
	}

	
}


