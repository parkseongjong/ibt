<h2>API 테스트 페이지</h2>

<?php
/*header('Content-Type: application/json; charset=UTF-8');
header("HTTP/1.1 200 OK");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");*/

//인증 코드값 발급
$public_key = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnIJhaoPYRwfOTnrdY4YcymkuhnGf8BXih/Qdhz70lOIPjRT1oA2j4GGN/OLKyaoHQO8KuxkmA9BX5KRFtcaRxzlx15i+9zhYVec+ikytn3O2kFBQ5GwDrJs4OG5XHXISPW5OF33mgQnqSnyl7eVlYxgHxodOFJqYPti9s/O/T3imKSgrbq1RR9hFdStNdRO53wfvTPXHabCb4vAH5rm+82H9Kz8GG5x3qrx0piVLWRstFBYUR8unhfXvQTNVpcjbkv38gtaZmtOM79Vhr3rzlxdlkSqOFCPMvSQFMwoEarJZK2dZwGBhx6cdGHqy4eVQuyxIyZp2zdUY4Mgtv+ssIwIDAQAB';
$client_id = '46b6e8f6-ea96-45d3-9ae3-ad7cc821f4d1';
$client_secret = 'c63b6ced-4101-4216-8b5f-1d3f43a9012c';
$token_api = 'https://development.codef.io';
$oauth_api = 'https://oauth.codef.io/oauth/token';


$data = [];
$data['client_id'] = '46b6e8f6-ea96-45d3-9ae3-ad7cc821f4d1';
$data['client_secret'] = 'c63b6ced-4101-4216-8b5f-1d3f43a9012c';
$output = '';
$url = "https://www.example.com"; //주소셋팅
$postfields = 'client_id=46b6e8f6-ea96-45d3-9ae3-ad7cc821f4d1&client_secret=c63b6ced-4101-4216-8b5f-1d3f43a9012c'; //post값 셋팅 (id값과 password 값이 셋팅됨)

$ch = curl_init(); //curl 로딩
curl_setopt($ch, CURLOPT_URL,$token_api); //curl에 url 셋팅
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 이 셋팅은 1로 고정하는 것이 정신건강에 좋음
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 주소가 https가 아니라면 지울것
curl_setopt($ch, CURLOPT_SSLVERSION,3); // 주소가 https가 아니라면 지울것

curl_setopt($ch, CURLOPT_POST, 1); // 포스트 전송 활성화
curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields); // curl에 포스트값 셋팅

$result = curl_exec ($ch); // curl 실행 및 결과값 저장
print_r($result); //출력
curl_close ($ch); // curl 종료

exit;



// 1원계좌 데이터

$service_url = 'https://development.codef.io/v1/kr/bank/a/account/transfer-authentication';
/*실서버용
$service_url = 'https://development.codef.io/v1/kr/bank/a/account/transfer-authentication';*/
$curl = curl_init($service_url);
$curl_post_data = array(
    "organization" => "003",
    "account" => "01066253606",
    "inPrintType" => "1",
    "inPrintContent" => "",
    "cmsCode" => ""
);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
$curl_response = curl_exec($curl);
if ($curl_response === false) {
    $info = curl_getinfo($curl);
    curl_close($curl);
    die('error : ' . var_export($info));
}
curl_close($curl);
$decoded = json_decode($curl_response);
if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
    die('error: ' . $decoded->response->errormessage);
}
var_export($decoded);
?>
