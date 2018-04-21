<?php

// Reads the variables sent via POST from our gateway
$sessionId   = $_POST["sessionId"];
$serviceCode = $_POST["serviceCode"];
$phoneNumber = $_POST["phoneNumber"];
$text        = $_POST["text"];

$response = '';

$textArr = explode('*', $text); 

if ( $textArr[0] == null ) {

	 // This is the first request. Note how we start the response with CON
	 $response = "CON Welcome to Unganisha. What is your emergency? \n";
	 $response .= "1. Road Accident \n";
	 $response .= "2. Poisoning \n";
	 $response .= "3. Unconsious Person \n";

}
else if (array_key_exists(1, $textArr) ) { 
	$response = "END Help is on the way. Someone will call you shortly with further instructions.";
	 
}

else if ( $textArr[0] != null && $textArr[0] == "1" || $textArr[0] == "2" || $textArr[0] == "3" && array_key_exists(1,$textArr) === false ) {
    
  $response = "CON Where are you? e.g Runda, Muthaiga, Kibera \n";
  
 }

if($textArr[0] == 1){
	$textArr[0] = 'Road Accident';
} else if ($textArr[0] == 2) {
    $textArr[0] = 'poisoning';
} else if ($textArr[0] == 3) {
    $textArr[0] = 'Unconsious person';
}




// Print the response onto the page so that our gateway can read it
header('Content-type: text/plain');

if ($response == "") { $response = "END Help is on the way. Someone will call you shortly with further instructions."; }
echo $response ;

if (sizeof($textArr) ==2) {
	$url = 'http://unganisha-b.herokuapp.com/api/';
	$data = array('latitude' => 0, 'longitude' => 0, 'description' => $textArr[0], 'number' => $phoneNumber, 'location' => $textArr[1], 'timestamp' => '2018' );
	
	// use key 'http' even if you send the request to https://...
	$options = array(
		'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	if ($result === FALSE) { /* Handle error */ }
	
	//var_dump($result);

}

// DONE!!!