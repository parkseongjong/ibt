<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_PORT => "8332",
  CURLOPT_URL => "http://139.162.42.236:8332/",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\"jsonrpc\": \"1.0\", \"id\":\"curltest\", \"method\": \"getnewaddress\", \"params\": [] }",
  CURLOPT_HTTPHEADER => array(
    "authorization: Basic aW50cmFtbzppbnRyYW1vUmZ0dkQ2NA==",
    "cache-control: no-cache",
    "content-type: application/json",
    "postman-token: 7c016daf-9451-81ea-06f6-181c435f1f40"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}