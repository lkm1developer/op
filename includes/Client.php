<?php
/**
 * WooCommerce REST API Client
 *
 * @category Client
 * @package  Automattic/WooCommerce
 */

namespace OnePay\Includes;



class Client
{

   
    public function __construct()
    {
        
    }


	public	function HttpClient($Url,$method,$data=null){
		 
			// is cURL installed yet?
			if (!function_exists('curl_init')){
				die('Sorry cURL is not installed!');
			}
		 
			// OK cool - then let's create a new cURL resource handle
			$ch = curl_init();
		 
			// Now set some options (most are optional)
		 
			// Set URL to download
			curl_setopt($ch, CURLOPT_URL, $Url);
		 
			// Set a referer
			curl_setopt($ch, CURLOPT_REFERER, "http://www.example.org/yay.htm");
		 
			// User agent
			curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
		 
			// Include header in result? (0 = yes, 1 = no)
			curl_setopt($ch, CURLOPT_HEADER, 0);
		 
			// Should cURL return or print out the data? (true = return, false = print)
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 
			// Timeout in seconds
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			If($method=='post'){
			curl_setopt($ch, CURLOPT_POST, 1);
			}
			if(!$data==null){
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			}
			// Download the given URL, and return output
			$output = curl_exec($ch);
		 
			// Close the cURL resource, and free system resources
			curl_close($ch);
			
			
			if(!curl_exec($ch)){
				die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
			}
			return $output;
		}


    
}
