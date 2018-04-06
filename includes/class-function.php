<?php 

Class OnePayFunctions 
	{
		public function __construct()
	 {
			$api=array(
						'apiKey'=>get_option('kach_merchat_apiKey'),
						'apiSecretKey'=>get_option('kach_merchat_secretKey'));
					
						
			$this->curl = new Includes\Client($api);
			
			
	 }	
		
		public function kachMerchantDetails()
		{
			
			 $kach_merchat_apiKey=get_option('kach_merchat_apiKey');
			 
			 if($kach_merchat_apiKey){
				 $data=new STDClass();
				$data->apiKey=get_option('kach_merchat_apiKey');
				$data->secretKey=get_option('kach_merchat_secretKey');
				$data->authenticationKey=get_option('kach_merchat_authenticationKey');
				$data->password=get_option('kach_merchat_password');
			 	$data->email=get_option('kach_merchat_email');
			 	$data->name=get_option('kach_merchat_name');
			 	 return $data;
			 }
			 else{
				 return false;
			 }
			
		}
		
		public function IsMerchantRegistered()
		{
			 $kach_merchat_apiKey=$this->kachMerchantDetails();

			 if($kach_merchat_apiKey){
				MerchantRegistered_html($kach_merchat_apiKey);
			 	 
			 }
			 elseif(isset($_POST['register_kach_onepay'])){
				 
					$Register=new OnePayRegister; 
					$res=$Register->Register();
					if($res->success==true){
						$kach_merchat_apiKey=$this->kachMerchantDetails();

							 if($kach_merchat_apiKey){
								MerchantRegistered_html($kach_merchat_apiKey);
								 
							 }
					}
					else{
						MerchantRegisteredApiResponce_html($res);
					}
				 
				 
				 
			 }
			 else{
				 MerchantRegister_html();
			 }
			
		}
		
		//end functionn
		
		
		// start syncing woo-com product 
		 public function SyncProductToOnePay(){
			 $total_products = count( get_posts( array('post_type' => 'product', 'post_status' => 'publish', 'fields' => 'ids', 'posts_per_page' => '-1') ) );
			 $tosync = count(get_posts( array('post_type' => 'product', 'post_status' => 'publish', 'fields' => 'ids', 'posts_per_page' => '-1',
			'meta_query' => array(
					array(
					 'key' => 'one_pay',
					 'compare' => 'NOT EXISTS' // this should work...
					)
				)
			) ));
			 ShowTotalProduct_html($total_products,$tosync);
		 }
		 
		 
		public function StartSyncProductToOnePay(){
			$products = get_posts( array('post_type' => 'product', 'post_status' => 'publish', 'fields' => 'ids', 'posts_per_page' => '-1',
			'meta_query' => array(
					array(
					 'key' => 'one_pay',
					 'compare' => 'NOT EXISTS' // this should work...
					)
				)
			) );
	
			
			if(count($products)>0){//var_dump($products);	die();
				foreach($products as $item){
					
					$sync=get_post_meta($item,'one_pay',true);
					
					if(!$sync){
						$product=$this->SetupForSyncProduct($item);
						$curl = curl_init();
							//echo json_encode($product,true);die();
							curl_setopt_array($curl, array(
							  CURLOPT_URL => "https://dev.kachyng.com/api/v2/buy/product/add",
							  CURLOPT_RETURNTRANSFER => true,
							  CURLOPT_ENCODING => "",
							  CURLOPT_MAXREDIRS => 10,
							  CURLOPT_TIMEOUT => 30,
							  CURLOPT_SSL_VERIFYHOST=>0,
							CURLOPT_SSL_VERIFYPEER=>0,
							  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
							  CURLOPT_CUSTOMREQUEST => "POST",
							  CURLOPT_POSTFIELDS =>json_encode($product,true),
							 
							  CURLOPT_HTTPHEADER => array(
								"Authorization: Basic ".get_option('kach_merchat_secretKey'),
								"Cache-Control: no-cache",
								"Content-Type: application/json",
								"Postman-Token: 80907bea-1054-4db3-a8fa-be87142b6f81",
								"apiKey:".get_option('kach_merchat_apiKey')
							  ),
							));

							$response = curl_exec($curl);
							$err = curl_error($curl);

							curl_close($curl);

							if ($err) {
							  echo "cURL Error #:" . $err;
							} else {
								update_post_meta($item,'one_pay',true);
							  echo $response;
							}
						
						//$res=	$this->curl->HttpPost('buy/product/add',$product);
						//var_dump($res);
						
					}
					
				}
				
			}
			else{
				$a['success']=false;
				
				echo json_encode($a);wp_die();
			}
			
		 }
		 
		 //////////////////////////////////////////////////
		
		public function SetupForSyncProduct($item){
			
			
			$_product = new WC_Product($item);
			$category_ids=$_product->category_ids;
			$cat=null;
			if(is_array($category_ids)){
				
				foreach($category_ids as $c){
					
					$cat .=get_the_category_by_ID(  $c ) .' , ';
					
				}
			}
			 $data= array(
						'apiKey'=>get_option('kach_merchat_apiKey'),
						
						'cartItems'=>array([
						'name'=>$_product->name,
						'imageURL'=>get_the_post_thumbnail_url( $item),						
						'uPCEAN'=>1234567891 .$item,
						'standardCost'=>$_product->price,
						'standardQuantity'=>$_product->stock_quantity?$_product->stock_quantity:1000,
						'description'=>substr($_product->description,0,30),
						'Category'=>$cat,
						'Brand'=>'',
						'longDescription'=>$_product->description,
						'isTaxable'=>$_product->tax_status?'Y':'N',
						'isSale'=>get_post_meta($item,'_sale_price',true)?'Y':'N',
						'isShipping'=>'',
						'width '=>get_post_meta($item,'_width',true),
						'Height'=>get_post_meta($item,'height',true),
						'Depth'=>get_post_meta($item,'length',true),
						'whlUnit'=>'',
						'Weight'=>get_post_meta($item,'weight',true),
						'weightUnit'=>'',
						'properitaryUrl'=>''
						
						]));
						
						$data= (object)$data;
						//var_dump($data);die();
						//echo json_encode($data);die();
						return $data;
						
		}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	} 