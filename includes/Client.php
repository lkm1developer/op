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

   
    


	
	 
	 public function __construct()
	 {
			//$this->ApiUrl='https://app.kachyng.com/api/v2/';
			$this->ApiUrl='https://dev.kachyng.com/api/v2/';
			$this->curl = new Curl();
			$this->curl->setHeader('X-Requested-With', 'application/json');
			$this->curl->setHeader('Content-Type', 'application/json');
			
	 }
	  public function HttpPost($url,$data)
	 {
			
			
			$this->curl->post($this->ApiUrl.$url, $data);
			if ($this->curl->error) {
				return  'Error: ' . $this->curl->errorCode . ': ' . $this->curl->errorMessage . "\n";
			} else {
				
				return $this->curl->response;
			}				  
			
	 }


    
}
