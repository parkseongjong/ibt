<?php
namespace App\Controller;

use App\Controller\AppController;

use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\Mailer\Email;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

use \PDO;

class TESTOJTController extends AppController
{
	public function beforeFilter(Event $event)
    {
		 parent::beforeFilter($event);
        // Allow users to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        // cause problems with normal functioning of AuthComponent.
        $this->Auth->allow(['index']);
    }
	private $basic_url = 'https://cyber.cybertronchain.com/wallet2/control.php/API/kiosk/';

    public function socket_like_curl($request, $ssl = true){
        $url = parse_url($request['url']);

        // parsing protocol
        if ($url['scheme'] == 'https') {
            $protocol = 'ssl';
            $port = isset($request['port']) ? $request['port'] : 443;

            // SSL Certification Options (Recommended options is true)
            $context = stream_context_create([
                'ssl' => [
                    'verify_peer' => $ssl,
                    'verify_peer_name' => $ssl
                ]
            ]);
        } else {
            $protocol = 'tcp';
            $port = isset($request['port']) ? $request['port'] : 80;
            $context = stream_context_create();
        }

        $url['query'] = isset($url['query']) ? $url['query'] : '';
        $request_url = $protocol . '://' . $url['host'] . ':' . $port;
        $socket = stream_socket_client($request_url, $errno, $errstr, ini_get("default_socket_timeout"), STREAM_CLIENT_CONNECT, $context);
        if ($socket) {
            $data = $url['path'] . '?' . $url['query'];

            //
            // Header Start
            //
            $header = $request['type'] . " " . $data . " HTTP/1.1" . "\r\n";
            $header .= 'Host: ' .  $url['host'] . "\r\n";

            // HTTP 1.1 is required Referer Header
            $httpReferer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
            $header .= 'Referer: ' . $httpReferer . "\r\n";

            // User-Agent
            $header .= "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36\r\n"
                . "Accept: application/json \r\n";
            $header .= "Content-type: application/x-www-form-urlencoded\r\n";
            $header .= "Content-Length: " . strlen($url['query']) . "\r\n";
            $header .= "Connection: close\r\n\r\n";
            $header .= $url['query'];

            //
            // Header End
            //

            fwrite($socket, $header);

            $data = '';
            while (!feof($socket)) {
                $data .= fgets($socket);
            }
            fclose($socket);

            $data = explode("\r\n\r\n", $data, 2);

            return $data[1];
        } else {
            return false;
        }
    }

    public function getCurl($url, $type, $data = false){
        try{
            $curl = curl_init();

            $data = http_build_query($data);

            if (stristr($url, 'cybertronchain.com') == FALSE) {
                $url = $this->basic_url.$url;
            }

            if ( $type == 'POST' ) {
                $post_type = 'POST';
                $post_boolean = true;
            } else {
                $post_type = 'GET';
                $post_boolean = false;
            }

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_HEADER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 3000,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_POST => $post_boolean,
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_CUSTOMREQUEST => $post_type,
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/json',
                    'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                    'Content-Length:'.strlen($data),
                    "cache-control: no-cache"
                ),
                CURLOPT_VERBOSE => false
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if($err){
                var_dump($err);
                throw new Exception('curl error');
            }
            //return = json_decode($response,true);
            return $response;

        }
        catch (Exception $e){
            return $e->getMessage();
        }
    }	
	public function index(){
		
		$dsn = "mysql:host=10.8.0.6;port=3306;dbname=exchange_db;charset=utf8";

		try {
			$db = new PDO($dsn, "smbit_ctc_exch", "1234");
			$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$query = "SELECT * FROM test";
			$stmt = $db->prepare($query);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_NUM);
			
			var_dump($result);

		} 
		catch(PDOException $e) {
			echo $e->getMessage();
		}

		echo('hi');
		$ret = self::getCurl('etoken/payment/list','POST',['wallet_address'=>'0xaa6e54a8fd0a670e4458257d5faa684e42ca1401','filter_col'=>'id','order_by'=>'DESC','offset'=>100]);
		#var_dump($ret);

		$postFieldString = http_build_query(['wallet_address'=>'0xaa6e54a8fd0a670e4458257d5faa684e42ca1401','filter_col'=>'id','order_by'=>'DESC','offset'=>100], '', '&');

		// request example
		$request = array(
		    'url'  => 'https://cyber.cybertronchain.com/wallet2/control.php/API/kiosk/etoken/payment/list?'.$postFieldString,
		    'port' => 443,
		    'type' => 'POST',
		);


		// call function
		$result = self::socket_like_curl($request, true);
		echo $result;

		$this->viewBuilder()->layout(false);
	}
}
?>
