<?php

/**
* @package MPESAOpay 
* @version 0.0.1
* @author Mauko Maunde < hi@mauko.co.ke >
* @see https://developer.safaricom.co.ke
**/

define( 'MPESA_NAME', 'Name' );
define('MPESA_SHORTCODE', 'Shortcode');
define('MPESA_SECRET', 'Secret');
define('MPESA_PASSWORD', 'Password');
define('MPESA_KEY', 'Key');
define('MPESA_TIMEOUT_URL', 'http://yoururl.tld/timeout');
define('MPESA_RESULT_URL', 'http://yoururl.tld/result');
define('MPESA_CONFIRMATION_URL', 'http://yoururl.tld/confirmationt');
define('MPESA_VALIDATION_URL', 'http://yoururl.tld/validation');

require_once( 'MPESA.php' );

$mpesa = new \Safaricom\MPESA();

/*
* C2B
*/
$request = $mpesa -> c2b( $InitiatorName, $CommandID, $Amount, $PartyB );

echo $request;

// Sample M-Pesa Core response received on the callback url.
{
    "Result":{
    "ResultType":0,
    "ResultCode":0,
    "ResultDesc":"The service request has been accepted successfully.",
    "OriginatorConversationID":"19455-424535-1",
    "ConversationID":"AG_20170717_00006be9c8b5cc46abb6",
    "TransactionID":"LGH3197RIB",
    "ResultParameters":{
      "ResultParameter":[
        {
          "Key":"TransactionReceipt",
          "Value":"LGH3197RIB"
        },
        {
          "Key":"TransactionAmount",
          "Value":8000
        },
        {
          "Key":"B2CWorkingAccountAvailableFunds",
          "Value":150000
        },
        {
          "Key":"B2CUtilityAccountAvailableFunds",
          "Value":133568
        },
        {
          "Key":"TransactionCompletedDateTime",
          "Value":"17.07.2017 10:54:57"
        },
        {
          "Key":"ReceiverPartyPublicName",
          "Value":"254708374149 - John Doe"
        },
        {
          "Key":"B2CChargesPaidAccountAvailableFunds",
          "Value":0
        },
        {
          "Key":"B2CRecipientIsRegisteredCustomer",
          "Value":"Y"
        }
      ]
    },
    "ReferenceData":{
      "ReferenceItem":{
        "Key":"QueueTimeoutURL",
        "Value":"https://internalsandbox.safaricom.co.ke/mpesa/b2cresults/v1/submit"
      }
    }
  }
}