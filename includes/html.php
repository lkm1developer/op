<?php 

	function MerchantRegister_html(){ 
		$str='<div id="message" class="updated woocommerce-message onepatmaindiv"style="display:block !important;">
		<button class="getLoginStatus">getLoginStatus</button>
		<div id="fb-root"></div>
		<script></script>

		<p class="main"><strong>Register as Merchant @ One Pay </strong></p>
		<p>Shipping is currently enabled, but you have not added any shipping methods to your shipping zones.</p>
		

		<p class="submit">
		<form action="" method="post">
			<input type="hidden" name="register_kach_onepay" value="Register"/>
			<input type="submit" class="button-primary" value="Register"/>
			</form>
		</p><p>
		OR </p><p>
		<fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
</fb:login-button>
		<div class="fb-login-button" data-max-rows="1" data-size="medium" data-button-type="continue_with" data-show-faces="true" data-auto-logout-link="true" data-use-continue-as="false"></div>
		</div>';
		echo $str;
	}
	
	
	function MerchantRegistered_html($data){ 
		$str='<div id="message" class="updated woocommerce-message ">
	

		<p class="main"><strong>Your Details as Merchant @ One Pay </strong></p>
		<p>Merchant Name: '.$data->name.'</p><br>
		<p>Email: '.$data->email.'</p><br>
		<p>API Key: </p><p>'.$data->apiKey.'</p><br>
		<p>Secret Key : </p><p>'.$data->secretKey.'</p><br>
		<p>Authentication Key : </p><p>'.$data->authenticationKey.'</p><br>
		<p>Password: </p><p>'.$data->password.'</p><br>
				
		
		</div>';
		echo $str;
	}
	function MerchantRegisteredApiResponce_html($data){ 
		$str='<div id="message" class="updated woocommerce-message ">
	

		<p class="main"><strong>There was an error while register </strong></p>';
		foreach($data->error as $er){
			$str.='<p>'.$er[0].'<p>';
		}
		
		echo $str;
	}
	
	function ShowTotalProduct_html(){
			$total_products = count( get_posts( array('post_type' => 'product', 'post_status' => 'publish', 'fields' => 'ids', 'posts_per_page' => '-1') ) );
	
		$str='<div id="message" class="updated woocommerce-message ">
	

		<p class="main"><strong>Total Product '.$total_products.' </strong></p>
		<p class="main"><strong>To Sync Product '.$total_products.' </strong></p>
		<p class="main"><button id="_opsync"class="btn btn-primary">Sync Product '.$total_products.' </button></p>';
		
		
		echo $str;
	}
