<?php 

Class OnePayFunctions 
	{
	
		
		public function kachMerchantDetails()
		{
			
			 $kach_merchat_apiKey=get_option('kach_merchat_apiKey');
			 
			 if($kach_merchat_apiKey){
				 $data=new STDClass();
				$data->apiKey=get_option('kach_merchat_apiKey');
				$data->secretKey=get_option('kach_merchat_secretKey');
				$data->authenticationKey=get_option('kach_merchat_authenticationKey');
				$data->password=get_option('kach_merchat_password');
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
			 ShowTotalProduct_html();
		 }
		 
		 
		public function StartSyncProductToOnePay(){
			$products = get_posts( array('post_type' => 'product', 'post_status' => 'publish', 'fields' => 'ids', 'posts_per_page' => '-1') );
			if($products){
				foreach($products as $item){
					$item_id=$item->id;
					$sync=get_post_meta('one_pay',$item_id,true);
					if(!$sync){
						$this->SetupForSyncProduct($item);
						
					}
					
				}
			}
			
		 }
		 
		 //////////////////////////////////////////////////
		
		public function SetupForSyncProduct($item){
			$data=array(
						'name'=>$item->title,
						'imageURL'=>'',						
						'uPCEAN'=>'',
						'standardCost'=>'',
						'standardQuantity'=>'',
						'description'=>'',
						'Category'=>'',
						'Brand'=>'',
						'longDescription'=>'',
						'isTaxable'=>'',
						'isSale'=>'',
						'isShipping'=>'',
						'width '=>'',
						'Height'=>'',
						'Depth'=>'',
						'whlUnit'=>'',
						'Weight'=>'',
						'weightUnit'=>'',
						'properitaryUrl'=>''
						
						);


			
		}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	} 