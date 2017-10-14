<?php 

class MPESA{

	function __construct( $key, $secret ){
		$this -> key = $key;
		$this -> secret = $secret;
	}

	private function authenticate(){
		$url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		$credentials = base64_encode($this -> key.':'.$this -> secret );
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials)); //setting a custom header
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$curl_response = curl_exec($curl);

		return json_decode($curl_response);
	}

	function setcred(){
		$publicKey = "PATH_TO_CERTICATE";
		//$plaintext = "Safaricom132!";
		$plaintext = "YOUR_PASSWORD";

		openssl_public_encrypt($plaintext, $encrypted, $publicKey, OPENSSL_PKCS1_PADDING);

		echo base64_encode($encrypted);
	}

}

$mpesa = new MPESA('l6jE7kgV4lCtNH4aveMueR9QdGkbutfR', '5slRuAafb4Gk7Ogo');
?>