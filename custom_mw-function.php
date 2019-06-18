<?php
//contact form before submiting
 
function mwpayframe_curl() {

$mwformdata = $_POST['formdata'];
/*var_dump($mwformdata);
exit();*/
$merchantUuid = $mwformdata[6]["value"];
$apiKey = $mwformdata[7]["value"];
$transactionAmount = $mwformdata[25]["value"];
$transactionAmount1 = number_format($transactionAmount,2,'.','');
$transactionCurrency = $mwformdata[12]["value"];
$transactionProduct = 'Test Product';
$customerName = $mwformdata[14]["value"];
$customerCountry = $mwformdata[22]["value"];
$customerState = $mwformdata[20]["value"];
$customerCity = $mwformdata[19]["value"];
$customerAddress = $mwformdata[18]["value"];
$customerPostCode = $mwformdata[21]["value"];
$customerPhone = $mwformdata[17]["value"];
$customerEmail = $mwformdata[16]["value"];
$hash = $mwformdata[11]["value"];
$payframeKey = $mwformdata[9]["value"];
$payframeToken = $mwformdata[8]["value"];




$fields = array(
	'method' => urlencode('processCard'),
	'merchantUUID' =>  urlencode($merchantUuid),
	'apiKey'	=>  urlencode($apiKey),
	'payframeToken' =>  urlencode($payframeToken),
	'payframeKey' =>  urlencode($payframeKey),
	'transactionAmount' =>  $transactionAmount1,
	'transactionCurrency' =>  $transactionCurrency,
	'transactionProduct'=> urlencode($transactionProduct),
	'customerName'=> urlencode($customerName),
	'customerCountry'=> urlencode($customerCountry),
	'customerState'=> urlencode($customerState),
	'customerCity'=> urlencode($customerCity),
	'customerAddress'=> urlencode($customerAddress),
	'customerPostCode'=>urlencode($customerPostCode),
	'hash'=>urlencode($hash),
);

$fields_string = http_build_query($fields, '', '&');

$ch = curl_init("https://api.merchantwarrior.com/payframe/");

curl_setopt($ch,CURLOPT_POST,count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

curl_setopt($ch,CURLOPT_HEADER , false);

curl_setopt($ch,CURLOPT_RETURNTRANSFER , true);
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER , false);
curl_setopt($ch,CURLOPT_FORBID_REUSE , true);
curl_setopt($ch,CURLOPT_FRESH_CONNECT , true);
curl_setopt($ch,CURLOPT_TIMEOUT , 45);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT , 30);

$response = curl_exec($ch);
$error = curl_error($ch);

// Check for CURL errors
if (isset($error) && strlen($error)) {
    throw new Exception("CURL Error: {$error}");
}

// Parse the XML
$xml = simplexml_load_string($response);

// Convert the result from a SimpleXMLObject into an array
$xml = (array)$xml;

// Validate the response - the only successful code is 0
$status = ((int)$xml['responseCode'] === 0) ? true : false;

// Make the response a little more useable
$result = array (
  'status' => $status, 
  'transactionID' => (isset($xml['transactionID']) ? $xml['transactionID'] : null),
  'responseData' => $xml
);
//print_r($result);
$response = array(
    'TransactionID' => $result['transactionID'],
    'AuthMessage' => $result['responseData']['responseMessage'],
);
print_r(json_encode($response,JSON_PRETTY_PRINT));
exit();
}
add_action('wp_ajax_mwpayframe_curl', 'mwpayframe_curl' ); // executed when logged in
add_action('wp_ajax_nopriv_mwpayframe_curl', 'mwpayframe_curl' ); // executed when logged out