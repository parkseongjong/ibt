<?php 

	$btcUrl = "http://13.125.120.31:8332/";
$btcPort = 8332;
	
	$email =time();
	$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_PORT => $btcPort,
		  CURLOPT_URL => $btcUrl,	
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "", 
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => "{\"jsonrpc\":\"1.0\",\"id\":\"curltest\",\"method\":\"getnewaddress\",\"params\":[\"".$email."\"]}",
		  CURLOPT_HTTPHEADER => array(
			"authorization: Basic Y3N0bWNid2lldGJla29ydGVjaGJpcGFqOiFBWHpLdzhmZkBocyRoTHQ5QFpUSWImZ0JEWVpHISUqcVZtOXRAMnVXaU8=",
			"cache-control: no-cache",
			"content-type: application/json",
			"postman-token: b177ddf8-7e00-7918-a082-08abe77d101b"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
		print_r($response);
		print_r($err);
		

?>