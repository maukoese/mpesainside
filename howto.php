<?php

define( 'MPESA_NAME', '' );
define('MPESA_SHORTCODE', '');
define('MPESA_SECRET', '');
define('MPESA_PASSWORD', '');
define('MPESA_KEY', '');
define('MPESA_TIMEOUT_URL', '');
define('MPESA_RESULT_URL', '');
define('MPESA_CONFIRMATION_URL', '');
define('MPESA_VALIDATION_URL', '');

require_once( 'MPESA.php' );

$mpesa = new MPESA();
