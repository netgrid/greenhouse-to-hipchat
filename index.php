<?php
require 'vendor/autoload.php';
require 'Config.php';

use Buzz\Browser;
use Buzz\Client\Curl;

function parse_inifile() {
	Config::load_config("config.ini");	
}

/* 
  Make a URL small
  * http://davidwalsh.name/bitly-php 
*/
function make_bitly_url($url,$login,$appkey,$format = 'xml',$version = '2.0.1') {
	//create the URL
	$bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$login.'&apiKey='.$appkey.'&format='.$format;
	//get the url
	// could also use cURL here
	$response = file_get_contents($bitly);
	//parse depending on desired format
	if(strtolower($format) == 'json') {
		$json = json_decode($response,true);
		return $json['results'][$url]['shortUrl'];
	} else {
		//xml
		$xml = simplexml_load_string($response);
		return 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
	}
}

function parse_request($request = NULL) {
	if($request != NULL) {
		$response = json_decode($request, true);
		$dateStart = $response['build']['started_at'];
		$dateEnd   = $response['build']['finished_at'];
		$elapsed = abs(strtotime($dateEnd) - strtotime($dateStart));
		if(Config::UseBitly() )
			$buildURL =  make_bitly_url($response['build']['web_url'], Config::getBitlyLogin(), Config::getBitlyAppKey(), 'json'); 
		else
			$buildURL = $response['build']['web_url'];

		if($response['build']['status'] == "success") {
			$responseMessage = '{"color": "green", "message": "'.$response['project']['name'].' #'.$response['build']['build_number'].' Success after '.$elapsed.' sec ('.$buildURL.')", "notify": true, "message_format": "text"}';
		} else {
			$responseMessage = '{"color": "red", "message": "'.$response['project']['name'].' #'.$response['build']['build_number'].' FAILURE after '.$elapsed.' sec ('.$buildURL.')", "notify": true, "message_format": "text"}';
		} 
	}
	return $responseMessage;
}

parse_inifile();

if( !isset($_GET["token"]) || Config::getCustomAuthSecret() != "".$_GET["token"] ) {
	return;
}

$message = parse_request( file_get_contents('php://input') );
if ( $message != "" )  {
	$url = "https://api.hipchat.com/v2/room/".Config::getHipChatRoomId()."/notification?auth_token=".Config::getHipChatAuthToken();
	$curlClient = new Curl();
	$response = (new Browser($curlClient))->post(
	    $url, 
	    array("Content-Type" => "application/json"),
	    $message
	);
	if (!$response->isSuccessful()) {
		echo "Error " . $response->getStatusCode() . ": " ;
		echo $response->getReasonPhrase();
	} else {
		echo $response;
	}
}

?>
