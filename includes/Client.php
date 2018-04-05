<?php
/**
 * WooCommerce REST API Client
 *
 * @category Client
 * @package  Automattic/WooCommerce
 */

namespace Includes;

use \Curl\Curl;

class Client
{

   
    


	
	 
	 public function __construct($header=null)
	 {
			//$this->ApiUrl='https://app.kachyng.com/api/v2/';
			$this->ApiUrl='https://dev.kachyng.com/api/v2/';
			$this->curl = new Curl();
			$this->curl->setHeader('X-Requested-With', 'application/json');
			$this->curl->setHeader('Content-Type', 'application/json');
			
			if($header){
				/* 'Authorization: OAuth SomeHugeOAuthaccess_tokenThatIReceivedAsAString'; */
				/* Authorization: Basic id:api_key */
				
				
				$this->curl->setHeader('Authorization', 'Basic Pujjd1na56x23m9Zs97ka6tuKnnyk050');
				$this->curl->setHeader('apiKey', 'gu7XeGszu8S6b2pMfcJjDvUnTfiN7Wfv');
				//$this->curl->setHeader('apiSecretKey', $header['apiSecretKey']);
				
			}
			
	 }
	  public function HttpPost($url,$data)
	 {
			
			
			$c=$this->curl->post($this->ApiUrl.$url, $data);
			
			if ($this->curl->error) {
				return  'Error: ' . $this->curl->errorCode . ': ' . $this->curl->errorMessage . "\n";
			} else {
				
				return $this->curl->response;
			}				  
			
	 }


    
}
