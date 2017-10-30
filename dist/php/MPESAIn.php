<?php

/**
* @package MPESA Inside
* @version 0.0.1
* @author Mauko Maunde < hi@mauko.co.ke >
* @see https://developer.safaricom.co.ke/docs
**/
namespace Safaricom;


/**
 * Class MPESA
 * @link https://developer.safaricom.co.ke/docs
 */
class MPESA{


	private $business;
	private $shortcode;
	private $consumer_key;
	private $consumer_secret;
	private $password;
	private $publicKey;
	private $timeout_url;
	private $result_url;
	private $confirmation_url;
	private $validation_url;

	private $live;



	public function __construct( $live = true, $public_key = "cert.cr" ){
		$this -> business = MPESA_NAME;
		$this -> shortcode = MPESA_SHORTCODE;
		$this -> key = MPESA_APP_KEY;
		$this -> secret = MPESA_APP_SECRET;
		$this -> password = MPESA_PASSWORD;
		$this -> publicKey = $public_key;
		$this -> timeout_url = MPESA_TIMEOUT_URL;
		$this -> result_url = MPESA_RESULT_URL;
		$this -> confirmation_url = MPESA_CONFIRMATION_URL;
		$this -> validation_url = MPESA_VALIDATION_URL;

        $this -> live = $live;
	}



    private function auth(){

        $curl = curl_init();
        curl_setopt( $curl, CURLOPT_URL, $this -> authenticate_url );
        $credentials = base64_encode( $this -> key.':'.$this -> secret );
        curl_setopt( $curl, CURLOPT_HTTPHEADER, array( 'Authorization: Basic '.$credentials ) ); //setting a custom header
        curl_setopt( $curl, CURLOPT_HEADER, true );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );

        $curl_response = curl_exec( $curl );

        return json_decode( $curl_response );
    }

    private function securityCredential(){

        openssl_public_encrypt( $this -> pass, $encrypted, $this -> publicKey, OPENSSL_PKCS1_PADDING );

        return base64_encode( $encrypted );
    }

    /**
     * @return mixed
     */
    public static function generateLiveToken(){
        $url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        $credentials = base64_encode( $this -> consumer_key.':'.$this -> consumer_secret );
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials)); //setting a custom header
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $curl_response = curl_exec($curl);

        return json_decode($curl_response)->access_token;
    }


    /**
     * @return mixed
     */
    public static function generateSandBoxToken(){
        $consumer_key = MPESA_SECRET;
        $consumer_secret = MPESA_KEY;
        $url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        $credentials = base64_encode( $this -> consumer_key.':'.$this -> consumer_secret );
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials)); //setting a custom header
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $curl_response = curl_exec($curl);

        return json_decode($curl_response)->access_token;
    }

    /**
     * @param $CommandID
     * @param $Initiator
     * @param $SecurityCredential
     * @param $TransactionID
     * @param $Amount
     * @param $ReceiverParty
     * @param $RecieverIdentifierType
     * @param $ResultURL
     * @param $QueueTimeOutURL
     * @param $Remarks
     * @param $Occasion
     * @return mixed|string
     */
    public static function reversal($CommandID, $Initiator, $TransactionID, $Amount, $ReceiverParty, $RecieverIdentifierType $Remarks, $Occasion ){
        if( $this -> live == "true"){
            $url = 'https://api.safaricom.co.ke/mpesa/reversal/v1/request';
            $token = $this -> generateLiveToken();
        } elseif ($this -> live == "false"){
            $url = 'https://sandbox.safaricom.co.ke/mpesa/reversal/v1/request';
            $token = $this -> generateSandBoxToken();
        } else {
            return json_encode(["Message"=>"invalid application status"]);
        }
        


        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token));


        $curl_post_data = array(
            'CommandID' => $CommandID,
            'Initiator' => $Initiator,
            'SecurityCredential' => $this -> securityCredential(),
            'TransactionID' => $TransactionID,
            'Amount' => $Amount,
            'ReceiverParty' => $ReceiverParty,
            'RecieverIdentifierType' => $RecieverIdentifierType,
            'ResultURL' => $this -> result_url,
            'QueueTimeOutURL' => $this -> timeout_url,
            'Remarks' => $Remarks,
            'Occasion' => $Occasion
        );

        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $curl_response = curl_exec($curl);
        return json_decode($curl_response);

    }


    /**
     * @param $ShortCode
     * @param $CommandID
     * @param $Amount
     * @param $Msisdn
     * @param $BillRefNumber
     * @return mixed|string
     */
    public  static  function  b2c( $CommandID, $Amount, $Msisdn, $BillRefNumber ){
        if( $this -> live == "true"){
            $url = 'https://api.safaricom.co.ke/mpesa/c2b/v1/simulate';
            $token = $this -> generateLiveToken();
        }elseif ($this -> live == "false"){
            $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';
            $token = $this -> generateSandBoxToken();
        }else{
            return json_encode(["Message"=>"invalid application status"]);
        }

        

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token));

        $curl_post_data = array(
            'ShortCode' => $this -> shortcode,
            'CommandID' => $CommandID,
            'Amount' => $Amount,
            'Msisdn' => $Msisdn,
            'BillRefNumber' => $BillRefNumber
        );

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $curl_response = curl_exec($curl);
        return $curl_response;

    }


    /**
     * @param $live - Takes two values "true" or "false" || "true" for live applications and "false"  for sandbox applications
     * @param $CommandID
     * @param $Initiator
     * @param $SecurityCredential
     * @param $PartyA
     * @param $IdentifierType
     * @param $Remarks
     * @param $QueueTimeOutURL
     * @param $ResultURL
     * @return mixed|string
     */
    public static function accountBalance($live, $CommandID, $Initiator, $SecurityCredential, $PartyA, $IdentifierType, $Remarks ){
        if( $this -> live == "true"){
            $url = 'https://api.safaricom.co.ke/mpesa/accountbalance/v1/query';
            $token = $this -> generateLiveToken();
        }elseif ($this -> live == "false"){
            $url = 'https://sandbox.safaricom.co.ke/mpesa/accountbalance/v1/query';
            $token = $this -> generateSandBoxToken();
        }else{
            return json_encode(["Message"=>"invalid application status"]);
        }
        

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token)); //setting custom header


        $curl_post_data = array(
            'CommandID' => $CommandID,
            'Initiator' => $Initiator,
            'SecurityCredential' => $SecurityCredential,
            'PartyA' => $PartyA,
            'IdentifierType' => $IdentifierType,
            'Remarks' => $Remarks,
            'QueueTimeOutURL' => $this -> timeout_url,
            'ResultURL' => $this -> result_url
        );

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $curl_response = curl_exec($curl);
        return $curl_response;
    }

    /**
     * @param $live - Takes two values "true" or "false" || "true" for live applications and "false"  for sandbox applications
     * @param $Initiator
     * @param $SecurityCredential
     * @param $CommandID
     * @param $TransactionID
     * @param $PartyA
     * @param $IdentifierType
     * @param $ResultURL
     * @param $QueueTimeOutURL
     * @param $Remarks
     * @param $Occasion
     * @return mixed|string
     */
    public function transactionStatus($live , $Initiator, $SecurityCredential, $CommandID, $TransactionID, $PartyA, $IdentifierType, $ResultURL, $QueueTimeOutURL, $Remarks, $Occasion){
        if( $this -> live == "true"){
            $url = 'https://api.safaricom.co.ke/mpesa/transactionstatus/v1/query';
            $token=$this -> generateLiveToken();
        }elseif ($this -> live == "false"){
            $url = 'https://sandbox.safaricom.co.ke/mpesa/transactionstatus/v1/query';
            $token=$this -> generateSandBoxToken();
        }else{
            return json_encode(["Message"=>"invalid application status"]);
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token)); //setting custom header


        $curl_post_data = array(
            'Initiator' => $Initiator,
            'SecurityCredential' => $SecurityCredential,
            'CommandID' => $CommandID,
            'TransactionID' => $TransactionID,
            'PartyA' => $PartyA,
            'IdentifierType' => $IdentifierType,
            'ResultURL' => $ResultURL,
            'QueueTimeOutURL' => $QueueTimeOutURL,
            'Remarks' => $Remarks,
            'Occasion' => $Occasion
        );

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $curl_response = curl_exec($curl);


        return $curl_response;
    }


    /**
     * @param $live - Takes two values "true" or "false" || "true" for live applications and "false"  for sandbox applications
     * @param $Initiator
     * @param $SecurityCredential
     * @param $Amount
     * @param $PartyA
     * @param $PartyB
     * @param $Remarks
     * @param $QueueTimeOutURL
     * @param $ResultURL
     * @param $AccountReference
     * @param $commandID
     * @param $SenderIdentifierType
     * @param $RecieverIdentifierType
     * @return mixed|string
     */
    public function b2b($live , $Initiator, $SecurityCredential, $Amount, $PartyA, $PartyB, $Remarks, $QueueTimeOutURL, $ResultURL, $AccountReference, $commandID, $SenderIdentifierType, $RecieverIdentifierType){
        if( $this -> live == "true"){
            $url = 'https://api.safaricom.co.ke/mpesa/b2b/v1/paymentrequest';
            $token=$this -> generateLiveToken();
        }elseif ($this -> live == "false"){
            $url = 'https://sandbox.safaricom.co.ke/mpesa/b2b/v1/paymentrequest';
            $token=$this -> generateSandBoxToken();
        }else{
            return json_encode(["Message"=>"invalid application status"]);
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token)); //setting custom header
        $curl_post_data = array(
            'Initiator' => $Initiator,
            'SecurityCredential' => $SecurityCredential,
            'CommandID' => $commandID,
            'SenderIdentifierType' => $SenderIdentifierType,
            'RecieverIdentifierType' => $RecieverIdentifierType,
            'Amount' => $Amount,
            'PartyA' => $PartyA,
            'PartyB' => $PartyB,
            'AccountReference' => $AccountReference,
            'Remarks' => $Remarks,
            'QueueTimeOutURL' => $QueueTimeOutURL,
            'ResultURL' => $ResultURL
        );
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        $curl_response = curl_exec($curl);
        return $curl_response;

    }

    /**
     * @param $live - Takes two values "true" or "false" || "true" for live applications and "false"  for sandbox applications
     * @param $BusinessShortCode
     * @param $LipaNaMpesaPasskey
     * @param $TransactionType
     * @param $Amount
     * @param $PartyA
     * @param $PartyB
     * @param $PhoneNumber
     * @param $CallBackURL
     * @param $AccountReference
     * @param $TransactionDesc
     * @param $Remark
     * @return mixed|string
     */
    public function STKPushSimulation($live , $BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remark){
        if( $this -> live == "true"){
            $url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
            $token=$this -> generateLiveToken();
        }elseif ($this -> live == "false"){
            $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
            $token=$this -> generateSandBoxToken();
        }else{
            return json_encode(["Message"=>"invalid application status"]);
        }
        

        $timestamp='20'.date(    "ymdhis");
        $password=base64_encode($BusinessShortCode.$LipaNaMpesaPasskey.$timestamp);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token));


        $curl_post_data = array(
            'BusinessShortCode' => $BusinessShortCode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => $TransactionType,
            'Amount' => $Amount,
            'PartyA' => $PartyA,
            'PartyB' => $PartyB,
            'PhoneNumber' => $PhoneNumber,
            'CallBackURL' => $CallBackURL,
            'AccountReference' => $AccountReference,
            'TransactionDesc' => $TransactionType,
            'Remark'=> $Remark
        );

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $curl_response=curl_exec($curl);
        return $curl_response;


    }


    /**
     * @param $live - Takes two values "true" or "false" || "true" for live applications and "false"  for sandbox applications
     * @param $checkoutRequestID
     * @param $businessShortCode
     * @param $password
     * @param $timestamp
     * @return mixed|string
     */
    public static function STKPushQuery($live, $checkoutRequestID, $businessShortCode, $password, $timestamp){
        if( $this -> live == "true"){
            $url = 'https://api.safaricom.co.ke/mpesa/stkpushquery/v1/query';
            $token=$this -> generateLiveToken();
        }elseif ($this -> live == "false"){
            $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpushquery/v1/query';
            $token=$this -> generateSandBoxToken();
        }else{
            return json_encode(["Message"=>"invalid application status"]);
        }
        

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$token));


        $curl_post_data = array(
            'BusinessShortCode' => $businessShortCode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'CheckoutRequestID' => $checkoutRequestID
        );

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HEADER, false);

        $curl_response = curl_exec($curl);

        return $curl_response;
    }

    /**
     *
     */
    public function confirm(){
        $resultArray=[
            "ResultDesc"=>"Confirmation Service request accepted successfully",
            "ResultCode"=>"0"
        ];
        header('Content-Type: application/json');

        echo json_encode($resultArray);
    }


    /**
     *
     */
    public function validate(){
        $resultArray=[
            "ResultDesc"=>"Confirmation Service request accepted successfully",
            "ResultCode"=>"0"
        ];

        header('Content-Type: application/json');

        echo json_encode($resultArray);
    }

    /**
     *
     */
    public function decline(){
        $resultArray=[
            "ResultDesc"=>"Confirmation Service request declined",
            "ResultCode"=>"1"
        ];

        header('Content-Type: application/json');

        echo json_encode($resultArray);
    }

}
